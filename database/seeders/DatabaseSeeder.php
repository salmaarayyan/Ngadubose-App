<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data awal minimal: 1 akun admin.
     * Password diambil dari SEED_DEFAULT_PASSWORD di .env (tidak di-hardcode).
     */
    public function run(): void
    {
        $password = env('SEED_DEFAULT_PASSWORD');

        if (blank($password)) {
            throw new RuntimeException('SEED_DEFAULT_PASSWORD belum diisi di file .env.');
        }

        User::updateOrCreate(
            ['email' => 'admin@bpsbantul.com'],
            [
                'nama' => 'Admin BPS',
                // Diisi admin sendiri lewat menu Manajemen User.
                'nomor_hp' => null,
                'password' => $password,
            ],
        );
    }
}
