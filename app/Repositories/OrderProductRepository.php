<?php

namespace App\Repositories;

use App\Exceptions\OrderProduct\AllOrderProductException;
use App\Exceptions\OrderProduct\CreateOrderProductException;
use App\Exceptions\OrderProduct\UpdateOrderProductException;
use App\Exceptions\OrderProduct\DeleteOrderProductException;
use App\Models\OrderProduct;

abstract class OrderProductRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(OrderProduct $orderProduct)
    {
        $this->model = $orderProduct;
    }
    
    public function create(array $data)
    {
        try 
        {
            $orderProduct = $this->model->create($data);
            
            return [
                'orderProduct' => $this->find($orderProduct->id)
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
                    'message' => 'Could`nt find orderProduct',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedOrderProduct' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteOrderProductException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find orderProduct',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_orderProduct' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateOrderProductException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $orderProduct = $this->model::with('product', 'order.customer.market.area')->find($id);
            if(!$orderProduct)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find orderProduct',
                ];
            }
            return [
                'success' => true,
                'orderProduct' => $orderProduct,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('product', 'order.customer.market.area')->get();
        }
        catch (\Exception $exception) {
            throw new AllOrderProductException($exception->getMessage());
        }
    }
}
