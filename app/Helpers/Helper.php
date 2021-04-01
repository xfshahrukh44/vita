<?php

use Carbon\Carbon;
use App\User;
use App\Models\Marketing;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;

function return_date($date)
{
    return Carbon::parse($date)->format('j F, Y. h:i a');
}

function return_date_wo_time($date){
    return Carbon::parse($date)->format('j F, Y.');
}

function return_date_pdf($date)
{
    return Carbon::parse($date)->format('j F, Y');
}

function return_todays_date()
{
    return Carbon::now();
}

function return_user_name($id)
{
    $user = User::find($id);
    return optional($user)->name;
}

function return_decimal_number($foo)
{
    return number_format((float)$foo, 2, '.', '');
}

function return_marketing_rider_for_customer($customer_id, $date)
{
    if(!$marketing = Marketing::where('customer_id', $customer_id)-> where('date', $date)->first()){
        return '';
    }
    if(!$rider = User::find($marketing->user_id)){
        return '';
    }

    return $rider->name;
}

function return_marketing_rider_for_receiving($receiving_id, $date)
{
    if(!$marketing = Marketing::where('receiving_id', $receiving_id)-> where('date', $date)->first()){
        return '';
    }
    if(!$rider = User::find($marketing->user_id)){
        return '';
    }
    return $rider->name;
}

function return_marketing_rider_for_invoice($invoice_id, $date)
{
    if(!$marketing = Marketing::where('invoice_id', $invoice_id)-> where('date', $date)->first()){
        return '';
    }
    if(!$rider = User::find($marketing->user_id)){
        return '';
    }
    return $rider->name;
}

function customer_shop_name($customer_id)
{
    $customer = Customer::find($customer_id);
    $shop = (($customer->shop_name) ? (' | ' . $customer->shop_name) : '');
    $market = (($customer->market && $customer->market->name) ? (' | ' . $customer->market->name) : '');
    $area = (($customer->market && $customer->market->area &&  $customer->market->area->name) ? (' | ' . $customer->market->area->name) : '');
    return $customer->name . $shop . $market . $area;
}

function count_by_status($status)
{
    return count(Customer::where('status', $status)->get());
}

function order_count_by_status($status)
{
    return count(Order::where('status', $status)->get());
}

function last_order_dispatched_at($customer_id){
    $customer = Customer::find($customer_id);
    $customer_name = customer_shop_name($customer_id);
    $order = Order::where('customer_id', $customer_id)->where('deleted_at', NULL)->latest()->first();
    return ($order ? return_date_wo_time($order->dispatch_date) : '');
}

function set_status_by_invoiced_items($order_id){
    $order = Order::with('order_products')->find($order_id);

    if(!$order){
        return '';
    }

    $item_count = count($order->order_products);
    $invoiced_count = 0;
    foreach($order->order_products as $order_product){
        if($order_product->invoiced == 1){
            $invoiced_count += 1;
        }
    }

    if($invoiced_count == 0){
        // incomplete
        $order->status = 'incomplete';
        $order->invoiced_items = $invoiced_count;
    }
    else if($invoiced_count < $item_count){
        // pending
        $order->status = 'pending';
        $order->invoiced_items = $invoiced_count;
    }
    else{
        // complete
        $order->status = 'completed';
        $order->invoiced_items = $invoiced_count;
    }
    
    $order->saveQuietly();
}