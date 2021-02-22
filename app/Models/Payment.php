<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ledger;

class Payment extends Model
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
            // ledger entry
            $ledger = Ledger::where('vendor_id', $query->vendor_id)
                            ->where('payment_id', $query->id)
                            ->where('amount', $old_amount)
                            ->first();
            if($ledger){
                $ledger->delete();
            }

            // new
            // ledger entry
            Ledger::create([
                'vendor_id' => $query->vendor_id,
                'payment_id' => $query->id,
                'amount' => $new_amount,
                'type' => 'debit',
                'transaction_date' => return_todays_date()
            ]);
        });

        static::deleting(function ($query) {
            // ledger entry
            $ledger = Ledger::where('vendor_id', $query->vendor_id)
                            ->where('payment_id', $query->id)
                            ->where('amount', $query->amount)
                            ->first();
            if($ledger){
                $ledger->delete();
            }
        });

        static::created(function ($query) {
            // ledger entry
            Ledger::create([
                'vendor_id' => $query->vendor_id,
                'payment_id' => $query->id,
                'amount' => $query->amount,
                'type' => 'debit',
                'transaction_date' => return_todays_date()
            ]);
        });
    }
    
    protected $fillable = [
        'vendor_id', 'expense_id', 'amount', 'created_by', 'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function ledgers()
    {
        return $this->hasMany('App\Models\Ledger');
    }

    public function expense()
    {
        return $this->belongsTo('App\Models\Expense');
    }
}
