<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Market\AllMarketException;
use App\Exceptions\Market\CreateMarketException;
use App\Exceptions\Market\DeletedMarketException;
use App\Exceptions\Market\UpdateMarketException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MarketService;
use App\Services\AreaService;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    private $marketService;
    private $areaService;

    public function __construct(MarketService $marketService, AreaService $areaService)
    {
        $this->marketService = $marketService;
        $this->areaService = $areaService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->marketService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'area_id' => 'required|int'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // check if area exists
        if( ($this->areaService->find($request->area_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Area not found.'
            ]);
        }

        $data = $this->marketService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->marketService->find($id));
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
            'name' => 'sometimes',
            'area_id' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // if area_id given
        if($request->area_id){
            // check if area exists
            if( ($this->areaService->find($request->area_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Area not found.'
                ]);
            }
        }

        $data = $this->marketService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->marketService->delete($id);
    }

    public function fetch_specific_markets(Request $request)
    {
        return $this->marketService->fetch_specific_markets($request->area_id);
    }
}
