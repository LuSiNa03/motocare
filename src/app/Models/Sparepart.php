<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'branch_id', 'sku', 'name', 'category', 'supplier',
        'purchase_price', 'selling_price', 'min_stock', 'stock'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
