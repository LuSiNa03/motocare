<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id', 'plate_number', 'brand', 'model', 
        'year', 'color', 'engine_number', 'chassis_number', 
        'init_km', 'qr_code', 'photo', 'next_service_date', 'next_service_km'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
