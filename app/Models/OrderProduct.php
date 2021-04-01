<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StockOut;
use App\Models\StockIn;
use App\Models\Order;
use App\Models\OrderProduct;

class OrderProduct extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'invoiced',
        'foc',
        'discount',
        'current_amount',
        'previous_amount',
        'final_amount',
        'payment',
        'amount',
        'balance_due',
        'dispatch_date',
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

            $order_product = OrderProduct::find($query->id);
            if($order_product){
                set_status_by_invoiced_items($order_product->order_id);
            }
        });

        static::deleting(function ($query) {
            set_status_by_invoiced_items($query->order_id);
        });

        static::created(function ($query) {
            
        });
    }

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
