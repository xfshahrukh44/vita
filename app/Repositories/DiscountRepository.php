<?php

namespace App\Repositories;

use App\Exceptions\Discount\AllDiscountException;
use App\Exceptions\Discount\CreateDiscountException;
use App\Exceptions\Discount\UpdateDiscountException;
use App\Exceptions\Discount\DeleteDiscountException;
use App\Models\Discount;

abstract class DiscountRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Discount $discount)
    {
        $this->model = $discount;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $discount = $this->model->create($data);
            
            return [
                'discount' => $this->find($discount->id)
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
                    'message' => 'Could`nt find discount',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'discount' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteDiscountException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find discount',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'discount' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateDiscountException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $discount = $this->model::find($id);
            if(!$discount)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find discount',
                ];
            }
            return [
                'success' => true,
                'discount' => $discount,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::all();
        }
        catch (\Exception $exception) {
            throw new AllDiscountException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllDiscountException($exception->getMessage());
        }
    }
}
