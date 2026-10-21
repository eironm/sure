<?php
namespace App\Console\Commands;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TestGate extends Command
{
    protected $signature = 'test:gate';
    protected $description = 'simulate login + verify-code flow';

    public function handle()
    {
        User::where('email', 'gate.test@example.com')->delete();

        $user = User::create([
            'name' => 'Gate Tester',
            'email' => 'gate.test@example.com',
            'password' => Hash::make('GatePass123'),
            'client_type' => 'external',
            'role' => 'guest',
            'is_active' => true,
        ]);

        // Seed + set a KNOWN code (hashed) for the real HTTP harness (test_gate_http.ps1)
        $code = '567890';
        $user->update([
            'verification_code'            => Hash::make($code),
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);
        echo 'seeded_code=' . $code . PHP_EOL;
    }
}

