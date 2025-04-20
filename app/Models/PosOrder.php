<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    use HasFactory;
 public function PosOrderDetail()

    {
        return $this->hasOne( PosOrderDetail::class, 'order_id');
    }

    public function PosPayment()

    {
        return $this->hasOne( PosPayment::class, 'order_id');
    }
    public function PaymentExpense()

    {
        return $this->hasOne(PaymentExpense::class, 'order_id');
    }

    public function details()
    {
        return $this->hasMany(PosOrderDetail::class, 'order_no', 'order_no');
    }

    public function order_detail()
{
    return $this->hasMany(PosOrderDetail::class);
}

protected $fillable = ['customer_id', 'total_amount'];

public function customer()
{
    return $this->belongsTo(Customer::class);
}
}