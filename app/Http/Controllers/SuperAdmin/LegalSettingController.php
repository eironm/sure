<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\BrandingService;
use App\Services\LegalService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalSettingController extends Controller
{
    /**
     * Show the whitelabel legal documents editor.
     */
    public function index()
    {
        $documents = [];
        foreach (LegalService::documents() as $key => $label) {
            $documents[$key] = [
                'label'     => $label,
                'setting'   => LegalService::settingKey($key),
                'languages' => LegalService::languages($key),
            ];
        }

        return view('superadmin.settings.legal', compact('documents'));
    }

    /**
     * Persist the edited legal documents.
     *
     * Expected payload:
     *   documents[<doc>][<i>][code|label|body]
     */
    public function update(Request $request)
    {
        $payload = $request->input('documents', []);

        foreach (LegalService::documents() as $doc => $label) {
            $entries = [];

            foreach ((array) ($payload[$doc] ?? []) as $lang) {
                $body = trim((string) ($lang['body'] ?? ''));
                if ($body === '') {
                    continue; // skip empty languages
                }
                $code  = Str::slug((string) ($lang['code'] ?? '')) ?: 'lang';
                $label2 = trim((string) ($lang['label'] ?? '')) ?: strtoupper($code);

                $entries[] = [
                    'code'  => $code,
                    'label' => $label2,
                    'body'  => $body,
                ];
            }

            SystemSetting::setValue(
                LegalService::settingKey($doc),
                json_encode($entries, JSON_UNESCAPED_UNICODE)
            );
        }

        // Keep the public Last Updated label tied to a successful save, not page render time.
        SystemSetting::setValue('legal_documents_updated_at', now()->toIso8601String());

        BrandingService::flush();

        return redirect()->route('superadmin.settings.legal')
            ->with('success', 'Legal documents updated successfully.');
    }
}
