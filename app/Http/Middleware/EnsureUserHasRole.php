<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        if (empty($roles)) {
            return $next($request);
        }

        // Support admin bypass or explicit role check
        if ($user->hasRole('admin') || $user->hasAnyRole($roles)) {
            // Synchronize active role in session if single role requested
            if (count($roles) === 1 && $user->hasRole($roles[0])) {
                session(['active_role' => $roles[0]]);
            }

            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized workspace access. This persona is not assigned to your Housing365 account.',
                'required_roles' => $roles,
            ], 403);
        }

        abort(403, 'Unauthorized workspace access. The requested Housing365 workspace ('.implode(', ', $roles).') is not assigned to your account.');
    }
}
