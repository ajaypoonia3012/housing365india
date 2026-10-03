<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Check if authenticated user can review a property.
     */
    public function checkEligibility(Request $request, Property $property)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['eligible' => false, 'reason' => 'Please log in to submit a review.'], 401);
        }

        $result = $this->reviewService->checkEligibility(
            $user,
            $property,
            $request->query('interaction_type'),
            $request->query('interaction_id') ? (int) $request->query('interaction_id') : null
        );

        return response()->json($result);
    }

    /**
     * Store review on a property.
     */
    public function storePropertyReview(Request $request, Property $property)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:150',
            'body' => 'required|string|min:10|max:2000',
            'interaction_type' => 'nullable|string',
            'interaction_id' => 'nullable|integer',
        ]);

        $review = $this->reviewService->submitReview($user, $property, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your review has been verified and published.',
                'review' => $review,
            ]);
        }

        return back()->with('status', 'Your verified customer review has been published.');
    }

    /**
     * Official response from property owner or vendor to a review.
     */
    public function storeResponse(Request $request, Review $review)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'body' => 'required|string|min:5|max:1000',
        ]);

        $response = $this->reviewService->submitResponse($user, $review, $validated['body']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Official response submitted.',
                'response' => $response,
            ]);
        }

        return back()->with('status', 'Your response has been published.');
    }

    /**
     * Report a review for abuse or fake content.
     */
    public function report(Request $request, Review $review)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'reason' => 'required|string|in:spam,harassment,false_information,privacy_violation,irrelevant,conflict_of_interest,suspected_fraud,duplicate,other',
            'details' => 'nullable|string|max:500',
        ]);

        $report = $this->reviewService->reportReview($user, $review, $validated['reason'], $validated['details'] ?? null);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for reporting this review. Our moderation team has been notified.',
            ]);
        }

        return back()->with('status', 'Review report submitted for moderation.');
    }
}
