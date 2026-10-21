<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Whitelabel legal documents.
 *
 * Each legal document (Terms of Service, Privacy Policy, Data Collection Notice)
 * is stored as a single JSON setting holding an ordered list of language entries:
 *
 *   [
 *     { "code": "en", "label": "English",  "body": "<markdown>" },
 *     { "code": "fil","label": "Filipino", "body": "<markdown>" }
 *   ]
 *
 * Universities can add / remove / reorder languages freely and edit the body as
 * Markdown (rendered to HTML at display time). Defaults below reproduce the
 * previously hardcoded EN + Filipino content so nothing changes visually until a
 * university customizes it.
 */
return new class extends Migration
{
    public function up(): void
    {
        $docs = [
            [
                'key'   => 'legal_terms',
                'label' => 'Terms of Service',
                'value' => json_encode([
                    ['code' => 'en',  'label' => 'English',  'body' => $this->termsEn()],
                    ['code' => 'fil', 'label' => 'Filipino', 'body' => $this->termsFil()],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'key'   => 'legal_privacy',
                'label' => 'Privacy Policy',
                'value' => json_encode([
                    ['code' => 'en',  'label' => 'English',  'body' => $this->privacyEn()],
                    ['code' => 'fil', 'label' => 'Filipino', 'body' => $this->privacyFil()],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'key'   => 'legal_data_collection',
                'label' => 'Data Collection Notice',
                'value' => json_encode([
                    ['code' => 'en',  'label' => 'English',  'body' => $this->dataEn()],
                    ['code' => 'fil', 'label' => 'Filipino', 'body' => $this->dataFil()],
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];

        foreach ($docs as $doc) {
            if (DB::table('system_settings')->where('key', $doc['key'])->doesntExist()) {
                DB::table('system_settings')->insert([
                    'key'        => $doc['key'],
                    'value'      => $doc['value'],
                    'type'       => 'legal',
                    'group'      => 'legal',
                    'label'      => $doc['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'legal_terms',
            'legal_privacy',
            'legal_data_collection',
        ])->delete();
    }

    /* ── Default content (Markdown) ─────────────────────────────────── */

    private function termsEn(): string
    {
        return <<<MD
## Terms of Service

By creating an account and using this platform, you agree to be legally bound by these Terms. If you do not agree, you must not use this platform.

### 1. Eligibility
Use of this platform is restricted to currently enrolled students, faculty members, and duly authorized personnel of the University. You must provide accurate, current, and complete information during registration. Accounts created with false information are subject to immediate deactivation without notice.

### 2. Account Responsibilities
You are solely responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. Notify the University System Administrator immediately if you suspect unauthorized access.

- Do not share your account credentials with any other person.
- Do not use another person's account without their explicit permission.
- You are responsible for all reservation requests submitted under your account.

### 3. Facility Reservations
All reservation requests are subject to availability and approval by an authorized administrator. Submission of a reservation request does not guarantee approval.

- Facilities must be used only for the purpose stated in the reservation request.
- Unauthorized use, subletting, or transfer of reserved facilities is strictly prohibited.
- You are responsible for leaving the facility in the same condition as received.
- Any damages caused during the reservation period may result in financial liability.

### 4. Payment Obligations
Applicable fees must be settled in full prior to or on the date of facility use, as directed by the University Cashier. Failure to complete payment may result in cancellation of your reservation and suspension of your reservation privileges.

### 5. Cancellation and Modification
Reservation cancellations and modifications are subject to the University's cancellation policy. Approved reservations cancelled within 48 hours of the scheduled start time may be subject to a cancellation fee.

### 6. Prohibited Conduct
You expressly agree not to use the platform for any unlawful purpose, attempt unauthorized access, submit false requests, disrupt platform availability, or use automated tools, bots, or scripts.

### 7. Termination
We reserve the right to suspend or deactivate your account at any time if we determine that you have violated these Terms or any applicable University policy.

### 8. Disclaimer of Warranties
The platform is provided on an "as-is" and "as-available" basis, without warranties of any kind.

### 9. Limitation of Liability
To the maximum extent permitted by law, the University shall not be liable for any indirect, incidental, or consequential damages arising from your use of the platform.

### 10. Modifications to These Terms
We may update these Terms from time to time. Continued use after changes constitutes acceptance of the revised Terms.

### 11. Governing Law
These Terms are governed by the laws of the Republic of the Philippines.

### 12. Contact
For questions regarding these Terms, please contact the University IT / Systems Administration Office.
MD;
    }

    private function termsFil(): string
    {
        return <<<MD
## Mga Tuntunin ng Serbisyo (Terms of Service)

Sa paggawa ng account at paggamit ng platform na ito, sumasang-ayon kang sumailalim nang legal sa Mga Tuntuning ito. Kung hindi ka sumasang-ayon, hindi mo dapat gamitin ang platform na ito.

### 1. Kwalipikasyon (Eligibility)
Ang paggamit sa platform na ito ay limitado lamang sa mga kasalukuyang nakatalang estudyante, faculty, at pinahintulutang tauhan ng Unibersidad. Dapat kang magbigay ng tumpak at kumpletong impormasyon sa pagrehistro.

### 2. Mga Pananagutan sa Account
Ikaw lamang ang may pananagutan sa pagpapanatiling kumpidensyal ng iyong account.

- Huwag ibahagi ang iyong mga detalye sa pag-login sa ibang tao.
- Huwag gamitin ang account ng ibang tao nang walang pahintulot.
- Pananagutan mo ang lahat ng reservation requests sa ilalim ng iyong account.

### 3. Pagpareserba ng Pasilidad
Ang lahat ng request ay nakasalalay sa availability at pag-apruba ng awtorisadong administrator. Ang pagpapasa ng request ay hindi garantiya ng pag-apruba.

### 4. Mga Obligasyon sa Pagbabayad
Ang mga bayarin ay dapat bayaran nang buo bago o sa mismong araw ng paggamit, ayon sa tagubilin ng Cashier ng Unibersidad.

### 5. Pagkansela at Pagbabago
Napapailalim sa patakaran sa pagkansela ng Unibersidad. Ang mga naaprubahang reserbasyon na kinansela sa loob ng 48 oras ay maaaring patawan ng cancellation fee.

### 6. Ipinagbabawal na Gawi
Sumasang-ayon kang hindi gagamitin ang platform sa ilegal na layunin, hindi mag-a-access nang walang pahintulot, at hindi gagamit ng bots o scripts.

### 7. Pagwawakas
Inilalaan namin ang karapatang suspindihin o i-deactivate ang iyong account kung lumabag ka sa Mga Tuntuning ito.

### 8. Disclaimer
Ang platform ay ibinibigay na "as-is" at "as-available".

### 9. Limitasyon sa Pananagutan
Hanggang sa pinahihintulutan ng batas, hindi mananagot ang Unibersidad sa anumang di-tuwirang pinsala.

### 10. Pagbabago sa Mga Tuntunin
Maaari naming i-update ang Mga Tuntuning ito paminsan-minsan.

### 11. Namamahalang Batas
Pinamamahalaan ng mga batas ng Republika ng Pilipinas.

### 12. Pakikipag-ugnayan
Makipag-ugnayan sa Office of IT / Systems Administration ng Unibersidad.
MD;
    }

    private function privacyEn(): string
    {
        return <<<MD
## Privacy Policy

This Privacy Policy explains how the University collects, uses, stores, and protects your personal information when you use this platform.

### 1. Information We Collect
- **Identity Information:** First name, middle name, last name.
- **Contact Information:** Institutional email address.
- **Account Credentials:** Password (stored encrypted; never in plaintext).
- **Reservation Data:** Facility requests, purpose, date/time, payment details.
- **Usage & Device Data:** Pages visited, timestamps, browser, IP address for security logging.

### 2. How We Use Your Information
- Authenticate your identity and manage your account.
- Process, approve, and track facility reservation requests.
- Send notifications regarding reservation status.
- Generate payment records and official documentation.
- Maintain audit logs and detect fraudulent activity.

### 3. Legal Basis for Processing
Contract performance, legitimate interests (security), consent, and legal obligations under the Data Privacy Act of 2012 (RA 10173).

### 4. Data Sharing and Disclosure
We do not sell, rent, or trade your personal information. Data may be shared with authorized University personnel, when legally required, and with service providers such as PayMongo.

### 5. Data Retention
We retain your personal information for as long as your account is active and as required to fulfill the purposes described in this policy.

### 6. Data Security
Bcrypt password hashing, HTTPS-encrypted transmission, CSRF protection, token-based sessions, and role-based access control.

### 7. Your Rights
Under the Data Privacy Act of 2012 you have the rights to access, correction, erasure, objection, data portability, and to lodge a complaint with the National Privacy Commission.

### 8. Cookies and Local Storage
The platform uses only session cookies strictly necessary for authentication. No tracking or advertising cookies are used.

### 9. Changes to This Policy
We may update this Privacy Policy periodically. Significant changes will be communicated via platform notifications or email.

### 10. Contact Us
For privacy-related concerns, contact the University Data Protection Officer through the IT / Systems Administration Office.
MD;
    }

    private function privacyFil(): string
    {
        return <<<MD
## Patakaran sa Privacy (Privacy Policy)

Ipinapaliwanag ng Patakarang ito kung paano nangongolekta, gumagamit, nagtatago, at nagpoprotekta ang Unibersidad ng iyong personal na impormasyon.

### 1. Impormasyong Kinokolekta Namin
- **Pagkakakilanlan:** Unang pangalan, gitnang pangalan, apelyido.
- **Pakikipag-ugnayan:** Institutional email address.
- **Kredensyal:** Password (naka-encrypt; hindi kailanman plaintext).
- **Data sa Reserbasyon:** Mga hiling na pasilidad, dahilan, petsa/oras, detalye ng bayad.

### 2. Paano Namin Ginagamit
- Kumpirmahin ang pagkakakilanlan at pamahalaan ang account.
- Iproseso at subaybayan ang mga reserbasyon.
- Magpadala ng mga abiso.
- Gumawa ng mga talaan ng pagbabayad at audit logs.

### 3. Legal na Basehan
Pagpapatupad ng kontrata, lehitimong interes, pahintulot, at legal na obligasyon sa ilalim ng Data Privacy Act of 2012 (RA 10173).

### 4. Pagbabahagi ng Data
Hindi namin ibinebenta o ipinagpapalit ang iyong data. Ibinabahagi lamang sa awtorisadong tauhan, kapag hinihingi ng batas, at sa mga service provider tulad ng PayMongo.

### 5. Pagpapanatili ng Data
Pinapanatili habang aktibo ang account at ayon sa kinakailangan ng batas.

### 6. Seguridad ng Data
Bcrypt hashing, HTTPS, CSRF protection, token-based sessions, at role-based access control.

### 7. Ang Iyong Mga Karapatan
Sa ilalim ng RA 10173 may karapatan kang mag-access, magwasto, magpabura, tumutol, at magreklamo sa National Privacy Commission.

### 8. Mga Cookie
Session cookies lamang na kinakailangan sa authentication. Walang tracking cookies.

### 9. Pagbabago sa Patakaran
Maaari naming i-update ang Patakarang ito paminsan-minsan.

### 10. Pakikipag-ugnayan
Makipag-ugnayan sa Data Protection Officer sa pamamagitan ng IT / Systems Administration Office.
MD;
    }

    private function dataEn(): string
    {
        return <<<MD
## Data Collection Notice

Issued in compliance with the **Data Privacy Act of 2012 (RA 10173)**. It informs you of the specific personal data collected, the purpose of collection, and your rights as a data subject.

### 1. Personal Information Controller
The University, acting through its authorized Systems Administration and IT Department, is the **Personal Information Controller (PIC)**.

### 2. Specific Personal Data Collected
- **Registration:** Full name, institutional email, encrypted password, assigned role, account status.
- **Reservation:** Facility, date, time slot, purpose, attendees, status history.
- **Payment:** Method, transaction references, amount, status, cashier confirmation.
- **System & Security:** Login/logout timestamps, IP address, audit log entries, session identifiers.

### 3. Purpose of Data Collection
Identity verification, reservation management, payment processing, notification delivery, access control, audit and accountability, and system security.

### 4. Legal Basis for Processing
Performance of a contract, compliance with legal obligation, consent, and legitimate interests.

### 5. Data Retention Schedule
- **Account Data:** Minimum 3 years after deactivation.
- **Reservation Records:** Minimum 5 years.
- **Payment Records:** Minimum 7 years.
- **Audit Logs:** Minimum 5 years.
- **Session & Security Logs:** Up to 1 year.

### 6. Data Sharing
Shared only with University personnel on a need-to-know basis, PayMongo for payments, and regulatory authorities when required by law. Your data is **never** sold or traded.

### 7. Data Subject Rights
Right to be informed, access, rectification, erasure, restrict processing, data portability, object, and to lodge a complaint with the National Privacy Commission (www.privacy.gov.ph).

### 8. Cross-Border Data Transfers
Data is primarily processed within the Philippines; any external processing is conducted under appropriate safeguards.

### 9. Updates to This Notice
Material updates will be communicated through the platform's notification system.

### 10. Contact the Data Protection Officer
University IT / Systems Administration Department — Compliance Reference: Republic Act No. 10173.
MD;
    }

    private function dataFil(): string
    {
        return <<<MD
## Paunawa sa Pangongolekta ng Data (Data Collection Notice)

Inilalabas bilang pagsunod sa **Data Privacy Act of 2012 (RA 10173)**. Ipinapaalam nito ang partikular na personal na data na kinokolekta, ang layunin, at ang iyong mga karapatan.

### 1. Personal Information Controller
Ang Unibersidad, sa pamamagitan ng Systems Administration at IT Department, ang **Personal Information Controller (PIC)**.

### 2. Partikular na Data na Kinokolekta
- **Pagrehistro:** Buong pangalan, institutional email, naka-encrypt na password, role, status ng account.
- **Reserbasyon:** Pasilidad, petsa, time slot, layunin, dadalo, status history.
- **Pagbabayad:** Paraan, transaction references, halaga, status, kumpirmasyon ng cashier.
- **Sistema at Seguridad:** Login/logout timestamps, IP address, audit logs, session identifiers.

### 3. Layunin ng Pangongolekta
Pagpapatunay ng pagkakakilanlan, pamamahala sa reserbasyon, pagproseso ng bayad, pagpapadala ng abiso, access control, audit, at seguridad ng sistema.

### 4. Legal na Basehan
Pagpapatupad ng kontrata, pagsunod sa legal na obligasyon, pahintulot, at lehitimong interes.

### 5. Pagtatakda sa Pagpapanatili ng Data
- **Data ng Account:** Minimum 3 taon pagkatapos ma-deactivate.
- **Rekord ng Reserbasyon:** Minimum 5 taon.
- **Rekord sa Pagbabayad:** Minimum 7 taon.
- **Audit Logs:** Minimum 5 taon.
- **Session at Security Logs:** Hanggang 1 taon.

### 6. Pagbabahagi ng Data
Ibinabahagi lamang sa tauhan ng Unibersidad sa need-to-know basis, sa PayMongo para sa bayad, at sa mga awtoridad kapag hinihingi ng batas. **Kailanman hindi** ibinebenta o ipinagpapalit.

### 7. Mga Karapatan Bilang Data Subject
Karapatang maipaalam, makakuha, magwasto, magpabura, limitahan ang pagproseso, data portability, tumutol, at magreklamo sa National Privacy Commission (www.privacy.gov.ph).

### 8. Cross-Border Data Transfers
Ang data ay pangunahing pinoproseso sa loob ng Pilipinas; ang anumang panlabas na pagproseso ay may angkop na safeguards.

### 9. Pag-update sa Paunawa
Ang mga mahahalagang update ay ipapaalam sa pamamagitan ng notification system.

### 10. Kontakin ang Data Protection Officer
University IT / Systems Administration Department — Compliance Reference: Republic Act No. 10173.
MD;
    }
};
