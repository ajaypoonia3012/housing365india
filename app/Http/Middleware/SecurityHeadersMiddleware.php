<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and enforce robust HTTP security headers.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Remove identifying server headers to prevent reconnaissance
        if (function_exists('header_remove') && ! headers_sent()) {
            header_remove('X-Powered-By');
        }
        $response->headers->remove('X-Powered-By');

        // 2. Prevent Clickjacking: deny framing from unauthorized origins
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. Prevent MIME-type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Enable browser XSS filtering
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 5. Control Referrer Policy to prevent leaking sensitive URLs
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 6. Restrict sensitive hardware permissions
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), payment=(), usb=()');

        // 7. Strict-Transport-Security (HSTS) when running over HTTPS or in production
        if ($request->isSecure() || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
