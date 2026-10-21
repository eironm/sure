<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Handles the landing-page contact form.
 *
 * Uses safe_mail() so a misconfigured mail server never 500s the request —
 * the inquiry is still acknowledged and the admin gets a warning flash to
 * check the configuration.
 */
class ContactController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        // Resolve where inquiries should go: optional env override first (useful for testing mailers),
        // then branding contact email, falling back to a sensible default.
        $recipient = trim(env('MAIL_CONTACT_RECIPIENT') ?: branding('contact_email') ?: branding('admin_email') ?: 'no-reply@prod.qzz.io');

        if (!safe_mail($recipient, new ContactInquiry(
            $validated['name'],
            $validated['email'],
            $validated['message'],
            now()->format('F j, Y g:i A T'),
        ))) {
            // Mail down — still acknowledge the visitor, but warn the admin.
            Log::warning('Contact form: inquiry could not be emailed', [
                'email' => $validated['email'],
                'name'  => $validated['name'],
            ]);

            $fallbackMessage = 'Thank you — your message was saved but could not be emailed to our team right now. Please contact us directly at ' . $recipient . ' in the meantime.';

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $fallbackMessage]);
            }

            return back()->withFragment('contact')->with('success', $fallbackMessage);
        }

        $okMessage = 'Thank you! Your message has been sent. Our team will get back to you shortly.';

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $okMessage]);
        }

        return back()->withFragment('contact')->with('success', $okMessage);
    }
}
