<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $settings = [];
        $settings = [
            ["key" => "payment_deadline_basis", "value" => "after_creation", "type" => "select", "group" => "payment_methods", "label" => "Payment Deadline Starts From"],
            ["key" => "payment_deadline_value", "value" => "3", "type" => "text", "group" => "payment_methods", "label" => "Payment Deadline Amount"],
            ["key" => "payment_deadline_unit", "value" => "days", "type" => "select", "group" => "payment_methods", "label" => "Payment Deadline Unit"],
        ];
        foreach ($settings as $setting) {
            if (! DB::table("system_settings")->where("key", $setting["key"])->exists()) {
                DB::table("system_settings")->insert(array_merge($setting, ["created_at" => $now, "updated_at" => $now]));
            }
        }
    }

    public function down(): void
    {
        DB::table("system_settings")->whereIn("key", ["payment_deadline_basis", "payment_deadline_value", "payment_deadline_unit"])->delete();
    }
};

