<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'customer_id',
        'total',
        'status',
        'payment',
        'amount_pay',
        'dispatch_date',
        'invoiced_items',
        'invoiced_from',
        'description',
        'created_by',
        'modified_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->user()->id;
        });

        static::updating(function ($query) {
            $query->modified_by = auth()->user()->id;
        });

        static::deleting(function ($query) {
            
        });

        static::created(function ($query) {
            
        });
    }

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function order_products()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice');
    }

    public function all_invoiced()
    {
        return $this->whereHas('order_products', function ($query) {
            return $query->where('invoiced', '=', 1);
        })->get();
    }

    public function marketings()
    {
        return $this->hasMany('App\Models\Marketing');
    }
}
