<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Mail\WelcomeRegistration;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'first_name'      => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.\']+$/u'],
            'middle_name'     => ['nullable', 'string', 'max:255', 'regex:/^[\pL\s\-\.\']+$/u'],
            'last_name'       => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-\.\']+$/u'],
            'email'           => ['required', 'string', 'lowercase', 'email:rfc', 'max:255', 'unique:'.User::class],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'contact_number'  => ['required', 'string', 'max:30', 'regex:/^(?:\+[1-9]\d{6,14}|(?:\+?63|0)\d{9,10})$/'],
            'client_type'     => ['required', 'in:internal,external'],
            'terms_agreed'    => ['required', 'accepted'],
            // Universal personal identity
            'date_of_birth'   => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'sex'             => ['required', 'in:Male,Female'],
            'civil_status'    => ['required', 'in:Single,Married,Widowed,Separated'],
            'nationality'     => ['required', 'string', 'max:100'],
            'personal_address'=> ['required', 'string', 'max:1000'],
        ];

        // Track the legal agreement BEFORE validation. The 'terms_agreed'
        // field is validated as required/accepted below — if validation
        // fails and we redirect back, the session must already hold this
        // flag so the register page can keep the checkbox checked and
        // locked instead of making the user re-read everything.
        if ($request->input('terms_agreed') === '1') {
            $request->session()->put('registration_legal_agreed', true);
        } else {
            $request->session()->forget('registration_legal_agreed');
        }

        if ($request->client_type === 'internal') {
            $rules['university_email'] = [
                'required',
                'string',
                'email:rfc',
                'max:255',
            ];
            $rules['university_id']        = ['required', 'string', 'max:50'];
            $rules['affiliation_type']     = ['required', 'in:Student,Faculty,Admin/Staff'];
            $rules['college_department']   = ['required', 'string', 'max:255'];
        } else {
            $rules['organization_name']    = ['nullable', 'string', 'max:255'];
            $rules['billing_address']      = ['required', 'string', 'max:1000'];
            $rules['government_id_type']   = ['required', 'string', 'max:100'];
            $rules['government_id_number'] = ['required', 'string', 'max:100'];
        }

        $request->validate($rules, [
            'date_of_birth.before_or_equal' => 'You must be at least 18 years old to register.',
            'contact_number.regex'          => 'Please enter a valid contact number with country code (e.g., +639171234567 or 09171234567).',
            'first_name.regex'              => 'First name may only contain letters, spaces, hyphens, and apostrophes.',
            'middle_name.regex'             => 'Middle name may only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex'               => 'Last name may only contain letters, spaces, hyphens, and apostrophes.',
            'email.email'                   => 'Please enter a valid email address.',
        ]);

        $fullName = $request->first_name . ($request->middle_name ? ' ' . $request->middle_name : '') . ' ' . $request->last_name;

        // Internal requires admin approval, external is instant activation
        $isActive = ($request->client_type === 'external');

        $userData = [
            'name'             => $fullName,
            'first_name'       => $request->first_name,
            'middle_name'      => $request->middle_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'password'         => $request->password,
            'role'             => 'guest',
            'is_active'        => $isActive,
            'client_type'      => $request->client_type,
            'contact_number'   => $request->contact_number,
            // Universal personal identity
            'date_of_birth'    => $request->date_of_birth,
            'sex'              => $request->sex,
            'civil_status'     => $request->civil_status,
            'nationality'      => $request->nationality,
            'personal_address' => $request->personal_address,
        ];

        if ($request->client_type === 'internal') {
            $userData['university_email']  = $request->university_email;
            $userData['university_id']     = $request->university_id;
            $userData['affiliation_type']  = $request->affiliation_type;
            $userData['college_department']= $request->college_department;
        } else {
            $userData['organization_name']    = $request->organization_name ?: 'Individual';
            $userData['billing_address']      = $request->billing_address;
            $userData['government_id_type']   = $request->government_id_type;
            $userData['government_id_number'] = $request->government_id_number;
        }

        try {
            $user = DB::transaction(function () use ($userData) {
                return User::create($userData);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Registration failed — database error', [
                'email'   => $request->email,
                'error'   => $e->getMessage(),
                'code'    => $e->getCode(),
            ]);

            // Surface the real error so we can debug
            $errorDetail = config('app.debug')
                ? ' Debug: ' . $e->getMessage()
                : ' Check storage/logs/laravel.log for details.';

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('registration_error', 'An unexpected error occurred while creating your account. Please try again. If this persists, contact support.' . $errorDetail);
        } catch (\Exception $e) {
            Log::error('Registration failed — general error', [
                'email'   => $request->email,
                'error'   => $e->getMessage(),
            ]);

            $errorDetail = config('app.debug')
                ? ' Debug: ' . $e->getMessage()
                : '';

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('registration_error', 'Something went wrong during registration. Please try again.' . $errorDetail);
        }

        event(new Registered($user));

        // Send branded welcome email — never let a broken mail transport
        // crash registration. A failed welcome email shows a warning flash
        // instead of a 500 error page.
        if (!safe_mail($user->email, new WelcomeRegistration($user))) {
            $request->session()->flash('warning', 'Your account was created, but we could not send the confirmation email right now. You can request the verification code again on the login screen.');
        }

        // PRE-LOGIN GATE: the account is NOT logged in yet. A 6-digit
        // verification code is generated and emailed now (external users
        // get it automatically; internal users also need admin approval
        // after the code). The user must enter the code before login.
        VerificationCodeController::sendCode($user);

        $request->session()->put('verify_email', $user->email);

        // Legal documents no longer need to be re-read for this session.
        $request->session()->forget('registration_legal_agreed');

        $info = $request->client_type === 'internal'
            ? 'Account created! A verification code has been sent to your email. Enter it below — note that university accounts also require administrator approval before full access.'
            : 'Account created! A 6-digit verification code has been sent to your email. Enter it below to activate your account.';

        return redirect()->route('verify-code')->with('info', $info);
    }
}
