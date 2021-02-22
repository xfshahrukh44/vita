<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\Services\MarketService;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    private $areaService;
    private $marketService;

    public function __construct(AreaService $areaService, MarketService $marketService)
    {
        $this->areaService = $areaService;
        $this->marketService = $marketService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $areas = $this->areaService->paginate(env('PAGINATE'));
        return view('admin.area.area', compact('areas'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // CREATE AREA
        $area = ($this->areaService->create($request->all()))['area']['area'];

        // check if markets' info provided
        if($request->market_names)
        {
            // create markets
            foreach($request->market_names as $market_name)
            {
                $this->marketService->create([
                    'name' => $market_name,
                    'area_id' => $area->id
                ]);
            }
        }

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->id;
        return $this->areaService->find($id);
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

        // UPDATE AREA
        $area = ($this->areaService->update($request->all(), $id))['updated_area']['area'];

        // delete all previous markets
        foreach($area->markets as $market)
        {
            $this->marketService->delete($market->id);
        }

        // check if markets' info provided
        if($request->market_names)
        {
            // create markets
            foreach($request->market_names as $market_name)
            {
                $this->marketService->create([
                    'name' => $market_name,
                    'area_id' => $area->id
                ]);
            }
        }

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->areaService->delete($id);

        return redirect()->back();
    }
}
