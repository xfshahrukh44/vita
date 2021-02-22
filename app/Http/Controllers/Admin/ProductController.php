<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\BrandService;
use App\Services\UnitService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Storage;

class ProductController extends Controller
{
    private $productService;
    private $categoryService;
    private $brandService;
    private $unitService;

    public function __construct(ProductService $productService, CategoryService $categoryService, BrandService $brandService, UnitService $unitService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->brandService = $brandService;
        $this->unitService = $unitService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $products = $this->productService->paginate(env('PAGINATE'));
        $categories = $this->categoryService->all();
        $brands = $this->brandService->all();
        $units = $this->unitService->all();
        return view('admin.product.product', compact('products', 'categories', 'brands', 'units'));
    }

    public function all()
    {
        return $this->productService->all();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|int',
            'brand_id' => 'required|int',
            'unit_id' => 'required|int',
            'article' => 'required',
            'gender' => 'sometimes',
            'purchase_price' => 'sometimes',
            'consumer_selling_price' => 'sometimes',
            'retailer_selling_price' => 'sometimes',
            'opening_quantity' => 'sometimes',
            'moq' => 'sometimes',
            'quantity_in_hand' => 'sometimes',
            'product_picture' => 'sometimes',
            'cost_value' => 'sometimes',
            'sales_value' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // image work
        $req = Arr::except($request->all(),['product_picture']);
        // product_picture
        if($request->product_picture){
            $image = $request->product_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_products')->put($imageName, \File::get($image));
            $req['product_picture'] = $imageName;
        }

        $this->productService->create($req);

        return redirect()->back();
    }
    
    public function show($id)
    {
        if(array_key_exists('id', $_REQUEST)){
            return $this->productService->find($_REQUEST['id']);
        }
        return $this->productService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $product = ($this->show($id))['product'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|int',
            'brand_id' => 'sometimes|int',
            'unit_id' => 'sometimes|int',
            'article' => 'sometimes',
            'gender' => 'sometimes',
            'purchase_price' => 'sometimes',
            'consumer_selling_price' => 'sometimes',
            'retailer_selling_price' => 'sometimes',
            'opening_quantity' => 'sometimes',
            'moq' => 'sometimes',
            'quantity_in_hand' => 'sometimes',
            'product_picture' => 'sometimes',
            'cost_value' => 'sometimes',
            'sales_value' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        
        // image work
        $req = Arr::except($request->all(),['shop_picture', 'shop_keeper_picture']);

        // product_picture
        if($request->product_picture){
            Storage::disk('public_products')->delete($product->product_picture);
            $image = $request->product_picture;
            $imageName = Str::random(10).'.png';
            Storage::disk('public_products')->put($imageName, \File::get($image));
            $req['product_picture'] = $imageName;
        }

        // dd($id);
        $this->productService->update($req, $id);

        if($request->identifier == 'rider'){
            return $this->getRiders($request);
        }

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->productService->delete($id);

        return redirect()->back();
    }

    public function search_products(Request $request)
    {
        $query = $request['query'];
        
        $products = $this->productService->search_products($query);
        $categories = $this->categoryService->all();
        $brands = $this->brandService->all();
        $units = $this->unitService->all();

        return view('admin.product.product', compact('products', 'categories', 'brands', 'units'));
    }

    public function create_category(Request $request)
    {
        return $this->productService->create_category($request->all());
    }
    
    public function create_brand(Request $request)
    {
        return $this->productService->create_brand($request->all());
        
    }
    
    public function create_unit(Request $request)
    {
        return $this->productService->create_unit($request->all());
        
    }

    public function fetch_product_labels()
    {
        $products = $this->productService->all();
        $new_products = [];
        foreach($products as $product){
            array_push($new_products, [
                'label' => $product->category->name . ' - ' . $product->brand->name . ' - ' . $product->article,
                'value' => $product->id,
                'consumer_selling_price' => $product->consumer_selling_price ? $product->consumer_selling_price : 0,
                'retailer_selling_price' => $product->retailer_selling_price ? $product->retailer_selling_price : 0,
            ]);
        }
        return $new_products;
    }
}
