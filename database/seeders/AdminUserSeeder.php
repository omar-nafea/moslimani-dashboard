<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create admin user if not exists
    User::firstOrCreate(
      ['email' => 'admin@moslimani.com'],
      [
        'name' => 'Admin',
        'email' => 'admin@moslimani.com',
        'password' => Hash::make('password'),
        'is_admin' => true,
        'email_verified_at' => now(),
      ]
    );

    $this->command->info('Admin user created/verified: admin@moslimani.com');
  }
}



