<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Customer\AllCustomerException;
use App\Exceptions\Customer\CreateCustomerException;
use App\Exceptions\Customer\DeletedCustomerException;
use App\Exceptions\Customer\UpdateCustomerException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Services\MarketService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

class CustomerController extends Controller
{
    private $customerService;
    private $marketService;

    public function __construct(CustomerService $customerService, MarketService $marketService)
    {
        $this->customerService = $customerService;
        $this->marketService = $marketService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->customerService->all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'area_id' => 'sometimes|int',
            'market_id' => 'sometimes|int',
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

        $data = $this->customerService->create($request->all());

        return response()->json($data);
    }

    public function show($id)
    {
        return response()->json($this->customerService->find($id));
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

        if($this->customerService->find($id)['success'] == false){
            return ['message' => 'Could`nt find customer'];
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'area_id' => 'sometimes|int',
            'market_id' => 'sometimes|int',
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
            Storage::disk('public_shops')->delete(($this->customerService->find($id))['customer']['shop_picture']);
            $image = explode(',', $request->shop_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shops')->put($imageName, base64_decode($image));
            $request['shop_picture'] = $imageName;
        }
        // shop_keeper_picture
        if(($request->shop_keeper_picture)){
            Storage::disk('public_shopkeepers')->delete(($this->customerService->find($id))['customer']['shop_keeper_picture']);
            $image = explode(',', $request->shop_keeper_picture)[1];
            $imageName = Str::random(10).'.'.'png';
            Storage::disk('public_shopkeepers')->put($imageName, base64_decode($image));
            $request['shop_keeper_picture'] = $imageName;
        }

        $data = $this->customerService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->customerService->delete($id);
    }
}
