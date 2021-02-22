<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Invoice\AllInvoiceException;
use App\Exceptions\Invoice\CreateInvoiceException;
use App\Exceptions\Invoice\DeletedInvoiceException;
use App\Exceptions\Invoice\UpdateInvoiceException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Services\InvoiceProductService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    private $invoiceService;
    private $customerService;
    private $invoiceProductService;

    public function __construct(InvoiceService $invoiceService, CustomerService $customerService, InvoiceProductService $invoiceProductService)
    {
        $this->invoiceService = $invoiceService;
        $this->customerService = $customerService;
        $this->invoiceProductService = $invoiceProductService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->invoiceService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'order_id' => 'sometimes',
            'rider_id' => 'sometimes',
            'total' => 'sometimes',
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

        // create invoice
        $data = $this->invoiceService->create($request->all());

        // create invoice product
        if($request->invoiceProducts){
            foreach($request->invoiceProducts as $invoiceProduct){
                $this->invoiceProductService->create([
                    'invoice_id' => $data['invoice']['invoice']['id'],
                    'product_id' => $invoiceProduct['product_id'],
                    'quantity' => $invoiceProduct['quantity'],
                    'price' => $invoiceProduct['price']
                ]);
            }
        }

        return response()->json($this->invoiceService->find($data['invoice']['invoice']['id']));
    }
    
    public function show($id)
    {
        return response()->json($this->invoiceService->find($id));
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
            'order_id' => 'sometimes',
            'rider_id' => 'sometimes',
            'total' => 'sometimes',
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

        $data = $this->invoiceService->update($request->all(), $id);

        // create invoice product
        if($request->invoiceProducts){
            // delete old
            $invoice = $data['invoice']['invoice'];
            foreach($invoice->invoice_products as $invoice_product){
                $invoice_product->delete();
            }
            // create new
            foreach($request->invoiceProducts as $invoiceProduct){
                $this->invoiceProductService->create([
                    'invoice_id' => $invoice['id'],
                    'product_id' => $invoiceProduct['product_id'],
                    'quantity' => $invoiceProduct['quantity'],
                    'price' => $invoiceProduct['price']
                ]);
            }
        }

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->invoiceService->delete($id);
    }

    public function generate_invoice_pdf(Request $request)
    {
        return $this->invoiceService->generate_invoice_pdf($request->invoice_id);
    }
}