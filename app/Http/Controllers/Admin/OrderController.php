<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Services\OrderProductService;
use App\Services\UserService;
use App\Services\DiscountService;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    private $orderService;
    private $customerService;
    private $productService;
    private $orderProductService;
    private $userService;
    private $discountService;

    public function __construct(OrderService $orderService, CustomerService $customerService, ProductService $productService, OrderProductService $orderProductService, UserService $userService, DiscountService $discountService)
    {
        $this->orderService = $orderService;
        $this->customerService = $customerService;
        $this->productService = $productService;
        $this->orderProductService = $orderProductService;
        $this->userService = $userService;
        $this->discountService = $discountService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $orders = $this->orderService->paginate(env('PAGINATE'));
        $customers = $this->customerService->all();
        $products = $this->productService->all();
        $riders = $this->userService->all_riders();
        $discounts = $this->discountService->all();
        return view('admin.order.order', compact('orders', 'customers', 'products', 'riders', 'discounts'));
    }

    public function all()
    {
        return $this->orderService->all();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'total' => 'sometimes',
            'status' => 'sometimes',
            'payment' => 'sometimes',
            'amount_pay' => 'sometimes',
            'dispatch_date' => 'sometimes',
            'description' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // check status
        if($request->has('pending_status'))
            $request['status'] = 'pending';
        if($request->has('completed_status'))
            $request['status'] = 'completed';

        // create order
        $order = ($this->orderService->create($request->all()))['order']['order'];
        
        if($request->products){
            for($i = 0; $i < count($request->products); $i++){
                $this->orderProductService->create([
                    'order_id' => $order['id'],
                    'product_id' => $request->hidden_product_ids[$i],
                    'quantity' => $request->quantities[$i],
                    'price' => $request->prices[$i],
                ]);
            }
        }
        
        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->order_id;
        return $this->orderService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $order = ($this->orderService->find($id))['order'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'total' => 'sometimes',
            'status' => 'sometimes',
            'payment' => 'sometimes',
            'amount_pay' => 'sometimes',
            'dispatch_date' => 'sometimes',
            'description' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // update order
        $this->orderService->update($request->all(), $id);

        if($request->products){
            // delete old
            foreach($order->order_products as $order_product){
                $order_product->delete();
            }
            // create new
            for($i = 0; $i < count($request->products); $i++){
                $this->orderProductService->create([
                    'order_id' => $order['id'],
                    'product_id' => $request->hidden_product_ids[$i],
                    'quantity' => $request->quantities[$i],
                    'price' => $request->prices[$i],
                ]);
            }
        }

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->orderService->delete($id);

        return redirect()->back();
    }

    public function search_orders(Request $request)
    {
        $query = $request['query'];
        
        $orders = $this->orderService->search_orders($query);
        $customers = $this->customerService->all();
        $products = $this->productService->all();
        $riders = $this->userService->all_riders();

        return view('admin.order.order', compact('orders', 'customers', 'products', 'riders'));
    }

    public function fetch_order_products(Request $request)
    {
        $order = ($this->orderService->find($request->order_id))['order'];

        return $order->order_products;
    }
}
