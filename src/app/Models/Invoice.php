<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'service_id', 'invoice_number', 'payment_method', 
        'status', 'tax', 'total_amount', 'pdf_url'
    ];

    public function service() { return $this->belongsTo(Service::class); }
}
