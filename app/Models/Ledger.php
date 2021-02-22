<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\Vendor;

class Ledger extends Model
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

            $old_type = $query->getOriginal('type');
            $new_type = $query->type;
            $old_amount = $query->getOriginal('amount');
            $new_amount = $query->amount;

            // fetching client from corresponding type
            $check = 0;
            if($query->customer_id != NULL){
                $client = Customer::find($query->customer_id);
                $check = 1;
            }
            if($query->vendor_id != NULL){
                $client = Vendor::find($query->vendor_id);
                $check = 1;
            }

            // if success
            if($check == 1){
                // old
                // credit
                if($old_type == 'credit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $old_amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $old_amount;
                        // business_to_date
                        $client->business_to_date -= $old_amount;
                    }

                    // save client
                    $client->save();
                }
                // debit
                if($old_type == 'debit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $old_amount;
                        // business_to_date
                        $client->business_to_date -= $old_amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $old_amount;
                    }

                    // save client
                    $client->save();
                }

                // new
                // credit
                if($new_type == 'credit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $new_amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $new_amount;
                        // business_to_date
                        $client->business_to_date += $new_amount;
                    }

                    // save client
                    $client->save();
                }
                // debit
                if($new_type == 'debit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $new_amount;
                        // business_to_date
                        $client->business_to_date += $new_amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $new_amount;
                    }
                    
                    // save client
                    $client->save();
                }
            }

        });

        static::deleting(function ($query) {
            // fetching client from corresponding type
            $check = 0;
            if($query->customer_id != NULL){
                $client = Customer::find($query->customer_id);
                $check = 1;
            }
            if($query->vendor_id != NULL){
                $client = Vendor::find($query->vendor_id);
                $check = 1;
            }

            // if success
            if($check == 1){
                // credit
                if($query->type == 'credit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $query->amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $query->amount;
                        // business_to_date
                        $client->business_to_date -= $query->amount;
                    }

                    // save client
                    $client->save();
                }

                // debit
                if($query->type == 'debit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $query->amount;
                        // business_to_date
                        $client->business_to_date -= $query->amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $query->amount;
                    }

                    // save client
                    $client->save();
                }
            }
        });

        static::created(function ($query) {
            // fetching client from corresponding type
            $check = 0;
            if($query->customer_id != NULL){
                $client = Customer::find($query->customer_id);
                $check = 1;
            }
            if($query->vendor_id != NULL){
                $client = Vendor::find($query->vendor_id);
                $check = 1;
            }

            // if success
            if($check == 1){
                // credit
                if($query->type == 'credit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $query->amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $query->amount;
                        // business_to_date
                        $client->business_to_date += $query->amount;
                    }

                    // save client
                    $client->save();
                }

                // debit
                if($query->type == 'debit'){
                    // customer
                    if($query->customer_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance += $query->amount;
                        // business_to_date
                        $client->business_to_date += $query->amount;
                    }

                    // vendor
                    if($query->vendor_id != NULL){
                        // outstanding_balance
                        $client->outstanding_balance -= $query->amount;
                    }
                    
                    // save client
                    $client->save();
                }
            }
        });
    }
    
    protected $fillable = [
        'customer_id', 'vendor_id', 'amount', 'type', 'invoice_id', 'receiving_id', 'payment_id', 'created_by', 'modified_by', 'transaction_date'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice');
    }

    public function receiving()
    {
        return $this->belongsTo('App\Models\Receiving');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment');
    }
}
