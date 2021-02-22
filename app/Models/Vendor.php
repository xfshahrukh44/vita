<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'area_id',
        'address',
        'market_id',
        'channel_id',
        'hub_id',
        'business_to_date',
        'outstanding_balance',
        'contact_number',
        'whatsapp_number',
        'type',
        'floor',
        'shop_name',
        'shop_number',
        'shop_picture',
        'shop_keeper_picture',
        'payment_terms',
        'cash_on_delivery',
        'visiting_days',
        'status',
        'opening_balance',
        'special_discount',
        'account_number',
        'created_by',
        'modified_by'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = auth()->user()->id;
            $query->outstanding_balance = 0;
        });

        static::updating(function ($query) {
            $query->modified_by = auth()->user()->id;
        });

        static::created(function ($query) {
            // sales ledger account number
            $query->account_number = '4010' . $query->id;
            $query->save();
        });
    }

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel');
    }

    public function hub()
    {
        return $this->belongsTo('App\Models\Hub');
    }

    public function ledgers()
    {
        return $this->hasMany('App\Models\Ledger');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

    public function stock_ins()
    {
        return $this->hasMany('App\Models\StockIn');
    }
}
