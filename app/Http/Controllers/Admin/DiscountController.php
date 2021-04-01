<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\DiscountService;

class DiscountController extends Controller
{
    private $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
        $this->middleware('auth');
    }

    public function index()
    {
        $discounts = $this->discountService->paginate(env('PAGINATE'));
        return view('admin.discount.discount', compact('discounts'));
    }

    public function all()
    {
        return $this->discountService->all();
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'sometimes',
            'percentage' => 'sometimes',
            'created_by' => 'sometimes',
            'modified_by' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->discountService->create($request->all());

        return redirect()->back();
    }

    
    public function show($id)
    {
        if(array_key_exists('id', $_REQUEST)){
            return $this->discountService->find($_REQUEST['id']);
        }
        return $this->discountService->find($id);
    }

    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $discount = ($this->show($id))['discount'];

        $validator = Validator::make($request->all(), [
            'level' => 'sometimes',
            'percentage' => 'sometimes',
            'created_by' => 'sometimes',
            'modified_by' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->discountService->update($req, $id);

        return redirect()->back();
    }

    
    public function destroy($id)
    {
        $id = $request->hidden;

        $this->discountService->delete($id);

        return redirect()->back();
    }
}
