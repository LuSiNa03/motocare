<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendServiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:service-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send service reminders to customers whose vehicles are due for service.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa kendaraan yang butuh servis...');

        // Cari kendaraan yang jadwal servisnya tinggal 7 hari atau terlewat
        $vehicles = Vehicle::whereNotNull('next_service_date')
            ->where('next_service_date', '<=', Carbon::now()->addDays(7)->toDateString())
            ->get();

        if ($vehicles->isEmpty()) {
            $this->info('Tidak ada kendaraan yang perlu diingatkan saat ini.');
            return;
        }

        foreach ($vehicles as $vehicle) {
            $user = $vehicle->user;
            if ($user) {
                // Di sini Anda bisa menambahkan logika notifikasi (Email, WhatsApp, Push Notification)
                $this->line("Kirim notifikasi ke: {$user->email} untuk kendaraan {$vehicle->plate_number} (Tenggat: {$vehicle->next_service_date})");
            }
        }

        $this->info('Selesai mengirim pengingat servis.');
    }
}
