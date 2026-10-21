<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

/**
 * Seeds default (English) legal documents into system settings.
 *
 * Idempotent: only writes a document when the setting is empty, so an admin's
 * own published content is never overwritten by re-running the seeder.
 */
class LegalDocsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'legal_terms' => json_encode([
                [
                    'code'  => 'en',
                    'label' => 'English',
                    'body'  => <<<'MD'
# Terms & Conditions

Welcome to the Smart University Reservation Engine (SURE). By registering an account or using this system, you agree to the terms below.

## 1. Account eligibility
Internal accounts are limited to currently enrolled students, faculty, and staff of the university. External clients must provide a valid government-issued identification during registration.

## 2. Reservations
- Reservations are subject to availability and admin approval where required.
- A reservation is only confirmed once it is approved and, where applicable, paid in full.
- Failure to pay by the stated deadline may result in automatic cancellation.
- The university reserves the right to cancel or reschedule any reservation for maintenance, emergencies, or official university functions.

## 3. User conduct
You agree not to misuse the system, create duplicate or false accounts, share your credentials, or use the platform for any unlawful purpose.

## 4. Changes to these terms
We may update these terms from time to time. Continued use of the system after changes are posted constitutes acceptance of the revised terms.
MD,
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),

            'legal_privacy' => json_encode([
                [
                    'code'  => 'en',
                    'label' => 'English',
                    'body'  => <<<'MD'
# SURE Policies & Privacy Notice

This notice explains how the Smart University Reservation Engine (SURE) collects and protects your personal information.

## 1. Information we collect
- **Account details**: name, affiliation, contact information, and (for external clients) government ID type.
- **Reservation data**: facility bookings, schedules, and payment records.
- **Technical data**: IP address, device information, and session cookies necessary for the system to function.

## 2. How we use your information
Your information is used to process reservations, communicate booking status, verify university affiliation, and maintain the security of the platform. We do not sell or share your personal data with third parties for marketing purposes.

## 3. Data retention
Reservation records are retained for university administrative and audit purposes. Account data is retained while your account is active and removed or anonymised upon verified request, subject to legal obligations.

## 4. Your rights
You may request access to, correction of, or deletion of your personal data through the profile settings or by contacting the administration.

## 5. Security
We apply industry-standard safeguards, including password hashing, encrypted sessions, and access controls. Payment processing is handled through PayMongo's secure infrastructure.
MD,
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),

            'legal_data_collection' => json_encode([
                [
                    'code'  => 'en',
                    'label' => 'English',
                    'body'  => <<<'MD'
# Data Collection & Usage Policy

This document describes, in plain terms, what data SURE collects automatically and why.

## 1. Essential cookies
SURE uses session cookies to keep you signed in and security tokens to protect forms against cross-site request forgery. These are strictly necessary and cannot be disabled without breaking login.

## 2. Accessibility preferences
Your accessibility settings (contrast, font size, safety toggles) are stored locally so they persist between visits.

## 3. Reservation and payment records
Booking details and payment statuses are stored to operate the reservation workflow and produce audit trails for university accountability.

## 4. No tracking
SURE does not use advertising trackers, third-party analytics scripts, or cross-site tracking of any kind.
MD,
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        foreach ($defaults as $key => $value) {
            if (empty(SystemSetting::getValue($key))) {
                SystemSetting::setValue($key, $value);
                $this->command->info("Seeded default legal document: {$key}");
            } else {
                $this->command->info("Skipped {$key} — admin content already exists.");
            }
        }
    }
}
