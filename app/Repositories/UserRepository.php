<?php

namespace App\Repositories;

use App\Exceptions\User\AllUserException;
use App\Exceptions\User\CreateUserException;
use App\Exceptions\User\UpdateUserException;
use App\Exceptions\User\DeleteUserException;
use App\User;
use Illuminate\Support\Facades\DB;
use Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

abstract class UserRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(User $user)
    {
        $this->model = $user;
    }
    
    public function create(array $data)
    {
        try {
            // password hashing
            if($data['password'])
            {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->model->create($data);

            $token = JWTAuth::fromUser($user);
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Could`nt find user',
                ]);
            }

            // make phone null before deleting snippet
            $user = ($this->find($id))['user'];
            $user->phone = NULL;
            $user->save();

            $this->model->destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedUser' => $temp,
            ]);
        }
        catch (\Exception $exception) {
            throw new DeleteUserException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            // password hashing
            if($data['password'])
            {
                $data['password'] = Hash::make($data['password']);
            }
            
            if(!$temp = $this->model->find($id))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Could`nt find user',
                ]);
            }

            $temp->update($data);
            $temp->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_user' => $temp,
            ]);
        }
        catch (\Exception $exception) {
            throw new UpdateUserException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try {
            // return $this->model::findOrFail($id);
            $user = $this->model::find($id);
            if(!$user)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find user',
                ];
            }
            return [
                'success' => true,
                'user' => $user,
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
            throw new AllUserException($exception->getMessage());
        }
    }

    public function paginate_staff($pagination)
    {
        try {
            return $this->model::where('type', '!=', 'rider')->orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }

    public function paginate_riders($pagination)
    {
        try {
            return $this->model::where('type', '=', 'rider')->orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }

    public function all_staff()
    {
        try {
            return $this->model::where('type', '!=', 'rider')->get();
        }
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }

    public function all_riders()
    {
        try {
            return $this->model::where('type', '=', 'rider')->get();
        }
        catch (\Exception $exception) {
            throw new AllUserException($exception->getMessage());
        }
    }

    public function search_users($query, $user_type)
    {
        // search block
        if($user_type == 'rider'){
            $users = User::where('type', 'rider')
                            ->where(function($q) use($query){
                                $q->orWhere('phone', 'LIKE', '%'.$query.'%');
                                $q->orWhere('name', 'LIKE', '%'.$query.'%');
                                $q->orWhere('email', 'LIKE', '%'.$query.'%');
                            })
                            ->paginate(env('PAGINATION'));
        }
        else{
            $users = User::where(function($q) use($query){
                                $q->orWhere('phone', 'LIKE', '%'.$query.'%');
                                $q->orWhere('name', 'LIKE', '%'.$query.'%');
                                $q->orWhere('email', 'LIKE', '%'.$query.'%');
                            })
                            ->paginate(env('PAGINATION'));
        }

        return $users;
    }
}
