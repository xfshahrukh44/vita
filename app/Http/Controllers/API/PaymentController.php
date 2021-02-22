<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Payment\AllPaymentException;
use App\Exceptions\Payment\CreatePaymentException;
use App\Exceptions\Payment\DeletedPaymentException;
use App\Exceptions\Payment\UpdatePaymentException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\VendorService;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    private $paymentService;
    private $vendorService;

    public function __construct(PaymentService $paymentService, VendorService $vendorService)
    {
        $this->paymentService = $paymentService;
        $this->vendorService = $vendorService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->paymentService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // check if vendor exists
        if( ($this->vendorService->find($request->vendor_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found.'
            ]);
        }

        $data = $this->paymentService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->paymentService->find($id));
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
            'vendor_id' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // if vendor_id given
        if($request->vendor_id){
            // check if vendor exists
            if( ($this->vendorService->find($request->vendor_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found.'
                ]);
            }
        }

        $data = $this->paymentService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->paymentService->delete($id);
    }
}
