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
        // Super Admin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('super_admin');

        // Pegawai
        $user = User::firstOrCreate(
            ['email' => 'pegawai@gmail.com'],
            [
                'name' => 'Pegawai',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('pegawai');

        // Users
        $users = [
            ['name' => 'Bagas',  'email' => 'bagas@gmail.com'],
            ['name' => 'Tupen',  'email' => 'tupen@gmail.com'],
            ['name' => 'Farel',  'email' => 'farel@gmail.com'],
            ['name' => 'Iis',    'email' => 'iis@gmail.com'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->assignRole('user');
        }
    }
}
