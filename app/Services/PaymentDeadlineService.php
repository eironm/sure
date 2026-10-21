<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\SystemSetting;
use Carbon\Carbon;

class PaymentDeadlineService
{
    public static function value(): int
    {
        return max(1, (int) SystemSetting::getValue("payment_deadline_value", SystemSetting::getValue("payment_deadline_days", "3")));
    }

    public static function unit(): string
    {
        $unit = SystemSetting::getValue("payment_deadline_unit", "days");
        return in_array($unit, ["hours", "days"], true) ? $unit : "days";
    }

    public static function basis(): string
    {
        return SystemSetting::getValue("payment_deadline_basis", "after_creation") === "before_event" ? "before_event" : "after_creation";
    }

    public static function calculate(Carbon|string $createdAt, Carbon|string $eventStart): Carbon
    {
        $createdAt = $createdAt instanceof Carbon ? $createdAt->copy() : Carbon::parse($createdAt);
        $eventStart = $eventStart instanceof Carbon ? $eventStart->copy() : Carbon::parse($eventStart);
        $amount = static::value();
        $deadline = static::basis() === "before_event" ? (static::unit() === "hours" ? $eventStart->subHours($amount) : $eventStart->subDays($amount)) : (static::unit() === "hours" ? $createdAt->addHours($amount) : $createdAt->addDays($amount));
        return $deadline->lt($createdAt) ? $createdAt : $deadline;
    }

    public static function forReservation(Reservation $reservation, ?Carbon $createdAt = null): Carbon
    {
        $eventStart = Carbon::parse($reservation->start_date . " " . $reservation->start_time);
        return static::calculate($createdAt ?: ($reservation->created_at ?: now()), $eventStart);
    }

    public static function config(): array
    {
        return ["basis" => static::basis(), "value" => static::value(), "unit" => static::unit()];
    }
    public static function earliestEligibleDate(?Carbon $now = null): Carbon
    {
        $now = ($now ?: now())->startOfDay();
        $windowEnd = static::unit() === 'hours' ? $now->copy()->addHours(static::value()) : $now->copy()->addDays(static::value());
        return $windowEnd->startOfDay()->addDay();
    }

    public static function isDateEligible(Carbon|string $date, ?Carbon $now = null): bool
    {
        return Carbon::parse($date)->startOfDay()->greaterThanOrEqualTo(static::earliestEligibleDate($now));
    }

}
