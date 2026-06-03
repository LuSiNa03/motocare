<?php

namespace App\Observers;

use App\Models\ServiceDetail;
use App\Models\Sparepart;
use Illuminate\Support\Facades\DB;

class ServiceDetailObserver
{
    /**
     * Handle the ServiceDetail "created" event.
     * Kurangi stok sparepart saat detail servis ditambahkan.
     */
    public function created(ServiceDetail $serviceDetail): void
    {
        if ($serviceDetail->type === 'sparepart' && $serviceDetail->item_id) {
            $sparepart = Sparepart::find($serviceDetail->item_id);
            if ($sparepart) {
                $sparepart->stock = max(0, $sparepart->stock - $serviceDetail->qty);
                $sparepart->save();
            }
        }

        $this->recalculateTotalCost($serviceDetail);
    }

    /**
     * Handle the ServiceDetail "updated" event.
     * Sesuaikan stok jika detail servis diupdate.
     */
    public function updated(ServiceDetail $serviceDetail): void
    {
        $oldItemId = $serviceDetail->getOriginal('item_id');
        $oldQty = $serviceDetail->getOriginal('qty');
        $oldType = $serviceDetail->getOriginal('type');

        // Jika sebelumnya merupakan sparepart, kembalikan stok lama
        if ($oldType === 'sparepart' && $oldItemId) {
            $oldSparepart = Sparepart::find($oldItemId);
            if ($oldSparepart) {
                $oldSparepart->stock += $oldQty;
                $oldSparepart->save();
            }
        }

        // Kurangi stok baru jika tipe baru adalah sparepart
        if ($serviceDetail->type === 'sparepart' && $serviceDetail->item_id) {
            $newSparepart = Sparepart::find($serviceDetail->item_id);
            if ($newSparepart) {
                $newSparepart->stock = max(0, $newSparepart->stock - $serviceDetail->qty);
                $newSparepart->save();
            }
        }

        $this->recalculateTotalCost($serviceDetail);
    }

    /**
     * Handle the ServiceDetail "deleted" event.
     * Kembalikan stok jika detail servis dihapus.
     */
    public function deleted(ServiceDetail $serviceDetail): void
    {
        if ($serviceDetail->type === 'sparepart' && $serviceDetail->item_id) {
            $sparepart = Sparepart::find($serviceDetail->item_id);
            if ($sparepart) {
                $sparepart->stock += $serviceDetail->qty;
                $sparepart->save();
            }
        }

        $this->recalculateTotalCost($serviceDetail);
    }

    /**
     * Recalculate parent Service total cost and update associated Invoice.
     */
    protected function recalculateTotalCost(ServiceDetail $serviceDetail): void
    {
        $service = $serviceDetail->service;
        if ($service) {
            // Recalculate total cost of all details
            $totalCost = $service->details()->sum(DB::raw('qty * price'));
            $service->total_cost = $totalCost;
            $service->saveQuietly(); // Gunakan saveQuietly agar tidak memicu loop observer tak terbatas jika ada observer lain

            // Sync dengan invoice jika ada
            $invoice = $service->invoice;
            if ($invoice) {
                $invoice->tax = $totalCost * 0.11;
                $invoice->total_amount = $totalCost * 1.11;
                $invoice->save();
            }
        }
    }
}
