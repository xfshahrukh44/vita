<?php

namespace App\Http\Controllers\API;

use App\Exceptions\StockOut\AllStockOutException;
use App\Exceptions\StockOut\CreateStockOutException;
use App\Exceptions\StockOut\DeletedStockOutException;
use App\Exceptions\StockOut\UpdateStockOutException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StockOutService;
use App\Services\ProductService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    private $stockOutService;
    private $productService;
    private $customerService;

    public function __construct(StockOutService $stockOutService, ProductService $productService, CustomerService $customerService)
    {
        $this->stockOutService = $stockOutService;
        $this->productService = $productService;
        $this->customerService = $customerService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->stockOutService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'somtimes',
            'price' => 'sometimes',
            'transaction_date' => 'sometimes',
            'expense_type' => 'sometimes',
            'narration' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // check if customer exists
        if( ($this->customerService->find($request->customer_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.'
            ]);
        }

        // check if product exists
        if( ($this->productService->find($request->product_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $data = $this->stockOutService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->stockOutService->find($id));
    }
    
    public function update(Request $request, $id)
    {
        if(auth()->user()->type != "superadmin")
        {
            return response()->json([
                'success' => false,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'somtimes',
            'price' => 'sometimes',
            'transaction_date' => 'sometimes',
            'expense_type' => 'sometimes',
            'narration' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // if customer_id given
        if($request->customer_id){
            // check if customer exists
            if( ($this->customerService->find($request->customer_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ]);
            }
        }

        // if product_id given
        if($request->product_id){
            // check if product exists
            if( ($this->productService->find($request->product_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ]);
            }
        }

        $data = $this->stockOutService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->stockOutService->delete($id);
    }
}