<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Branches if not exists
        if (Branch::count() === 0) {
            Branch::insert([
                [
                    'name' => 'MotoCare Pusat Bandung',
                    'address' => 'Jl. Raya Bengkel No.1, Bandung',
                    'phone' => '08111222333',
                    'latitude' => -6.9175,
                    'longitude' => 107.6191,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'MotoCare Cabang Cimahi',
                    'address' => 'Jl. Amir Machmud No. 99, Cimahi',
                    'phone' => '08222333444',
                    'latitude' => -6.8868,
                    'longitude' => 107.5361,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // 2. Create Service Packages if not exists
        if (ServicePackage::count() === 0) {
            ServicePackage::insert([
                [
                    'name' => 'Servis Ringan / Berkala',
                    'description' => 'Pengecekan standar 15 titik, setel rantai/CVT, dan semprot karbu/injeksi.',
                    'price' => 75000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Paket Ganti Oli & Servis',
                    'description' => 'Servis ringan ditambah pergantian oli mesin standar.',
                    'price' => 125000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Servis Besar (Turun Mesin Sebagian)',
                    'description' => 'Pembersihan kerak ruang bakar, skir klep, dan pengecekan piston.',
                    'price' => 350000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Tune Up Performa',
                    'description' => 'Setel injeksi/karbu performa tinggi, bersihkan CVT total.',
                    'price' => 150000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
