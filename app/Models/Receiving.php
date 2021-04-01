<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ledger;
use App\Models\Invoice;

class Receiving extends Model
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
            $old_invoice_id = $query->getOriginal('invoice_id');
            $new_invoice_id = $query->invoice_id;
            $old_payment_date = $query->getOriginal('payment_date');
            $new_payment_date = $query->payment_date;

            // old
            $ledger = Ledger::where('customer_id', $query->customer_id)
                            ->where('receiving_id', $query->id)
                            ->where('amount', $old_amount)
                            ->first();
            if($ledger){
                $ledger->delete();
            }
            // if any amount paid or not
            if($old_invoice_id != NULL && $old_amount != NULL && $old_payment_date !=NULL){
                $invoice = Invoice::withTrashed()->find($old_invoice_id);
                $invoice->amount_pay -= intval($old_amount);
                $invoice->saveQuietly();
            }

            // new
            // ledger entry
            Ledger::create([
                'customer_id' => $query->customer_id,
                'receiving_id' => $query->id,
                'amount' => $new_amount,
                'type' => 'credit',
                'transaction_date' => return_todays_date()
            ]);
            // if any amount paid or not
            if($new_invoice_id != NULL && $new_amount != NULL && $new_payment_date !=NULL){
                $invoice = Invoice::withTrashed()->find($new_invoice_id);
                $invoice->amount_pay += intval($new_amount);
                $invoice->saveQuietly();
            }
        });

        static::deleting(function ($query) {
            $ledger = Ledger::where('customer_id', $query->customer_id)
                            ->where('receiving_id', $query->id)
                            ->where('amount', $query->amount)
                            ->first();
            if($ledger){
                $ledger->delete();
            }

            // if any amount paid or not
            if($query->invoice_id != NULL && $query->amount != NULL && $query->payment_date !=NULL){
                $invoice = Invoice::withTrashed()->find($query->invoice_id);
                $invoice->amount_pay -= intval($query->amount);
                $invoice->saveQuietly();
            }
        });

        static::created(function ($query) {
            // ledger entry
            Ledger::create([
                'customer_id' => $query->customer_id,
                'invoice_id' => $query->invoice_id ? $query->invoice_id : NULL,
                'receiving_id' => $query->id,
                'amount' => $query->amount,
                'type' => 'credit',
                'transaction_date' => return_todays_date()
            ]);

            // if any amount paid or not
            if($query->invoice_id != NULL && $query->amount != NULL && $query->payment_date !=NULL){
                $invoice = Invoice::withTrashed()->find($query->invoice_id);
                $invoice->amount_pay += intval($query->amount);
                $invoice->saveQuietly();
            }
        });
    }
    
    protected $fillable = [
        'invoice_id', 'customer_id', 'amount', 'payment_date', 'created_by', 'modified_by',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    public function ledgers()
    {
        return $this->hasMany('App\Models\Ledger');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function marketings()
    {
        return $this->hasMany('App\Models\Marketing');
    }
}
