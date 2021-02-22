<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Category\AllCategoryException;
use App\Exceptions\Category\CreateCategoryException;
use App\Exceptions\Category\DeletedCategoryException;
use App\Exceptions\Category\UpdateCategoryException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->categoryService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->categoryService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->categoryService->find($id));
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

        $data = $this->categoryService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->categoryService->delete($id);
    }
}
