<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Vendor\AllVendorException;
use App\Exceptions\Vendor\CreateVendorException;
use App\Exceptions\Vendor\DeletedVendorException;
use App\Exceptions\Vendor\UpdateVendorException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VendorService;
use App\Services\MarketService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

class VendorController extends Controller
{
    private $vendorService;
    private $marketService;

    public function __construct(VendorService $vendorService, MarketService $marketService)
    {
        $this->vendorService = $vendorService;
        $this->marketService = $marketService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->vendorService->all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'area_id' => 'sometimes',
            'address' => 'sometimes',
            'business_to_date' => 'sometimes',
            'outstanding_balance' => 'sometimes',
            'contact_number' => 'sometimes',
            'whatsapp_number' => 'sometimes',
            'type' => 'sometimes',
            'floor' => 'sometimes',
            'shop_name' => 'sometimes',
            'shop_number' => 'sometimes',
            'shop_picture' => 'sometimes',
            'shop_keeper_picture' => 'sometimes',
            'payment_terms' => 'sometimes',
            'cash_on_delivery' => 'sometimes',
            'visiting_days' => 'sometimes',
            'status' => 'sometimes',
            'opening_balance' => 'sometimes',
            'special_discount' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // check if market exists
        if( ($this->marketService->find($request->market_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Market not found.'
            ]);
        }

        // shop_picture
        if(($request->shop_picture != NULL)){
            $image = explode(',', $request->shop_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shops')->put($imageName, base64_decode($image));
            $request['shop_picture'] = $imageName;
        }
        // shop_keeper_picture
        if(($request->shop_keeper_picture != NULL)){
            $image = explode(',', $request->shop_keeper_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shopkeepers')->put($imageName, base64_decode($image));
            $request['shop_keeper_picture'] = $imageName;
        }

        $data = $this->vendorService->create($request->all());

        return response()->json($data);
    }

    public function show($id)
    {
        return response()->json($this->vendorService->find($id));
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

        if($this->vendorService->find($id)['success'] == false){
            return ['message' => 'Could`nt find vendor'];
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes',
            'area_id' => 'sometimes',
            'address' => 'sometimes',
            'business_to_date' => 'sometimes',
            'outstanding_balance' => 'sometimes',
            'contact_number' => 'sometimes',
            'whatsapp_number' => 'sometimes',
            'type' => 'sometimes',
            'floor' => 'sometimes',
            'shop_name' => 'sometimes',
            'shop_number' => 'sometimes',
            'shop_picture' => 'sometimes',
            'shop_keeper_picture' => 'sometimes',
            'payment_terms' => 'sometimes',
            'cash_on_delivery' => 'sometimes',
            'visiting_days' => 'sometimes',
            'status' => 'sometimes',
            'opening_balance' => 'sometimes',
            'special_discount' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // if market_id given
        if($request->market_id){
            // check if market exists
            if( ($this->marketService->find($request->market_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Market not found.'
                ]);
            }
        }
        
        // shop_picture
        if(($request->shop_picture)){
            Storage::disk('public_shops')->delete(($this->vendorService->find($id))['vendor']['shop_picture']);
            $image = explode(',', $request->shop_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shops')->put($imageName, base64_decode($image));
            $request['shop_picture'] = $imageName;
        }
        // shop_keeper_picture
        if(($request->shop_keeper_picture)){
            Storage::disk('public_shopkeepers')->delete(($this->vendorService->find($id))['vendor']['shop_keeper_picture']);
            $image = explode(',', $request->shop_keeper_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shopkeepers')->put($imageName, base64_decode($image));
            $request['shop_keeper_picture'] = $imageName;
        }

        $data = $this->vendorService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->vendorService->delete($id);
    }
}
