<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payment;

class Expense extends Model
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

            $old_amount = $query->getOriginal('amount');
            $new_amount = $query->amount;

            // old
            // find payment and delete
            $payment = Payment::where('expense_id', $query->id)->where('amount', $old_amount)->first();
            $payment->delete();

            // new
            // payment entry
            Payment::create([
                'expense_id' => $query->id,
                'amount' => $new_amount
            ]);
        });

        static::deleting(function ($query) {
            // find payment and delete
            $payment = Payment::where('expense_id', $query->id)->where('amount', $query->amount)->first();
            $payment->delete();
        });

        static::created(function ($query) {
            // payment entry
            Payment::create([
                'expense_id' => $query->id,
                'amount' => $query->amount
            ]);
        });
    }

    protected $fillable = [
        'detail', 'type', 'amount', 'date', 'stock_out_id', 'created_by', 'modified_by'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function payment()
    {
        return $this->hasOne('App\Models\Payment');
    }
}
