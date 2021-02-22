<?php

namespace App\Repositories;

use App\Exceptions\Brand\AllBrandException;
use App\Exceptions\Brand\CreateBrandException;
use App\Exceptions\Brand\UpdateBrandException;
use App\Exceptions\Brand\DeleteBrandException;
use App\Models\Brand;

abstract class BrandRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Brand $brand)
    {
        $this->model = $brand;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $brand = $this->model->create($data);
            
            return [
                'brand' => $this->find($brand->id)
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
                    'message' => 'Could`nt find brand',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedBrand' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteBrandException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find brand',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_brand' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateBrandException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $brand = $this->model::with('products')->find($id);
            if(!$brand)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find brand',
                ];
            }
            return [
                'success' => true,
                'brand' => $brand,
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
            throw new AllBrandException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllBrandException($exception->getMessage());
        }
    }
}
