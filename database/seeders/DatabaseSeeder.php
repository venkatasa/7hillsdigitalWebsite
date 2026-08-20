<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PackageSeeder::class);

        $email = env('ADMIN_EMAIL', 'admin@7hillsdigital.co.za');

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Admin user {$email} already exists — password left alone.");

            return;
        }

        // No weak default ships: if ADMIN_PASSWORD is unset we generate one
        // and print it once. Change it at /admin after first sign-in.
        $password = env('ADMIN_PASSWORD') ?: Str::password(16, symbols: false);

        User::create([
            'name' => env('ADMIN_NAME', 'Seven Hills Digital'),
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->command?->warn("Admin created: {$email}");
        $this->command?->warn("Password: {$password}");
        $this->command?->warn('Save this now — it is not stored anywhere else.');
    }
}
