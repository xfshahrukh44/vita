<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Models\StockIn;

class StockIn extends Model
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

            // find product
            $product = Product::find($query->product_id);

            $old_quantity = $query->getOriginal('quantity');
            $new_quantity = $query->quantity;
            $old_amount = $query->getOriginal('amount');
            $new_amount = $query->amount;
            $old_rate = $query->getOriginal('rate');
            $new_rate = $query->rate;

            // old
            // update product quantity in hand
            $product->quantity_in_hand -= $old_quantity;

            // update vendor ledger
            $ledger = Ledger::where('vendor_id', $query->vendor_id)
                            ->where('amount', $query->amount)
                            ->where('type', 'credit')
                            ->first();
            if($ledger){
                $ledger->delete();
            }

            // new
            $product->purchase_price = ($product->cost_value - $old_amount + $new_amount) / ( $product->quantity_in_hand - $old_quantity + $new_quantity);
            
            // update product quantity in hand
            $product->quantity_in_hand += $new_quantity;
            
            // cost and sales value
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;

            // update vendor ledger
            Ledger::create([
                'vendor_id' => $query->vendor_id,
                'amount' => $new_amount,
                'type' => 'credit',
                'transaction_date' => return_todays_date()
            ]);

            $product->saveQuietly();
        });

        static::deleting(function ($query) {
            // find product
            $product = Product::find($query->product_id);

            $product->purchase_price = ($product->cost_value - $query->amount) / ( $product->quantity_in_hand - $query->quantity);

            // update product quantity in hand
            $product->quantity_in_hand -= $query->quantity;

            // cost and sales value
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            $product->saveQuietly();

            // update vendor ledger
            $ledger = Ledger::where('vendor_id', $query->vendor_id)
                            ->where('amount', $query->amount)
                            ->where('type', 'credit')
                            ->first();
            if($ledger){
                $ledger->delete();
            }
        });

        static::created(function ($query) {
            // find product
            $product = Product::find($query->product_id);

            $product->purchase_price = ($product->cost_value + $query->amount) / ( $product->quantity_in_hand + $query->quantity);
            
            // update product quantity in hand
            $product->quantity_in_hand += $query->quantity;

            // cost and sales value
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            $product->saveQuietly();

            // update vendor ledger
            Ledger::create([
                'vendor_id' => (($query->vendor_id) ? ($query->vendor_id) : NULL),
                'customer_id' => 0,
                'amount' => $query->amount,
                'type' => 'credit',
                'transaction_date' => return_todays_date()
            ]);
        });
    }
    
    protected $fillable = [
        'vendor_id', 'product_id', 'quantity', 'rate', 'amount', 'transaction_date', 'created_by', 'modified_by'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }
}
