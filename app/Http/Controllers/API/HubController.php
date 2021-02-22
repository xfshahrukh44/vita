<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Hub\AllHubException;
use App\Exceptions\Hub\CreateHubException;
use App\Exceptions\Hub\DeletedHubException;
use App\Exceptions\Hub\UpdateHubException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\HubService;
use Illuminate\Support\Facades\Validator;

class HubController extends Controller
{
    private $hubService;

    public function __construct(HubService $hubService)
    {
        $this->hubService = $hubService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->hubService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->hubService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->hubService->find($id));
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

        $data = $this->hubService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->hubService->delete($id);
    }
}
