<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'booking_id', 'vehicle_id', 'technician_id', 
        'current_km', 'total_cost', 'health_score',
        'rating', 'comment'
    ];

    public function booking() { return $this->belongsTo(Booking::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    public function details() { return $this->hasMany(ServiceDetail::class); }
    public function invoice() { return $this->hasOne(Invoice::class); }
}
