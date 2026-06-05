<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_name', 'status', 'total_price'];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status' => 'string',
    ];


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
