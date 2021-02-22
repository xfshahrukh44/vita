<?php

namespace App\Repositories;

use App\Exceptions\Vendor\AllVendorException;
use App\Exceptions\Vendor\CreateVendorException;
use App\Exceptions\Vendor\UpdateVendorException;
use App\Exceptions\Vendor\DeleteVendorException;
use App\Models\Vendor;
use App\Models\Area;

abstract class VendorRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Vendor $vendor)
    {
        $this->model = $vendor;
    }
    
    public function create(array $data)
    {
        try 
        {
            $vendor = $this->model->create($data);
            
            return [
                'vendor' => $this->find($vendor->id)
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
                    'message' => 'Could`nt find vendor',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'vendor' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteVendorException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find vendor',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'vendor' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateVendorException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $vendor = $this->model::with('area', 'ledgers', 'payments', 'stock_ins', 'channel', 'hub', 'area')->find($id);
            if(!$vendor)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find vendor',
                ];
            }
            return [
                'success' => true,
                'vendor' => $vendor,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('area')->get();
        }
        catch (\Exception $exception) {
            throw new AllVendorException($exception->getMessage());
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

    public function search_vendors($query)
    {
        // foreign fields
        // areas
        $areas = Area::select('id')->where('name', 'LIKE', '%'.$query.'%')->get();
        $area_ids = [];
        foreach($areas as $area){
            array_push($area_ids, $area->id);
        }

        // search block
        $vendors = Vendor::where('name', 'LIKE', '%'.$query.'%')
                        ->orWhere('contact_number', 'LIKE', '%'.$query.'%')
                        ->orWhere('whatsapp_number', 'LIKE', '%'.$query.'%')
                        ->orWhere('type', 'LIKE', '%'.$query.'%')
                        ->orWhere('shop_name', 'LIKE', '%'.$query.'%')
                        ->orWhere('shop_number', 'LIKE', '%'.$query.'%')
                        ->orWhere('status', 'LIKE', '%'.$query.'%')
                        ->orWhereIn('area_id', $area_ids)
                        ->paginate(env('PAGINATION'));

        return $vendors;
    }
}
