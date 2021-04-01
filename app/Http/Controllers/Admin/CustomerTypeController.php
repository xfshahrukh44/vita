<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerTypeService;
use Illuminate\Support\Facades\Validator;
use Storage;

class CustomerTypeController extends Controller
{
    private $customerTypeService;

    public function __construct(CustomerTypeService $customerTypeService)
    {
        $this->customerTypeService = $customerTypeService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $customerTypes = $this->customerTypeService->paginate(env('PAGINATE'));
        return view('admin.customerType.customerType', compact('customerTypes'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->customerTypeService->create($request->all());

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->id;
        return $this->customerTypeService->find($id);
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

        $this->customerTypeService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->customerTypeService->delete($id);

        return redirect()->back();
    }
}
