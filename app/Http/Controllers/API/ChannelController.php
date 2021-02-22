<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Channel\AllChannelException;
use App\Exceptions\Channel\CreateChannelException;
use App\Exceptions\Channel\DeletedChannelException;
use App\Exceptions\Channel\UpdateChannelException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChannelService;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    private $channelService;

    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->channelService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->channelService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->channelService->find($id));
    }
    
    public function update(Request $request, $id)
    {
        if(auth()->user()->type != "superadmin")
        {
            return response()->json([
                'success' => false,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->channelService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->channelService->delete($id);
    }
}
