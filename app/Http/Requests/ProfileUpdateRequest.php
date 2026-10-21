<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isAdmin = $this->user()->isAdminOrAbove();

        return [
            'first_name' => $isAdmin ? ['required', 'string', 'max:255'] : [],
            'middle_name' => $isAdmin ? ['nullable', 'string', 'max:255'] : [],
            'last_name' => $isAdmin ? ['required', 'string', 'max:255'] : [],
            'email' => $isAdmin ? ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)] : [],
        ];
    }
}
