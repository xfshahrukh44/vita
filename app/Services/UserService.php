<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Hash;

class UserService extends UserRepository
{
    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    /**
     * @param $credentials
     * @param $guard
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($credentials, $guard = 'web')
    {
        $user = User::wherePhone($credentials['phone'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'success' => true,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard($guard)->factory()->getTTL() * 60,
                'user' => $user,
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Incorrect phone number or password.'
        ], 401);
    }

    /**
     * @param $token
     * @param $guard
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $guard)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard($guard)->factory()->getTTL() * 60,
            'user' => $this->guard($guard)->user(),
        ]);
    }

    public function logout($guard = 'web')
    {
        // dd($this->guard($guard)->user());
        if(!auth()->user())
        {
            return response()->json([
                'success' => false,
                'message' => 'Already logged out',
            ]);
        }
        
        $this->guard($guard)->logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
        
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
