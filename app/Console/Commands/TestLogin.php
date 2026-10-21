<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestLogin extends Command
{
    protected $signature = 'test:login {u} {p}';
    protected $description = 'test login credentials';

    public function handle()
    {
        $ok = Auth::once(['email' => $this->argument('u'), 'password' => $this->argument('p')]);
        echo 'auth_result=' . ($ok ? 'OK' : 'FAIL') . PHP_EOL;
    }
}
