<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentMethodService;
use Illuminate\Support\Facades\Validator;
use Storage;

class PaymentMethodController extends Controller
{
    private $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $paymentMethods = $this->paymentMethodService->paginate(env('PAGINATE'));
        return view('admin.paymentMethod.paymentMethod', compact('paymentMethods'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->paymentMethodService->create($request->all());

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->id;
        return $this->paymentMethodService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->paymentMethodService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->paymentMethodService->delete($id);

        return redirect()->back();
    }
}
