<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Unit\AllUnitException;
use App\Exceptions\Unit\CreateUnitException;
use App\Exceptions\Unit\DeletedUnitException;
use App\Exceptions\Unit\UpdateUnitException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UnitService;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->unitService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->unitService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->unitService->find($id));
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

        $data = $this->unitService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->unitService->delete($id);
    }
}
