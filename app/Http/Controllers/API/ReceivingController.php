<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Receiving\AllReceivingException;
use App\Exceptions\Receiving\CreateReceivingException;
use App\Exceptions\Receiving\DeletedReceivingException;
use App\Exceptions\Receiving\UpdateReceivingException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReceivingService;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Validator;

class ReceivingController extends Controller
{
    private $receivingService;
    private $invoiceService;

    public function __construct(ReceivingService $receivingService, InvoiceService $invoiceService)
    {
        $this->receivingService = $receivingService;
        $this->invoiceService = $invoiceService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->receivingService->all());
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
        
        // check if invoice exists
        if( ($this->invoiceService->find($request->invoice_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.'
            ]);
        }

        $data = $this->receivingService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->receivingService->find($id));
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
            'customer_id' => 'sometimes',
            'payment_date' => 'sometimes',
            'amount' => 'sometimes'
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

        $data = $this->receivingService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->receivingService->delete($id);
    }
}
