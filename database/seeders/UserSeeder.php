<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'System Admin',
       
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        UserModel::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Demo Client',
              
                'password' => Hash::make('client123'),
                'role' => 'client',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        UserModel::updateOrCreate(
            ['email' => 'free@gmail.com'],
            [
                'name' => 'Demo Freelancer',
          
                'password' => Hash::make('freelancer123'),
                'role' => 'freelancer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}