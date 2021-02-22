<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Services\AreaService;
use App\Services\MarketService;
use App\Services\ChannelService;
use App\Services\HubService;
use App\Services\ProductService;
use App\Services\SpecialDiscountService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Storage;

class CustomerController extends Controller
{
    private $customerService;
    private $areaService;
    private $marketService;
    private $channelService;
    private $hubService;
    private $productService;
    private $specialDiscountService;

    public function __construct(CustomerService $customerService, AreaService $areaService, MarketService $marketService, ProductService $productService, SpecialDiscountService $specialDiscountService, ChannelService $channelService, HubService $hubService)
    {
        $this->customerService = $customerService;
        $this->areaService = $areaService;
        $this->marketService = $marketService;
        $this->channelService = $channelService;
        $this->hubService = $hubService;
        $this->productService = $productService;
        $this->specialDiscountService = $specialDiscountService;
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = $this->customerService->paginate(env('PAGINATE'));
        $areas = $this->areaService->all();
        $markets = $this->marketService->all();
        $channels = $this->channelService->all();
        $hubs = $this->hubService->all();
        $products = $this->productService->all();
        return view('admin.customer.customer', compact('customers', 'areas', 'markets', 'channels', 'hubs', 'products'));
    }

    public function all()
    {
        return $this->customerService->all();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'area_id' => 'sometimes|int',
            'market_id' => 'sometimes|int',
            'channel_id' => 'sometimes|int',
            'hub_id' => 'sometimes|int',
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

        // image work
        $req = Arr::except($request->all(),['shop_picture', 'shop_keeper_picture']);
        // shop_picture
        if($request->shop_picture){
            $image = $request->shop_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_shops')->put($imageName, \File::get($image));
            $req['shop_picture'] = $imageName;
        }
        
        // shop_keeper_picture
        if($request->shop_keeper_picture){
            $image = $request->shop_keeper_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_shopkeepers')->put($imageName, \File::get($image));
            $req['shop_keeper_picture'] = $imageName;
        }

        $customer = ($this->customerService->create($req))['customer']['customer'];

        // special discount work
        if($request->products){
            // create special discounts
            for($i = 0; $i < count($request->products); $i++){
                // here 
                $this->specialDiscountService->create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->products[$i],
                    'amount' => $request->amounts[$i]
                ]);
            }
        }

        return redirect()->back();
    }
    
    public function show($id)
    {
        if(array_key_exists('id', $_REQUEST)){
            return $this->customerService->find($_REQUEST['id']);
        }
        return $this->customerService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $customer = ($this->show($id))['customer'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'area_id' => 'sometimes|int',
            'market_id' => 'sometimes|int',
            'channel_id' => 'sometimes|int',
            'hub_id' => 'sometimes|int',
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

        
        // image work
        $req = Arr::except($request->all(),['shop_picture', 'shop_keeper_picture']);

        // shop_picture
        if($request->shop_picture){
            Storage::disk('shops')->delete($customer->shop_picture);
            $image = $request->shop_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_shops')->put($imageName, \File::get($image));
            $req['shop_picture'] = $imageName;
        }
        
        // shop_keeper_picture
        if($request->shop_keeper_picture){
            Storage::disk('shopkeepers')->delete($customer->shop_keeper_picture);
            $image = $request->shop_keeper_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_shopkeepers')->put($imageName, \File::get($image));
            $req['shop_keeper_picture'] = $imageName;
        }

        $customer = ($this->customerService->update($req, $id))['customer']['customer'];

        // special discount work
        if($request->products){
            // delete old special discounts
            foreach($customer->special_discounts as $special_discount){
                $special_discount->delete();
            }
            // create new ones
            for($i = 0; $i < count($request->products); $i++){
                // here 
                $this->specialDiscountService->create([
                    'customer_id' => $customer->id,
                    'product_id' => $request->products[$i],
                    'amount' => $request->amounts[$i]
                ]);
            }
        }

        if($request->identifier == 'rider'){
            return $this->getRiders($request);
        }
        
        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->customerService->delete($id);

        return redirect()->back();
    }

    public function search_customers(Request $request)
    {
        $query = $request['query'];
        
        $customers = $this->customerService->search_customers($query);
        $areas = $this->areaService->all();
        $markets = $this->marketService->all();
        $products = $this->productService->all();

        return view('admin.customer.customer', compact('customers', 'areas', 'markets', 'products'));
    }

    public function fetch_customer_labels()
    {
        $customers = $this->customerService->all();
        $new_customers = [];
        foreach($customers as $customer){
            array_push($new_customers, [
                'label' => $customer->name,
                'value' => $customer->id
            ]);
        }

        return $new_customers;
    }
}
