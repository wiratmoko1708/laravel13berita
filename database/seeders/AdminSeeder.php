<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@berita.test'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );
    }
}
