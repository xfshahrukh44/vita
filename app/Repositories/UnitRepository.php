<?php

namespace App\Repositories;

use App\Exceptions\Unit\AllUnitException;
use App\Exceptions\Unit\CreateUnitException;
use App\Exceptions\Unit\UpdateUnitException;
use App\Exceptions\Unit\DeleteUnitException;
use App\Models\Unit;

abstract class UnitRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $unit = $this->model->create($data);
            
            return [
                'unit' => $this->find($unit->id)
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
                    'message' => 'Could`nt find unit',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedUnit' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteUnitException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find unit',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_unit' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateUnitException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $unit = $this->model::with('products')->find($id);
            if(!$unit)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find unit',
                ];
            }
            return [
                'success' => true,
                'unit' => $unit,
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
            throw new AllUnitException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllUnitException($exception->getMessage());
        }
    }
}
