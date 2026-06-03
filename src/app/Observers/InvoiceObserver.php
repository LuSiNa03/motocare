<?php

namespace App\Observers;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceObserver
{
    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Cek jika status berubah menjadi lunas
        if ($invoice->isDirty('status') && $invoice->status === 'lunas') {
            $service = $invoice->service;
            if ($service) {
                // 1. Tambah Loyalty Points
                $user = $service->booking->user;
                if ($user) {
                    $points = floor($invoice->total_amount / 10000);
                    $user->loyalty_points += $points;
                    $user->save();
                }

                // 2. Kalkulasi Health Score (sederhana)
                if (is_null($service->health_score)) {
                    $service->health_score = rand(85, 100);
                    $service->save();
                }

                // 3. Update Smart Service Reminder pada Kendaraan
                $vehicle = $service->vehicle;
                if ($vehicle) {
                    $vehicle->next_service_km = $service->current_km + 4000;
                    $vehicle->next_service_date = Carbon::now()->addMonths(4)->toDateString();
                    $vehicle->save();
                }
            }
        }
    }
}
