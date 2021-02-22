<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Area\AllAreaException;
use App\Exceptions\Area\CreateAreaException;
use App\Exceptions\Area\DeletedAreaException;
use App\Exceptions\Area\UpdateAreaException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaService;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    private $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->areaService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->areaService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->areaService->find($id));
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

        $data = $this->areaService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->areaService->delete($id);
    }
}
