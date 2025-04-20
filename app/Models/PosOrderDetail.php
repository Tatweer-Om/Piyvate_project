<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosOrderDetail extends Model
{
    use HasFactory;

    public function posOrder() {
        return $this->belongsTo(PosOrder::class, 'order_id');
    }
    public function order()
    {
        return $this->belongsTo(PosOrder::class, 'order_no', 'order_no');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
