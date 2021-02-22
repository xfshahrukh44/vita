<?php

namespace App\Repositories;

use App\Exceptions\SpecialDiscount\AllSpecialDiscountException;
use App\Exceptions\SpecialDiscount\CreateSpecialDiscountException;
use App\Exceptions\SpecialDiscount\UpdateSpecialDiscountException;
use App\Exceptions\SpecialDiscount\DeleteSpecialDiscountException;
use App\Models\SpecialDiscount;

abstract class SpecialDiscountRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(SpecialDiscount $specialDiscount)
    {
        $this->model = $specialDiscount;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $specialDiscount = $this->model->create($data);
            
            return [
                'specialDiscount' => $this->find($specialDiscount->id)
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
                    'message' => 'Could`nt find specialDiscount',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'specialDiscount' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteSpecialDiscountException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find specialDiscount',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'specialDiscount' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateSpecialDiscountException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $specialDiscount = $this->model::with('customer', 'product')->find($id);
            if(!$specialDiscount)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find specialDiscount',
                ];
            }
            return [
                'success' => true,
                'specialDiscount' => $specialDiscount,
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
            throw new AllSpecialDiscountException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllSpecialDiscountException($exception->getMessage());
        }
    }

    public function fetch_by_customer_and_product($data)
    {
        $special_discount = SpecialDiscount::where('customer_id', $data['customer_id'])->where('product_id', $data['product_id'])->latest()->first();
        if(!$special_discount){
            return response()->json([
                'success' => false,
                'message' => 'no special discounts were found'
            ]);
        }
        return response()->json([
            'success' => true,
            'special_discount' => $special_discount
        ]);
    }
}
