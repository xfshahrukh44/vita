<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'created_by', 'modified_by'
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
    }

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function markets()
    {
        return $this->hasMany('App\Models\Market');
    }
    
    public function vendors()
    {
        return $this->hasMany('App\Models\Vendor');
    }
}
