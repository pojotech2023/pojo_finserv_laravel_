<?php

namespace Database\Seeders;

use App\Models\Login;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Login::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Encrypt the password
        ]);
    }
}
