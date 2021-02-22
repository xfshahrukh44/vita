<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvoiceProduct\AllInvoiceProductException;
use App\Exceptions\InvoiceProduct\CreateInvoiceProductException;
use App\Exceptions\InvoiceProduct\DeletedInvoiceProductException;
use App\Exceptions\InvoiceProduct\UpdateInvoiceProductException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InvoiceProductService;
use App\Services\ProductService;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Validator;

class InvoiceProductController extends Controller
{
    private $invoiceProductService;
    private $productService;
    private $invoiceService;

    public function __construct(InvoiceProductService $invoiceProductService, ProductService $productService, InvoiceService $invoiceService)
    {
        $this->invoiceProductService = $invoiceProductService;
        $this->productService = $productService;
        $this->invoiceService = $invoiceService;
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json($this->invoiceProductService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'sometimes',
            'price' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // check if invoice exists
        if( ($this->invoiceService->find($request->invoice_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.'
            ]);
        }

        // check if product exists
        if( ($this->productService->find($request->product_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Product not found.'
            ]);
        }

        $data = $this->invoiceProductService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->invoiceProductService->find($id));
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
            'invoice_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'sometimes',
            'price' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // if invoice_id given
        if($request->invoice_id){
            // check if invoice exists
            if( ($this->invoiceService->find($request->invoice_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found.'
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

        $data = $this->invoiceProductService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->invoiceProductService->delete($id);
    }
}
