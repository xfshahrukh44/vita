<?php

namespace App\Http\Controllers\API;

use App\Exceptions\StockIn\AllStockInException;
use App\Exceptions\StockIn\CreateStockInException;
use App\Exceptions\StockIn\DeletedStockInException;
use App\Exceptions\StockIn\UpdateStockInException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StockInService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Validator;

class StockInController extends Controller
{
    private $stockInService;
    private $productService;

    public function __construct(StockInService $stockInService, ProductService $productService)
    {
        $this->stockInService = $stockInService;
        $this->productService = $productService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->stockInService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|int',
            'vendor_id' => 'sometimes',
            'quantity' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'transaction_date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // check if product exists
        if( ($this->productService->find($request->product_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $data = $this->stockInService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->stockInService->find($id));
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
            'product_id' => 'sometimes|int',
            'vendor_id' => 'sometimes',
            'quantity' => 'sometimes',
            'rate' => 'required',
            'amount' => 'required',
            'transaction_date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

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

        $data = $this->stockInService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->stockInService->delete($id);
    }
}
