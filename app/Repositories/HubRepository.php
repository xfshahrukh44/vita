<?php

namespace App\Repositories;

use App\Exceptions\Hub\AllHubException;
use App\Exceptions\Hub\CreateHubException;
use App\Exceptions\Hub\UpdateHubException;
use App\Exceptions\Hub\DeleteHubException;
use App\Models\Hub;

abstract class HubRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Hub $hub)
    {
        $this->model = $hub;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $hub = $this->model->create($data);
            
            return [
                'hub' => $this->find($hub->id)
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
                    'message' => 'Could`nt find hub',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'hub' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteHubException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find hub',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'hub' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateHubException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $hub = $this->model::with('vendors', 'customers')->find($id);
            if(!$hub)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find hub',
                ];
            }
            return [
                'success' => true,
                'hub' => $hub,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::all();
        }
        catch (\Exception $exception) {
            throw new AllHubException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllHubException($exception->getMessage());
        }
    }
}
