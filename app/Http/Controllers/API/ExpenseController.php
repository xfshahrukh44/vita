<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Expense\AllExpenseException;
use App\Exceptions\Expense\CreateExpenseException;
use App\Exceptions\Expense\DeletedExpenseException;
use App\Exceptions\Expense\UpdateExpenseException;
use App\Exceptions\Group\AllGroupException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ExpenseService;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    private $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        return response()->json($this->expenseService->all());
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'detail' => 'sometimes',
            'type' => 'sometimes',
            'amount' => 'sometimes',
            'date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->expenseService->create($request->all());

        return response()->json($data);
    }
    
    public function show($id)
    {
        return response()->json($this->expenseService->find($id));
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
            'detail' => 'sometimes',
            'type' => 'sometimes',
            'amount' => 'sometimes',
            'date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $data = $this->expenseService->update($request->all(), $id);

        return response()->json($data);
    }
    
    public function destroy($id)
    {
        return $this->expenseService->delete($id);
    }
}
