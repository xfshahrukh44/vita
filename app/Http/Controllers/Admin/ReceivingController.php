<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReceivingService;
use App\Services\InvoiceService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;

class ReceivingController extends Controller
{
    private $receivingService;
    private $invoiceService;
    private $customerService;

    public function __construct(ReceivingService $receivingService, InvoiceService $invoiceService, CustomerService $customerService)
    {
        $this->receivingService = $receivingService;
        $this->invoiceService = $invoiceService;
        $this->customerService = $customerService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $receivings = $this->receivingService->paginate(env('PAGINATE'));
        $invoices = $this->invoiceService->all();
        $customers = $this->customerService->all();
        return view('admin.receiving.receiving', compact('receivings', 'invoices', 'customers'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'sometimes',
            'customer_id' => 'sometimes',
            'payment_date' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->receivingService->create($request->all());

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->receiving_id;
        return $this->receivingService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $request['receiving_id'] = $id;
        $receiving = ($this->show($request, $id))['receiving'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'invoice_id' => 'sometimes',
            'customer_id' => 'sometimes',
            'payment_date' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->receivingService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->receivingService->delete($id);

        return redirect()->back();
    }

    public function search_receivings(Request $request)
    {
        $query = $request['query'];
        
        $receivings = $this->receivingService->search_receivings($query);
        $invoices = $this->invoiceService->all();

        return view('admin.receiving.receiving', compact('receivings', 'invoices'));
    }
}
