<?php

namespace App\Repositories;

use App\Exceptions\Receiving\AllReceivingException;
use App\Exceptions\Receiving\CreateReceivingException;
use App\Exceptions\Receiving\UpdateReceivingException;
use App\Exceptions\Receiving\DeleteReceivingException;
use App\Models\Receiving;

abstract class ReceivingRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Receiving $receiving)
    {
        $this->model = $receiving;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $receiving = $this->model->create($data);
            
            return [
                'receiving' => $this->find($receiving->id)
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
                    'message' => 'Could`nt find receiving',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'receiving' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteReceivingException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find receiving',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'receiving' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateReceivingException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $receiving = $this->model::with('invoice.order.customer.market.area')->find($id);
            if(!$receiving)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find receiving',
                ];
            }
            return [
                'success' => true,
                'receiving' => $receiving,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('invoice')->get();
        }
        catch (\Exception $exception) {
            throw new AllReceivingException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllReceivingException($exception->getMessage());
        }
    }

    public function search_receivings($query)
    {
        // foreign fields

        // search block
        $receivings = Receiving::where('invoice_id', 'LIKE', '%'.$query.'%')
                        ->orWhere('amount', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $receivings;
    }
}
