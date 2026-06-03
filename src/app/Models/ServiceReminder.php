<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReminder extends Model
{
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'reminder_type',
        'status',
        'message',
        'sent_at',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
