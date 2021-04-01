<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'level', 'percentage', 'created_by', 'modified_by'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = ((auth()->user()) ? (auth()->user()->id) : NULL);
        });

        static::updating(function ($query) {
            $query->modified_by = ((auth()->user()) ? (auth()->user()->id) : NULL);
        }); 
    }

    public function invoice_products()
    {
        return $this->hasMany('App\Models\InvoiceProduct');
    }
}
