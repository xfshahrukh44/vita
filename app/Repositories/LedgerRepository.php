<?php

namespace App\Repositories;

use App\Exceptions\Ledger\AllLedgerException;
use App\Exceptions\Ledger\CreateLedgerException;
use App\Exceptions\Ledger\UpdateLedgerException;
use App\Exceptions\Ledger\DeleteLedgerException;
use App\Models\Ledger;
use App\Models\Customer;
use App\Models\Vendor;

abstract class LedgerRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Ledger $ledger)
    {
        $this->model = $ledger;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $ledger = $this->model->create($data);
            
            return [
                'ledger' => $this->find($ledger->id)
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
                    'message' => 'Could`nt find ledger',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedLedger' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteLedgerException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find ledger',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_ledger' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateLedgerException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $ledger = $this->model::with('customer')->find($id);
            if(!$ledger)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find ledger',
                ];
            }
            return [
                'success' => true,
                'ledger' => $ledger,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('customer')->get();
        }
        catch (\Exception $exception) {
            throw new AllLedgerException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllLedgerException($exception->getMessage());
        }
    }

    public function paginate_customer_ledgers($pagination)
    {
        try {
            return Customer::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllLedgerException($exception->getMessage());
        }
    }

    public function paginate_vendor_ledgers($pagination)
    {
        try {
            return Vendor::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllLedgerException($exception->getMessage());
        }
    }

    public function search_ledgers($query)
    {
        // foreign fields
        // customers
        $customers = Customer::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $customer_ids = [];
        foreach($customers as $customer){
            array_push($customer_ids, $customer->id);
        }

        // search block
        $ledgers = Ledger::whereIn('customer_id', $customer_ids)
                        ->orWhere('amount', 'LIKE', '%'.$query.'%')
                        ->orWhere('type', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $ledgers;
    }
}
