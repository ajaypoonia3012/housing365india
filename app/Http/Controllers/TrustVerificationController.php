<?php

namespace App\Http\Controllers;

use App\Models\AccountSuspension;
use App\Models\Appeal;
use App\Models\Property;
use App\Models\Verification;
use App\Models\VerificationDocument;
use App\Models\VerificationType;
use App\Services\FraudService;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrustVerificationController extends Controller
{
    public function __construct(
        protected VerificationService $verificationService,
        protected FraudService $fraudService
    ) {}

    /**
     * User Trust & Verification Hub.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $verifications = Verification::with(['verificationType', 'documents'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $badges = $user->activeBadges()->get();

        $activeSuspension = $user->activeSuspension;
        $appeals = Appeal::where('user_id', $user->id)->latest()->get();

        $verificationTypes = VerificationType::active()->get();

        $userProperties = Property::where('user_id', $user->id)->get();

        return view('dashboard.trust.index', compact(
            'user',
            'verifications',
            'badges',
            'activeSuspension',
            'appeals',
            'verificationTypes',
            'userProperties'
        ));
    }

    /**
     * Submit verification request with private document uploads.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'verification_type_code' => 'required|string|exists:verification_types,code',
            'subject_type' => 'nullable|string',
            'subject_id' => 'nullable|integer',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'document_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $subject = $user;
        if (! empty($validated['subject_type']) && ! empty($validated['subject_id'])) {
            $class = $validated['subject_type'] === 'property' ? Property::class : User::class;
            $found = $class::find($validated['subject_id']);
            if ($found && ($found instanceof Property ? $found->user_id === $user->id : $found->id === $user->id)) {
                $subject = $found;
            }
        }

        $files = [];
        if ($request->hasFile('document')) {
            $files['document'] = $request->file('document');
        }

        $this->verificationService->submitVerification(
            $subject,
            $user,
            $validated['verification_type_code'],
            ['notes' => $validated['notes'] ?? null],
            $files
        );

        return redirect()->route('trust.dashboard')
            ->with('status', 'Verification request submitted successfully. Our compliance team will review your documents.');
    }

    /**
     * Secure, authorization-controlled private document download.
     */
    public function downloadDocument(VerificationDocument $document)
    {
        $user = Auth::user();

        // Security check: Only document owner or Admin can access
        if ($document->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Unauthorized access to confidential verification document.');
        }

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Document file not found in secure storage.');
        }

        return Storage::disk('local')->download($document->file_path, $document->original_filename ?? 'verification_document.pdf');
    }

    /**
     * Submit user appeal against rejected verification or account suspension.
     */
    public function submitAppeal(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'appealable_type' => 'required|string',
            'appealable_id' => 'required|integer',
            'reason' => 'required|string|min:10|max:1000',
        ]);

        $appealableClass = match ($validated['appealable_type']) {
            'verification' => Verification::class,
            'suspension' => AccountSuspension::class,
            default => null,
        };

        if (! $appealableClass) {
            abort(422, 'Invalid appeal target type.');
        }

        $target = $appealableClass::findOrFail($validated['appealable_id']);

        if ($target->user_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Unauthorized to appeal this record.');
        }

        $this->fraudService->submitAppeal($user, $target, $validated['reason']);

        return redirect()->route('trust.dashboard')
            ->with('status', 'Your appeal has been submitted for compliance review.');
    }
}
