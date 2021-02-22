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
                            ->first();
            if($ledger){
                $ledger->delete();
            }

            // new
            // update product quantity in hand
            $product->quantity_in_hand += $new_quantity;
            // ---------------------------------------------------------------
            // update product purchase price
            $stockIns = StockIn::where('product_id', $query->product_id)->get();
            $amount = 0;
            $quantity = 0;
            foreach($stockIns as $stockIn){
                $amount += $stockIn->amount;
                $quantity += $stockIn->quantity;
            }
            // decrement current amount and rate
            $amount -= $old_amount;
            $quantity -= $old_quantity;
            // increment new amount and rate
            $amount += $new_amount;
            $quantity += $new_quantity;
            // calculate avg purchase price
            $purchase_price = $amount / $quantity;
            $product->purchase_price = $purchase_price;
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

            $product->save();
        });

        static::deleting(function ($query) {
            // find product
            $product = Product::find($query->product_id);

            // update product purchase price
            $stockIns = StockIn::where('product_id', $query->product_id)->get();
            $amount = 0;
            $quantity = 0;
            foreach($stockIns as $stockIn){
                $amount += $stockIn->amount;
                $quantity += $stockIn->quantity;
            }
            // decrement current amount and rate
            $amount -= $query->amount;
            $quantity -= $query->quantity;

            $purchase_price = $amount / $quantity;
            $product->purchase_price = $purchase_price;

            // update product quantity in hand
            $product->quantity_in_hand -= $query->quantity;

            // cost and sales value
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            $product->save();

            // update vendor ledger
            $ledger = Ledger::where('vendor_id', $query->vendor_id)
                            ->where('amount', $query->amount)
                            ->first();
            if($ledger){
                $ledger->delete();
            }
        });

        static::created(function ($query) {
            // find product
            $product = Product::find($query->product_id);

            // update product purchase price
            $stockIns = StockIn::where('product_id', $query->product_id)->get();
            $amount = 0;
            $quantity = 0;
            foreach($stockIns as $stockIn){
                $amount += $stockIn->amount;
                $quantity += $stockIn->quantity;
            }
            $purchase_price = $amount / $quantity;
            $product->purchase_price = $purchase_price;
            
            // update product quantity in hand
            $product->quantity_in_hand += $query->quantity;

            // cost and sales value
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            $product->save();

            // update vendor ledger
            Ledger::create([
                'vendor_id' => $query->vendor_id,
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
