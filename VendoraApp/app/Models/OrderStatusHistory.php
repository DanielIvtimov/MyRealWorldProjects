<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'notes',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
