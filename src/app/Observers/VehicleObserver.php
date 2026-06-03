<?php

namespace App\Observers;

use App\Models\Vehicle;
use Illuminate\Support\Str;

class VehicleObserver
{
    /**
     * Handle the Vehicle "creating" event.
     */
    public function creating(Vehicle $vehicle): void
    {
        if (empty($vehicle->qr_code)) {
            $vehicle->qr_code = Str::upper(Str::random(12));
        }
    }
}
