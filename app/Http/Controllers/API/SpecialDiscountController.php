<?php

namespace App\Http\Controllers\API;

use App\Exceptions\SpecialDiscount\AllSpecialDiscountException;
use App\Exceptions\SpecialDiscount\CreateSpecialDiscountException;
use App\Exceptions\SpecialDiscount\DeletedSpecialDiscountException;
use App\Exceptions\SpecialDiscount\UpdateSpecialDiscountException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SpecialDiscountService;
use Illuminate\Support\Facades\Validator;

class SpecialDiscountController extends Controller
{
    private $specialDiscountService;

    public function __construct(SpecialDiscountService $specialDiscountService)
    {
        $this->specialDiscountService = $specialDiscountService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->specialDiscountService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->specialDiscountService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->specialDiscountService->find($id));
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
            'product_id' => 'sometimes',
            'amount' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->specialDiscountService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->specialDiscountService->delete($id);
    }
}
