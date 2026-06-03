<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'vehicle_id', 'branch_id', 'service_package_id',
        'date', 'time', 'status', 'queue_number', 'notes'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function servicePackage() { return $this->belongsTo(ServicePackage::class); }
}
