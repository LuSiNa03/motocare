<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotoCareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Branches
        $branch1 = \App\Models\Branch::firstOrCreate(
            ['name' => 'MotoCare Bandung'],
            ['address' => 'Jl. Braga No. 1, Bandung', 'phone' => '0221234567']
        );
        $branch2 = \App\Models\Branch::firstOrCreate(
            ['name' => 'MotoCare Cimahi'],
            ['address' => 'Jl. Amir Machmud, Cimahi', 'phone' => '0227654321']
        );

        // Roles
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
        $pegawaiRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'pegawai']);
        $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);

        // Users
        $superAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@motocare.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'super_admin'
            ]
        );
        if (!$superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole($superAdminRole);
        }

        $pegawai = \App\Models\User::firstOrCreate(
            ['email' => 'pegawai@motocare.com'],
            [
                'name' => 'Pegawai Bandung',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'pegawai',
                'branch_id' => $branch1->id
            ]
        );
        if (!$pegawai->hasRole('pegawai')) {
            $pegawai->assignRole($pegawaiRole);
        }

        $customer = \App\Models\User::firstOrCreate(
            ['email' => 'user@motocare.com'],
            [
                'name' => 'Budi Customer',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user'
            ]
        );
        if (!$customer->hasRole('user')) {
            $customer->assignRole($userRole);
        }

        // Vehicles
        \App\Models\Vehicle::firstOrCreate(
            ['plate_number' => 'D 1234 ABC'],
            [
                'user_id' => $customer->id,
                'brand' => 'Honda',
                'model' => 'Vario 160',
                'year' => 2023,
                'color' => 'Hitam',
                'qr_code' => 'QR-D1234ABC'
            ]
        );
    }
}
