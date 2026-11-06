<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CrUser; // ✅ ADD THIS
use Illuminate\Support\Facades\Hash; 

class CrUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
        
        CrUser::create([
    'name' => 'Sales',
    'email' => 'sales@demo.com',
    'password' => Hash::make('Sales1221'),
]);
    
    }
}
