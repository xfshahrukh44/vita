<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\Vendor;

class Marketing extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->user()->id;
        });

        static::updating(function ($query) {
            $query->modified_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'customer_id', 'invoice_id', 'order_id', 'receiving_id', 'user_id', 'date', 'created_by', 'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function receiving()
    {
        return $this->belongsTo('App\Models\Receiving');
    }

    public function rider()
    {
        return $this->belongsTo('App\User');
    }
}
