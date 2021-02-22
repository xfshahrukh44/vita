<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// open routes
Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@store');

// closed routes
Route::group(['middleware' => ['auth:api', 'cors'], 'prefix' => 'auth'], function() {
    
    // User
    Route::post('logout', 'API\AuthController@logout');
    Route::post('me', 'API\AuthController@me');
    Route::apiResources(['user'=>'API\AuthController']);

    // Customer
    Route::apiResources(['customer'=>'API\CustomerController']);

    // Market
    Route::apiResources(['market'=>'API\MarketController']);

    // Area
    Route::apiResources(['area'=>'API\AreaController']);

    // Category
    Route::apiResources(['category'=>'API\CategoryController']);

    // Brand
    Route::apiResources(['brand'=>'API\BrandController']);

    // Unit
    Route::apiResources(['unit'=>'API\UnitController']);

    // Product
    Route::apiResources(['product'=>'API\ProductController']);

    // Order
    Route::apiResources(['order'=>'API\OrderController']);
    Route::get('/fetch_pending_orders', 'API\OrderController@fetch_pending_orders')->name('fetch_pending_orders');

    // OrderProduct
    Route::apiResources(['order_product'=>'API\OrderProductController']);

    // Ledger
    Route::apiResources(['ledger'=>'API\LedgerController']);

    // StockIn
    Route::apiResources(['stock_in'=>'API\StockInController']);

    // StockOut
    Route::apiResources(['stock_out'=>'API\StockOutController']);

    // invoice
    Route::apiResources(['invoice'=>'API\InvoiceController']);

    // marketing
    Route::apiResources(['marketing'=>'API\MarketingController']);

    // channel
    Route::apiResources(['channel'=>'API\ChannelController']);

    // hub
    Route::apiResources(['hub'=>'API\HubController']);

    // helpers
    Route::get('/fetch_specific_markets', 'API\MarketController@fetch_specific_markets');

    // generate invoice pdf
    Route::get('/generate_invoice_pdf', 'API\InvoiceController@generate_invoice_pdf')->name('generate_invoice_pdf');

});
