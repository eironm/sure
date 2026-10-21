<?php

namespace App\Rules;

use App\Models\PasswordHistory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

/**
 * Ensures the new password has never been used before by this user.
 * Checks against all entries in the password_histories table.
 */
class NotPreviousPassword implements ValidationRule
{
    public function __construct(
        protected int $userId
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $previousPasswords = PasswordHistory::where('user_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->pluck('password_hash');

        foreach ($previousPasswords as $oldHash) {
            if (Hash::check($value, $oldHash)) {
                $fail('You cannot reuse a previous password. Please choose a new one you haven\'t used before.');
                return;
            }
        }
    }
}
