<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator BPS',
            'email' => 'admin@admin.com',
            'nomor_hp' => '083896735071',
            'password' => Hash::make('admin123'), 
            'created_at' => now(),
            'updated_at' => now(),                     
        ]);
    }
}