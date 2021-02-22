<?php

namespace App\Repositories;

use App\Exceptions\Marketing\AllMarketingException;
use App\Exceptions\Marketing\CreateMarketingException;
use App\Exceptions\Marketing\UpdateMarketingException;
use App\Exceptions\Marketing\DeleteMarketingException;
use App\Models\Marketing;
use App\Models\Market;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Receiving;
use App\User;

abstract class MarketingRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Marketing $marketing)
    {
        $this->model = $marketing;
    }
    
    public function create(array $data)
    {
        try 
        {
            $marketing = $this->model->create($data);
            
            return [
                'marketing' => $this->find($marketing->id)
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
                    'message' => 'Could`nt find marketing',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'marketing' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteMarketingException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find marketing',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'marketing' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateMarketingException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $marketing = $this->model::with('customer', 'invoice', 'order', 'receiving', 'rider')->find($id);
            if(!$marketing)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find marketing',
                ];
            }
            return [
                'success' => true,
                'marketing' => $marketing,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('customer', 'invoice', 'order', 'receiving', 'rider')->get();
        }
        catch (\Exception $exception) {
            throw new AllMarketingException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }

    public function search_marketings($query)
    {
        // foreign fields
        // customers
        $customers = Customer::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $customer_ids = [];
        foreach($customers as $customer){
            array_push($customer_ids, $customer->id);
        }
        // invoices
        $invoices = Invoice::select('id')->where('id', 'LIKE', '%'.$query.'%')->get();
        $invoice_ids = [];
        foreach($invoices as $invoice){
            array_push($invoice_ids, $invoice->id);
        }
        // orders
        $orders = Order::select('id')->where('id', 'LIKE', '%'.$query.'%')->get();
        $order_ids = [];
        foreach($orders as $order){
            array_push($order_ids, $order->id);
        }
        // receivings
        $receivings = Receiving::select('id')->where('id', 'LIKE', '%'.$query.'%')->get();
        $receiving_ids = [];
        foreach($receivings as $receiving){
            array_push($receiving_ids, $receiving->id);
        }
        // users
        $users = User::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $user_ids = [];
        foreach($users as $user){
            array_push($user_ids, $user->id);
        }

        // search block
        $marketings = Marketing::whereIn('customer_id', $customer_ids)
                        ->orWhereIn('invoice_id', $invoice_ids)
                        ->orWhereIn('order_id', $order_ids)
                        ->orWhereIn('receiving_id', $receiving_ids)
                        ->orWhereIn('user_id', $user_ids)
                        ->paginate(env('PAGINATION'));

        return $marketings;
    }
}
