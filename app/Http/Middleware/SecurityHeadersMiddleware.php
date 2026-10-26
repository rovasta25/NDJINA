<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; ".
            "script-src 'self' 'unsafe-inline'; ".
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
            "font-src 'self' https://fonts.gstatic.com; ".
            "img-src 'self' data:; ".
            "connect-src 'self'; ".
            "worker-src 'self'; ".
            "manifest-src 'self'; ".
            "frame-src 'self'; ".
            "object-src 'none'; ".
            "base-uri 'self'; ".
            "form-action 'self'"
        );

        return $response;
    }
}
