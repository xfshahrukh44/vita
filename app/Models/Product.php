<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Product;
use App\Events\ThresholdReached;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'category_id',
        'brand_id',
        'unit_id',
        'article',
        'gender',
        'purchase_price',
        'consumer_selling_price',
        'retailer_selling_price',
        'opening_quantity',
        'moq',
        'quantity_in_hand',
        'product_picture',
        'cost_value',
        'sales_value',
        'sub_category_id',
        'article_code',
        'description',
        'case_count',
        'net_weight_pc',
        'case_weight',
        'created_by',
        'modified_by'
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

        static::created(function ($query) {
            // stock_in entry
            if($query->opening_quantity > 0){
                StockIn::create([
                    'vendor_id' => 0,
                    'product_id' => $query->id,
                    'quantity' => $query->opening_quantity,
                    'rate' => $query->purchase_price,
                    'amount' => $query->opening_quantity * $query->purchase_price
                ]);
            }

            // cost and sales value
            $product = Product::find($query->id);
            $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            $product->save();


            // pusher
            if($query->opening_quantity < $query->moq){
                $category = $query->category->name;
                $brand = $query->brand->name;
                $article = $query->article;
                $message = $category.'-'.$brand.'-'.$article.' is low on stock.';
                event(new ThresholdReached($message));
            }
        });

        static::updated(function ($query) {
            // // cost and sales value
            // $product = Product::find($query->id);
            // $product->cost_value = $product->quantity_in_hand * $product->purchase_price;
            // $product->sales_value = $product->quantity_in_hand * $product->consumer_selling_price;
            // $product->save();

            // pusher
            // if($query->quantity_in_hand < $query->moq){
            //     $category = $query->category->name;
            //     $brand = $query->brand->name;
            //     $article = $query->article;
            //     $message = $category.'-'.$brand.'-'.$article.' is low on stock.';
            //     event(new ThresholdReached($message));
            // }
        });
    }

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Models\Category', 'sub_category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function order_products()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function stock_ins()
    {
        return $this->hasMany('App\Models\StockIn');
    }

    public function stock_outs()
    {
        return $this->hasMany('App\Models\StockOut');
    }

    public function special_discounts()
    {
        return $this->hasMany('App\Models\SpecialDiscount');
    }
}
