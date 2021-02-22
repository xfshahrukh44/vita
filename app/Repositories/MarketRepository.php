<?php

namespace App\Repositories;

use App\Exceptions\Market\AllMarketException;
use App\Exceptions\Market\CreateMarketException;
use App\Exceptions\Market\UpdateMarketException;
use App\Exceptions\Market\DeleteMarketException;
use App\Models\Market;

abstract class MarketRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Market $market)
    {
        $this->model = $market;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $market = $this->model->create($data);
            
            return [
                'market' => $this->find($market->id)
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
                    'message' => 'Could`nt find market',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedMarket' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteMarketException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find market',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_market' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateMarketException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $market = $this->model::with('area', 'customers')->find($id);
            if(!$market)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find market',
                ];
            }
            return [
                'success' => true,
                'market' => $market,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('area', 'customers')->get();
        }
        catch (\Exception $exception) {
            throw new AllMarketException($exception->getMessage());
        }
    }

    public function fetch_specific_markets($area_id)
    {
        return $this->model::with('area', 'customers')->where('area_id', $area_id)->get();
    }
}
