<?php

namespace App\Repositories;

use App\Exceptions\CustomerType\AllCustomerTypeException;
use App\Exceptions\CustomerType\CreateCustomerTypeException;
use App\Exceptions\CustomerType\UpdateCustomerTypeException;
use App\Exceptions\CustomerType\DeleteCustomerTypeException;
use App\Models\CustomerType;

abstract class CustomerTypeRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(CustomerType $customerType)
    {
        $this->model = $customerType;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $customerType = $this->model->create($data);
            
            return [
                'customerType' => $this->find($customerType->id)
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
                    'message' => 'Could`nt find customerType',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedCustomerType' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteCustomerTypeException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find customerType',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_customerType' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateCustomerTypeException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $customerType = $this->model::with('products')->find($id);
            if(!$customerType)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find customerType',
                ];
            }
            return [
                'success' => true,
                'customerType' => $customerType,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('products')->get();
        }
        catch (\Exception $exception) {
            throw new AllCustomerTypeException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllCustomerTypeException($exception->getMessage());
        }
    }
}
