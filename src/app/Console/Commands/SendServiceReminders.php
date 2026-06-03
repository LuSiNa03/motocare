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
    protected $signature = 'app:send-service-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send smart service reminders to customers whose vehicles are due for service based on date or KM.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa kendaraan yang butuh servis...');

        // 1. Check by next_service_date (due today or overdue)
        $dueByDate = Vehicle::whereNotNull('next_service_date')
            ->where('next_service_date', '<=', Carbon::now()->toDateString())
            ->get();

        foreach ($dueByDate as $vehicle) {
            $user = $vehicle->user;
            if (!$user) continue;

            // Check if reminder was already sent for this date cycle
            $exists = \App\Models\ServiceReminder::where('vehicle_id', $vehicle->id)
                ->where('reminder_type', 'date')
                ->where('created_at', '>=', Carbon::now()->startOfDay())
                ->exists();

            if (!$exists) {
                $msg = "Halo {$user->name}, motor {$vehicle->brand} {$vehicle->model} ({$vehicle->plate_number}) Anda sudah memasuki tanggal jadwal servis berkala: {$vehicle->next_service_date}. Yuk booking servis sekarang di MotoCare!";
                
                \App\Models\ServiceReminder::create([
                    'vehicle_id' => $vehicle->id,
                    'user_id' => $user->id,
                    'reminder_type' => 'date',
                    'status' => 'sent',
                    'message' => $msg,
                    'sent_at' => now(),
                ]);

                \Illuminate\Support\Facades\Log::info("[SMART REMINDER WHATSAPP/SMS] To: {$user->email} | Msg: {$msg}");
                $this->line("Notifikasi tanggal dikirim ke: {$user->name} ({$vehicle->plate_number})");
            }
        }

        // 2. Check by current_km vs next_service_km (if next_service_km is set, check if we are within 200km of it)
        $dueByKm = Vehicle::whereNotNull('next_service_km')
            ->get();

        foreach ($dueByKm as $vehicle) {
            $user = $vehicle->user;
            if (!$user) continue;

            // Get last service current_km to compare
            $lastService = \App\Models\Service::where('vehicle_id', $vehicle->id)->latest()->first();
            $currentKm = $lastService ? $lastService->current_km : 0;

            if ($currentKm > 0 && ($vehicle->next_service_km - $currentKm) <= 200) {
                // Check if reminder was already sent for this km cycle
                $exists = \App\Models\ServiceReminder::where('vehicle_id', $vehicle->id)
                    ->where('reminder_type', 'km')
                    ->where('created_at', '>=', Carbon::now()->subDays(7)) // limit to once a week
                    ->exists();

                if (!$exists) {
                    $msg = "Halo {$user->name}, motor {$vehicle->brand} {$vehicle->model} ({$vehicle->plate_number}) Anda mendekati kilometer batas servis berkala: {$vehicle->next_service_km} km (KM saat ini: {$currentKm} km). Yuk booking servis sekarang di MotoCare!";

                    \App\Models\ServiceReminder::create([
                        'vehicle_id' => $vehicle->id,
                        'user_id' => $user->id,
                        'reminder_type' => 'km',
                        'status' => 'sent',
                        'message' => $msg,
                        'sent_at' => now(),
                    ]);

                    \Illuminate\Support\Facades\Log::info("[SMART REMINDER WHATSAPP/SMS] To: {$user->email} | Msg: {$msg}");
                    $this->line("Notifikasi KM dikirim ke: {$user->name} ({$vehicle->plate_number})");
                }
            }
        }

        $this->info('Selesai memeriksa dan mengirim pengingat servis.');
    }
}
