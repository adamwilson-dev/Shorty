<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'make:user {name} {email} {password} {--admin}';
    protected $description = 'Create a new user with an optional admin role';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $isAdmin = $this->option('admin');

        $role = $isAdmin ? 'admin' : 'user';

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $this->info('User created successfully! Role: ' . $role);
    }
}
