<?php

namespace App\Http\Controllers\API;

use App\Exceptions\User\AllUserException;
use App\Exceptions\User\CreateUserException;
use App\Exceptions\User\DeletedUserException;
use App\Exceptions\User\UpdateUserException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
        $this->middleware('cors');
    }
    
    public function index()
    {
        return $this->userService->all();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4|confirmed',
            'phone' => 'required|unique:users',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        if(!$request['email'])
            $request['email'] = NULL;

        $request['type'] = 'user';
        $data = $this->userService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return $this->userService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'password' => 'sometimes|string|min:4|confirmed',
            'phone' => 'unique:users,phone,'.$id,
            'email' => 'sometimes|email',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
                $request['email'] = NULL;
        
        if(auth()->user()->type != 'superadmin')
        {
            $request['type'] = 'customer';
        }

        $data = $this->userService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->userService->delete($id);
    }

    public function login(Request $request)
    {
        $data = $this->userService->login($request->only('phone', 'password'), 'api');
        $data = $data->getData();
        return response()->json($data);
    }

    public function logout()
    {
        return $this->userService->logout('api');
    }

    public function me()
    {
        return $this->userService->me();
    }
}
