<?php

namespace App\Repositories;

use App\Exceptions\PaymentMethod\AllPaymentMethodException;
use App\Exceptions\PaymentMethod\CreatePaymentMethodException;
use App\Exceptions\PaymentMethod\UpdatePaymentMethodException;
use App\Exceptions\PaymentMethod\DeletePaymentMethodException;
use App\Models\PaymentMethod;

abstract class PaymentMethodRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->model = $paymentMethod;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $paymentMethod = $this->model->create($data);
            
            return [
                'paymentMethod' => $this->find($paymentMethod->id)
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
                    'message' => 'Could`nt find paymentMethod',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedPaymentMethod' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeletePaymentMethodException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find paymentMethod',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_paymentMethod' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdatePaymentMethodException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $paymentMethod = $this->model::with('products')->find($id);
            if(!$paymentMethod)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find paymentMethod',
                ];
            }
            return [
                'success' => true,
                'paymentMethod' => $paymentMethod,
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
            throw new AllPaymentMethodException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllPaymentMethodException($exception->getMessage());
        }
    }
}
