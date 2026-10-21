<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Adds hardening headers to every web response:
 *  - X-Frame-Options: DENY                 → blocks clickjacking (no iframes allowed)
 *  - X-Content-Type-Options: nosniff       → stops MIME-type sniffing attacks
 *  - Referrer-Policy: strict-origin-when-cross-origin
 *  - Permissions-Policy: camera/microphone/geolocation blocked
 *  - Removes X-Powered-By (when PHP/Swoole expose it)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Strip the X-Powered-By header if PHP or the runtime exposes it.
        // Guard: header_remove() throws outside a real HTTP context (unit
        // tests, artisan commands), which would blow up the whole pipeline.
        try {
            header_remove('X-Powered-By');
        } catch (\Throwable) {
            // Non-fatal — the header simply couldn't be removed here.
        }

        return $response;
    }
}
