<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Order\AllOrderException;
use App\Exceptions\Order\CreateOrderException;
use App\Exceptions\Order\DeletedOrderException;
use App\Exceptions\Order\UpdateOrderException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\OrderProductService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private $orderService;
    private $customerService;
    private $orderProductService;

    public function __construct(OrderService $orderService, CustomerService $customerService, OrderProductService $orderProductService)
    {
        $this->orderService = $orderService;
        $this->customerService = $customerService;
        $this->orderProductService = $orderProductService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->orderService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'total' => 'sometimes',
            'status' => 'sometimes',
            'description' => 'sometimes'
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

        // create order
        $data = $this->orderService->create($request->all());

        // create order product
        if($request->orderProducts){
            foreach($request->orderProducts as $orderProduct){
                $this->orderProductService->create([
                    'order_id' => $data['order']['order']['id'],
                    'product_id' => $orderProduct['product_id'],
                    'quantity' => $orderProduct['quantity'],
                    'price' => $orderProduct['price']
                ]);
            }
        }

        return response()->json($this->orderService->find($data['order']['order']['id']));
    }
    
    public function show($id)
    {
        return response()->json($this->orderService->find($id));
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
            'total' => 'sometimes',
            'status' => 'sometimes',
            'description' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // if customer_id given
        if($request->customer_id){
            // check if customer exists
            if( ($this->customerService->find($request->customer_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found.'
                ]);
            }
        }

        $data = $this->orderService->update($request->all(), $id);

        // create order product
        if($request->orderProducts){
            // delete old
            $order = $data['order']['order'];
            foreach($order->order_products as $order_product){
                $order_product->delete();
            }
            // create new
            foreach($request->orderProducts as $orderProduct){
                $this->orderProductService->create([
                    'order_id' => $order['id'],
                    'product_id' => $orderProduct['product_id'],
                    'quantity' => $orderProduct['quantity'],
                    'price' => $orderProduct['price']
                ]);
            }
        }

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->orderService->delete($id);
    }

    public function fetch_pending_orders(Request $request)
    {
        return $this->orderService->fetch_pending_orders();
    }
}