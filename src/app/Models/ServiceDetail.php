<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetail extends Model
{
    protected $fillable = [
        'service_id', 'type', 'item_id', 'item_name', 'qty', 'price'
    ];

    public function service() { return $this->belongsTo(Service::class); }
}
