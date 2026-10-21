<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Public legal pages (Terms, SURE Policies, Data Collection, Cookies).
 *
 * Terms/Privacy/Data Collection content is rendered from the admin-managed
 * legal documents in system settings (via the legal_doc() helper). When the
 * admin has not published content yet, a helpful "coming soon" notice is
 * shown instead of a 404 — so the footer links never dead-end.
 */
class LegalPageController extends Controller
{
    /**
     * @param string $doc The legal_doc() key: terms | privacy | data_collection
     */
    public function show(string $doc): View
    {
        $valid = ['terms', 'privacy', 'data_collection'];

        if (!in_array($doc, $valid, true)) {
            abort(404);
        }

        $titles = [
            'terms'           => 'Terms & Conditions',
            'privacy'         => 'SURE Policies & Privacy Notice',
            'data_collection' => 'Data Collection & Usage Policy',
        ];

        // legal_doc() returns a plain array; the view uses Collection methods,
        // so normalise it here (a failed doc lookup renders 'coming soon').
        $entries = collect(legal_doc($doc) ?? []);

        return view('legal.page', [
            'doc'     => $doc,
            'title'   => $titles[$doc],
            'entries' => $entries,
        ]);
    }

    /**
     * Static cookie notice — kept simple since no cookie policy doc exists.
     */
    public function cookies(): View
    {
        return view('legal.cookies');
    }
}
