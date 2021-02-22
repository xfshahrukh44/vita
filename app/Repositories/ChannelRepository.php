<?php

namespace App\Repositories;

use App\Exceptions\Channel\AllChannelException;
use App\Exceptions\Channel\CreateChannelException;
use App\Exceptions\Channel\UpdateChannelException;
use App\Exceptions\Channel\DeleteChannelException;
use App\Models\Channel;

abstract class ChannelRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Channel $channel)
    {
        $this->model = $channel;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $channel = $this->model->create($data);
            
            return [
                'channel' => $this->find($channel->id)
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
                    'message' => 'Could`nt find channel',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'channel' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteChannelException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find channel',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'channel' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateChannelException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $channel = $this->model::with('vendors', 'customers')->find($id);
            if(!$channel)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find channel',
                ];
            }
            return [
                'success' => true,
                'channel' => $channel,
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
            throw new AllChannelException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllChannelException($exception->getMessage());
        }
    }
}
