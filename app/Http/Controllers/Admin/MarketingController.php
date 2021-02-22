<?php

namespace App\Http\Controllers\Admin;

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
        $marketings = $this->marketingService->paginate(env('PAGINATE'));
        $customers = $this->customerService->all();
        $invoices = $this->invoiceService->all();
        $orders = $this->orderService->all();
        $receivings = $this->receivingService->all();
        $users = $this->userService->all();
        return view('admin.marketing.marketing', compact('marketings', 'customers', 'invoices', 'orders', 'receivings', 'users'));
    }

    public function all()
    {
        return $this->marketingService->all();
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

        $this->marketingService->create($request->all());

        return redirect()->back();
    }
    
    public function show($id)
    {
        if(array_key_exists('id', $_REQUEST)){
            return $this->marketingService->find($_REQUEST['id']);
        }
        return $this->marketingService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $marketing = ($this->show($id))['marketing'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
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


        // dd($id);
        $this->marketingService->update($request->all(), $id);

        if($request->identifier == 'rider'){
            return $this->getRiders($request);
        }

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->marketingService->delete($id);

        return redirect()->back();
    }

    public function search_marketings(Request $request)
    {
        $query = $request['query'];
        
        $marketings = $this->marketingService->search_marketings($query);
        $customers = $this->customerService->all();
        $invoices = $this->invoiceService->all();
        $orders = $this->orderService->all();
        $receivings = $this->receivingService->all();
        $users = $this->userService->all();

        return view('admin.marketing.marketing', compact('marketings', 'customers', 'invoices', 'orders', 'receivings', 'users'));
    }
}
