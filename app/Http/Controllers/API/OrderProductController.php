<?php

namespace App\Http\Controllers\API;

use App\Exceptions\OrderProduct\AllOrderProductException;
use App\Exceptions\OrderProduct\CreateOrderProductException;
use App\Exceptions\OrderProduct\DeletedOrderProductException;
use App\Exceptions\OrderProduct\UpdateOrderProductException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderProductService;
use App\Services\ProductService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Validator;

class OrderProductController extends Controller
{
    private $orderProductService;
    private $productService;
    private $orderService;

    public function __construct(OrderProductService $orderProductService, ProductService $productService, OrderService $orderService)
    {
        $this->orderProductService = $orderProductService;
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json($this->orderProductService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'sometimes',
            'price' => 'sometimes',
            'current_amount' => 'sometimes',
            'previous_amount' => 'somtimes',
            'final_amount' => 'sometimes',
            'payment' => 'sometimes',
            'amount' => 'sometimes',
            'balance_due' => 'sometimes',
            'dispatch_date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // check if order exists
        if( ($this->orderService->find($request->order_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ]);
        }

        // check if product exists
        if( ($this->productService->find($request->product_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $data = $this->orderProductService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->orderProductService->find($id));
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
            'order_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'sometimes',
            'price' => 'sometimes',
            'current_amount' => 'sometimes',
            'previous_amount' => 'somtimes',
            'final_amount' => 'sometimes',
            'payment' => 'sometimes',
            'amount' => 'sometimes',
            'balance_due' => 'sometimes',
            'dispatch_date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // if order_id given
        if($request->order_id){
            // check if order exists
            if( ($this->orderService->find($request->order_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.'
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

        $data = $this->orderProductService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->orderProductService->delete($id);
    }
}
