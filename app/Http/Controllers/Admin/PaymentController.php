<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('auth');
    }
    
    public function index()
    {
        $payments = $this->paymentService->paginate(env('PAGINATE'));
        $vendors = $this->vendorService->all();
        return view('admin.payment.payment', compact('payments', 'vendors'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->paymentService->create($request->all());

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->payment_id;
        return $this->paymentService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $payment = ($this->show($id))['payment'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'vendor_id' => 'sometimes',
            'amount' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->paymentService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->paymentService->delete($id);

        return redirect()->back();
    }

    public function search_payments(Request $request)
    {
        $query = $request['query'];
        
        $payments = $this->paymentService->search_payments($query);
        $vendors = $this->vendorService->all();

        return view('admin.payment.payment', compact('payments', 'vendors'));
    }
}
