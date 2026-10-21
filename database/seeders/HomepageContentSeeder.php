<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Carries over the current homepage CMS content to any environment
 * (local SQLite, production MySQL, etc.).
 *
 * Run once on deploy:
 *   php artisan db:seed --class=HomepageContentSeeder
 *
 * Safe to run multiple times: overwrites the stored value each time.
 * Generated from the local SQLite export on 2026_08_13_154501
 */
class HomepageContentSeeder extends Seeder
{
    public function run(): void
    {
        $json = <<<JSON
{
    "hero": {
        "heading": "Reserve University Facilities",
        "sub": "Book campus spaces effortlessly with our streamlined reservation platform.",
        "cta_text": "Reserve a facility",
        "eyebrow": "SIMPLIFY UNIVERSITY RESERVATIONS",
        "badges": [
            {
                "label": "100% online process"
            },
            {
                "label": "Real-time status updates"
            },
            {
                "label": "No double bookings"
            }
        ]
    },
    "about": {
        "title": "Built for every campus community",
        "text": "is a centralized reservation and approval system designed to Simplify University Reservations. Faster scheduling, transparent tracking, and a friendlier experience — built for students, faculty, and administrators alike.",
        "subtitle": "Connecting students, faculty, and administration through a unified platform that makes booking university spaces quick and hassle-free.",
        "quote": "Your campus, your space — reserved with confidence, approved with clarity."
    },
    "features": {
        "title": "Why SURE?",
        "cards": [
            {
                "icon": "bi-calendar2-plus-fill",
                "title": "Organized & reliable",
                "body": "A reservation experience that is organized, reliable, and accessible for the entire university community."
            },
            {
                "icon": "bi-eye-fill",
                "title": "Transparent by default",
                "body": "Fostering transparency and efficient facility management — the standard platform for campus reservations."
            },
            {
                "icon": "bi-bell-fill",
                "title": "Track every step",
                "body": "Follow each request step-by-step with real-time status updates and automated reminders for seamless coordination."
            },
            {
                "icon": "bi-graph-up-arrow",
                "title": "Insights for decision-makers",
                "body": "Clear logs and resource insights to better support university events, academic functions, and student activities."
            }
        ],
        "subtitle": "Everything our community needs to plan, request, and manage campus facilities â€” in one place."
    },
    "gallery": {
        "title": "Your campus. Your space.",
        "note": "Click any image to enlarge. Photos captured on campus facilities available for reservation.",
        "subtitle": "Explore the venues and spaces our community reserves every day.",
        "items": [
            {
                "caption": "Campus grounds &amp; stands",
                "image": "uploads/facilities/26UuhESgPtxHSzJIEHq8.jpg"
            },
            {
                "caption": "Event spaces",
                "image": "uploads/facilities/AsW1gxmCvigBL4W8jOvJ.jpg"
            },
            {
                "caption": "Platform preview"
            },
            {
                "caption": "Dashboard &amp; analytics"
            }
        ]
    },
    "faq": {
        "title": "Frequently asked questions",
        "groups": [
            {
                "title": "General information",
                "questions": [
                    {
                        "q": "What is SURE?",
                        "a": "SURE (Smart University Reservation Engine) is an automated web platform designed to streamline, track, and manage university facility and resource bookings across campus, ensuring a transparent process for all users."
                    },
                    {
                        "q": "Who can use the system?",
                        "a": "Students, faculty, administrative staff, recognized student organizations, and authorized external clients across university campuses."
                    },
                    {
                        "q": "What facilities and resources can be reserved?",
                        "a": "Users can reserve authorized campus venues (such as auditoriums, gymnasiums, and grounds) as well as shared event equipment."
                    }
                ]
            },
            {
                "title": "Reservations &amp; approval process",
                "questions": [
                    {
                        "q": "How do I submit a campus facility reservation request?",
                        "a": "Log into the portal using your institutional credentials, navigate to the reservation section, select your desired location or equipment, and fill in the event details before submitting for approval."
                    },
                    {
                        "q": "How long does the approval process take?",
                        "a": "Processing times depend on the specific venue and administrative review requirements, but every approval step is updated dynamically on your tracking dashboard."
                    }
                ]
            },
            {
                "title": "Policies &amp; security",
                "questions": [
                    {
                        "q": "What is the policy for modifying or cancelling a reservation?",
                        "a": "Cancellations or modifications must be submitted at least 7 days (1 week) prior to your scheduled event date. Late cancellations may result in a temporary suspension of booking privileges."
                    },
                    {
                        "q": "How does SURE handle double-booking and system security?",
                        "a": "SURE employs automated soft-lock mechanisms to prevent overlapping submission attempts and enforces role-based access secured by institutional single sign-on authentication."
                    }
                ]
            },
            {
                "title": "Support &amp; assistance",
                "questions": [
                    {
                        "q": "Who do I contact if I need help planning an event?",
                        "a": "You can reach out directly to the facilities administration team via the contact form below or email support for specialized event requirements."
                    }
                ]
            }
        ],
        "subtitle": "Everything you need to know about booking campus facilities through SURE."
    },
    "contact": {
        "title": "Contact the facilities team",
        "note": "Send us a message and we will get back to you as soon as possible. Fields marked with * are required."
    }
}
JSON;

        if (!json_validate($json)) {
            $this->command->error('HomepageContentSeeder: invalid embedded JSON, aborting.');
            return;
        }

        $existing = DB::table('system_settings')->where('key', 'homepage_content')->first();

        if ($existing) {
            DB::table('system_settings')
                ->where('key', 'homepage_content')
                ->update(['value' => $json, 'updated_at' => now()]);
        } else {
            DB::table('system_settings')->insert([
                'key' => 'homepage_content',
                'value' => $json,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
