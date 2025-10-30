<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {--username=admin} {--email=admin@admin.com} {--password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $username = $this->option('username');
        $email = $this->option('email');
        $password = $this->option('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error('User with email ' . $email . ' already exists!');
            return 1;
        }

        // Create admin user
        $user = User::create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Username', $username],
                ['Email', $email],
                ['Password', $password],
            ]
        );

        $this->line('');
        $this->info('You can now login to admin panel at: http://127.0.0.1:8000/admin');
        $this->warn('Please change the password after first login for security!');

        return 0;
    }
}
