<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Product\AllProductException;
use App\Exceptions\Product\CreateProductException;
use App\Exceptions\Product\DeletedProductException;
use App\Exceptions\Product\UpdateProductException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\BrandService;
use App\Services\UnitService;
use Illuminate\Support\Facades\Validator;

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
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->productService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
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
            'sales_value' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        // check if category exists
        if( ($this->categoryService->find($request->category_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Category not found.'
            ]);
        }
        // check if brand exists
        if( ($this->brandService->find($request->brand_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Brand not found.'
            ]);
        }
        // check if unit exists
        if( ($this->unitService->find($request->unit_id))['success'] == false ){
            return response()->json([
                'success' => false,
                'message' => 'Unit not found.'
            ]);
        }

        $data = $this->productService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->productService->find($id));
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
            'category_id' => 'sometimes',
            'brand_id' => 'sometimes',
            'unit_id' => 'sometimes',
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
            'sales_value' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        // if category_id given
        if($request->category_id){
            // check if category exists
            if( ($this->categoryService->find($request->category_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found.'
                ]);
            }
        }

        // if brand_id given
        if($request->brand_id){
            // check if brand exists
            if( ($this->brandService->find($request->brand_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Brand not found.'
                ]);
            }
        }

        // if unit_id given
        if($request->unit_id){
            // check if unit exists
            if( ($this->unitService->find($request->unit_id))['success'] == false ){
                return response()->json([
                    'success' => false,
                    'message' => 'Unit not found.'
                ]);
            }
        }
        $data = $this->productService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->productService->delete($id);
    }
}
