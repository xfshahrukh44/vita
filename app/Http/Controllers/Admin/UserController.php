<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $users = $this->userService->paginate_staff(env('PAGINATE'));
        $user_type = 'staff';
        return view('admin.user.user', compact('users', 'user_type'));
    }

    public function getRiders(Request $request)
    {
        // paginate_riders
        $users = $this->userService->paginate_riders(env('PAGINATE'));
        $user_type = 'rider';
        return view('admin.user.user', compact('users', 'user_type'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'phone' => 'required|unique:users'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        $this->userService->create($request->all());
        
        if($request->identifier == 'rider'){
            return $this->getRiders($request);
        }

        return redirect()->back();
    }
    
    public function show($id)
    {
        return $this->userService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        
        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'password' => 'sometimes',
            'email' => 'sometimes|unique:users,email,'.$request->hidden,
            'phone' => 'sometimes|unique:users,phone,'.$request->hidden,
        ]);

        if($request->password == NULL){
            $request = Arr::except($request,['password']);
        }

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);
        
        if(auth()->user()->type != 'superadmin')
        {
            $request['type'] = 'customer';
        }

        $this->userService->update($request->all(), $id);

        if($request->identifier == 'rider'){
            return $this->getRiders($request);
        }

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->userService->delete($id);

        if($request->user_type == 'rider'){
            return $this->getRiders($request);
        }
        
        return redirect()->back();
    }

    public function search_users(Request $request)
    {
        $query = $request['query'];
        $user_type = $request['user_type'];
        
        $users = $this->userService->search_users($query, $user_type);

        return view('admin.user.user', compact('users','user_type'));
    }
}
