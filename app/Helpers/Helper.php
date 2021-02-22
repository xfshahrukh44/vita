<?php

use Carbon\Carbon;
use App\User;
use App\Models\Marketing;

function return_date($date)
{
    return Carbon::parse($date)->format('j F, Y. h:i a');
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