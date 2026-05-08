<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HardenHttpHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Click-jacking protection.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Disable browser MIME sniffing.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Limit referer leakage to cross-origin requests.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable powerful sensors that this app does not use.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), magnetometer=(), gyroscope=()'
        );

        // Disable cross-origin opener leakage and force same-origin window grouping.
        if (! $response->headers->has('Cross-Origin-Opener-Policy')) {
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
        }

        // Foundational CSP: locks default sources to self while still permitting current
        // inline blade scripts/styles, Stripe Checkout assets, and Google Fonts CDNs that
        // the app loads. Tighten by removing 'unsafe-inline'/'unsafe-eval' once all inline
        // scripts/styles are externalised.
        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; "
                ."img-src 'self' data: blob: https:; "
                ."style-src 'self' 'unsafe-inline' https:; "
                ."script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; "
                ."font-src 'self' data: https:; "
                ."connect-src 'self' https:; "
                ."frame-src 'self' https://js.stripe.com https://hooks.stripe.com; "
                ."frame-ancestors 'self'; "
                ."base-uri 'self'; "
                ."form-action 'self' https://checkout.stripe.com"
            );
        }

        // Only emit HSTS over HTTPS so local plain-HTTP development is not stuck on TLS.
        if ($request->isSecure() && ! $response->headers->has('Strict-Transport-Security')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // Strip the framework fingerprint header.
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
