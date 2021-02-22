<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Marketing\AllMarketingException;
use App\Exceptions\Marketing\CreateMarketingException;
use App\Exceptions\Marketing\DeletedMarketingException;
use App\Exceptions\Marketing\UpdateMarketingException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MarketingService;
use App\Services\CustomerService;
use App\Services\InvoiceService;
use App\Services\OrderService;
use App\Services\ReceivingService;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class MarketingController extends Controller
{
    private $marketingService;
    private $customerService;
    private $invoiceService;
    private $orderService;
    private $receivingService;
    private $userService;

    public function __construct(MarketingService $marketingService, CustomerService $customerService, InvoiceService $invoiceService, OrderService $orderService, ReceivingService $receivingService, UserService $userService)
    {
        $this->marketingService = $marketingService;
        $this->customerService = $customerService;
        $this->invoiceService = $invoiceService;
        $this->orderService = $orderService;
        $this->receivingService = $receivingService;
        $this->userService = $userService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->marketingService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'invoice_id' => 'sometimes',
            'order_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'user_id' => 'sometimes',
            'date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // check if category exists
        if( ($this->categoryService->find($request->category_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ]);
        }
        // check if brand exists
        if( ($this->brandService->find($request->brand_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Brand not found.'
            ]);
        }
        // check if unit exists
        if( ($this->unitService->find($request->unit_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Unit not found.'
            ]);
        }

        $data = $this->marketingService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->marketingService->find($id));
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
            'invoice_id' => 'sometimes',
            'order_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'user_id' => 'sometimes',
            'date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // if category_id given
        if($request->category_id){
            // check if category exists
            if( ($this->categoryService->find($request->category_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ]);
            }
        }

        // if brand_id given
        if($request->brand_id){
            // check if brand exists
            if( ($this->brandService->find($request->brand_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found.'
                ]);
            }
        }

        // if unit_id given
        if($request->unit_id){
            // check if unit exists
            if( ($this->unitService->find($request->unit_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ]);
            }
        }
        $data = $this->marketingService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->marketingService->delete($id);
    }
}
