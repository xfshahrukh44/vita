<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Brand\AllBrandException;
use App\Exceptions\Brand\CreateBrandException;
use App\Exceptions\Brand\DeletedBrandException;
use App\Exceptions\Brand\UpdateBrandException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BrandService;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->brandService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->brandService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->brandService->find($id));
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
            'name' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->brandService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->brandService->delete($id);
    }
}
