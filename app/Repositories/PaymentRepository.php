<?php

namespace App\Repositories;

use App\Exceptions\Payment\AllPaymentException;
use App\Exceptions\Payment\CreatePaymentException;
use App\Exceptions\Payment\UpdatePaymentException;
use App\Exceptions\Payment\DeletePaymentException;
use App\Models\Payment;

abstract class PaymentRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $payment = $this->model->create($data);
            
            return [
                'payment' => $this->find($payment->id)
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
                    'message' => 'Could`nt find payment',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'payment' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeletePaymentException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find payment',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'payment' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdatePaymentException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $payment = $this->model::with('vendor')->find($id);
            if(!$payment)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find payment',
                ];
            }
            return [
                'success' => true,
                'payment' => $payment,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('vendor')->get();
        }
        catch (\Exception $exception) {
            throw new AllPaymentException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllPaymentException($exception->getMessage());
        }
    }

    public function search_payments($query)
    {
        // foreign fields
        // vendors
        $vendors = Vendor::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $vendor_ids = [];
        foreach($vendors as $vendor){
            array_push($vendor_ids, $vendor->id);
        }

        // search block
        $payments = Payment::whereIn('vendor_id', $vendor_ids)
                        ->orWhere('amount', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $payments;
    }
}
