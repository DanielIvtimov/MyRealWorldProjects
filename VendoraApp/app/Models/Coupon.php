<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Models\Order;
use App\Models\CouponUsage;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order_value',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_customer',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    #[Scope()]
    protected function active(Builder $builder)
    {
        $builder->where('is_active', true);
    }

    // Check for valid coupon 
    #[Scope()]
    protected function valid(Builder $builder){
        $now = Carbon::now();
        $builder->where('is_active', true)->where(function($q) use ($now){
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })->where(function($q) use ($now){
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
        });
    }

    // Relationships
    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function usages(){
        return $this->hasMany(CouponUsage::class);
    }

    // Helper Method
    public function isValid(){
        if(!$this->is_active){
            return false;
        }
        
        if($this->expires_at && $this->starts_at->isFuture()){
            return false;
        }

        if($this->expires_at && $this->expires_at->isPast()){
            return false;
        }
        if($this->usage_limit && $this->usage->count() >= $this->usage_limit){
            return false;
        }
        return true;
    }
}
