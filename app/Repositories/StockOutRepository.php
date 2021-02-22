<?php

namespace App\Repositories;

use App\Exceptions\StockOut\AllStockOutException;
use App\Exceptions\StockOut\CreateStockOutException;
use App\Exceptions\StockOut\UpdateStockOutException;
use App\Exceptions\StockOut\DeleteStockOutException;
use App\Models\StockOut;
use App\Models\Customer;
use App\Models\Product;

abstract class StockOutRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(StockOut $stockOut)
    {
        $this->model = $stockOut;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $stockOut = $this->model->create($data);
            
            return [
                'stockOut' => $this->find($stockOut->id)
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
                    'message' => 'Could`nt find stockOut',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedStockOut' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteStockOutException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find stockOut',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_stockOut' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateStockOutException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $stockOut = $this->model::with('customer', 'product')->find($id);
            if(!$stockOut)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find stockOut',
                ];
            }
            return [
                'success' => true,
                'stockOut' => $stockOut,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('customer', 'product')->get();
        }
        catch (\Exception $exception) {
            throw new AllStockOutException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllStockOutException($exception->getMessage());
        }
    }

    public function search_stockOuts($query)
    {
        // foreign fields
        // customers
        $customers = Customer::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $customer_ids = [];
        foreach($customers as $customer){
            array_push($customer_ids, $customer->id);
        }

        // products
        $products = Product::select('id')->where('article', 'LIKE', '%'.$query.'%')->get();
        $product_ids = [];
        foreach($products as $product){
            array_push($product_ids, $product->id);
        }

        // search block
        $stockOuts = StockOut::whereIn('customer_id', $customer_ids)
                        ->orWhereIn('product_id', $product_ids)
                        ->orWhere('quantity', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $stockOuts;
    }
}
