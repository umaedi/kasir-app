<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'whatsapp' => '6285741492045',
                'email' => 'admin@tokokita.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'alamat' => 'Jl. Administrasi No. 1, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasir Utama',
                'whatsapp' => '6285741492046',
                'email' => 'kasir@tokokita.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'alamat' => 'Jl. Kasir No. 1, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Manajer Toko',
                'whatsapp' => '6281234567892',
                'email' => 'manajer@tokokita.com',
                'password' => Hash::make('password123'),
                'role' => 'manajer',
                'alamat' => 'Jl. Manajemen No. 1, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kasir 2',
                'whatsapp' => '6281234567893',
                'email' => 'kasir2@tokokita.com',
                'password' => Hash::make('password123'),
                'role' => 'kasir',
                'alamat' => 'Jl. Kasir No. 2, Jakarta',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Berhasil menambahkan ' . count($users) . ' user ke database.');
        $this->command->info('Default password untuk semua user: password123');
    }
}