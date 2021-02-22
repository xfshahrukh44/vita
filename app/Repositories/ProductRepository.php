<?php

namespace App\Repositories;

use App\Exceptions\Product\AllProductException;
use App\Exceptions\Product\CreateProductException;
use App\Exceptions\Product\UpdateProductException;
use App\Exceptions\Product\DeleteProductException;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;

abstract class ProductRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Product $product)
    {
        $this->model = $product;
    }
    
    public function create(array $data)
    {
        try 
        {
            $product = $this->model->create($data);
            
            return [
                'product' => $this->find($product->id)
            ];
        }
        catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
    
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find product',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedProduct' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteProductException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find product',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_product' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateProductException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $product = $this->model::with('category', 'brand', 'unit', 'special_discounts')->find($id);
            if(!$product)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find product',
                ];
            }
            return [
                'success' => true,
                'product' => $product,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('category', 'brand', 'unit')->get();
        }
        catch (\Exception $exception) {
            throw new AllProductException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllProductException($exception->getMessage());
        }
    }

    public function search_products($query)
    {
        // foreign fields
        // categories
        $categories = Category::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $category_ids = [];
        foreach($categories as $category){
            array_push($category_ids, $category->id);
        }
        // brands
        $brands = Brand::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $brand_ids = [];
        foreach($brands as $brand){
            array_push($brand_ids, $brand->id);
        }
        // units
        $units = Unit::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $unit_ids = [];
        foreach($units as $unit){
            array_push($unit_ids, $unit->id);
        }

        // search block
        $products = Product::where('article', 'LIKE', '%'.$query.'%')
                        ->orWhereIn('category_id', $category_ids)
                        ->orWhereIn('brand_id', $brand_ids)
                        ->orWhereIn('unit_id', $unit_ids)
                        ->orWhere('purchase_price', 'LIKE', '%'.$query.'%')
                        ->orWhere('gender', 'LIKE', '%'.$query.'%')
                        ->orWhere('consumer_selling_price', 'LIKE', '%'.$query.'%')
                        ->orWhere('retailer_selling_price', 'LIKE', '%'.$query.'%')
                        ->orWhere('opening_quantity', 'LIKE', '%'.$query.'%')
                        ->orWhere('moq', 'LIKE', '%'.$query.'%')
                        ->orWhere('quantity_in_hand', 'LIKE', '%'.$query.'%')
                        ->orWhere('cost_value', 'LIKE', '%'.$query.'%')
                        ->orWhere('sales_value', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $products;
    }

    public function create_category(array $data)
    {
        return Category::create([
            'name' => $data['name']
        ]);
    }

    public function create_brand(array $data)
    {
        return Brand::create([
            'name' => $data['name']
        ]);
    }
    
    public function create_unit(array $data)
    {
        return Unit::create([
            'name' => $data['name']
        ]);
    }
    
}
