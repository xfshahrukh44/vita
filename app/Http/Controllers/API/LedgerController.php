<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Ledger\AllLedgerException;
use App\Exceptions\Ledger\CreateLedgerException;
use App\Exceptions\Ledger\DeletedLedgerException;
use App\Exceptions\Ledger\UpdateLedgerException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LedgerService;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    private $ledgerService;
    private $customerService;

    public function __construct(LedgerService $ledgerService, CustomerService $customerService)
    {
        $this->ledgerService = $ledgerService;
        $this->customerService = $customerService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->ledgerService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'vendor_id' => 'sometimes',
            'invoice_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'payment_id' => 'sometimes',
            'amount' => 'required',
            'type' => 'required',
            'transaction_date' => 'sometimes',
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

        $data = $this->ledgerService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->ledgerService->find($id));
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
            'vendor_id' => 'sometimes',
            'invoice_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'payment_id' => 'sometimes',
            'amount' => 'sometimes',
            'type' => 'sometimes',
            'transaction_date' => 'sometimes',
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

        $data = $this->ledgerService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->ledgerService->delete($id);
    }
}
