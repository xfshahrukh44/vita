<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('auth');
    }
    
    public function index()
    {
        $expenses = $this->expenseService->paginate(env('PAGINATE'));
        return view('admin.expense.expense', compact('expenses'));
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

        $this->expenseService->create($request->all());

        return redirect()->back();
    }
    
    public function show(Request $request, $id)
    {
        $id = $request->id;
        return $this->expenseService->find($id);
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
            'detail' => 'sometimes',
            'type' => 'sometimes',
            'amount' => 'sometimes',
            'date' => 'sometimes'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->expenseService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->expenseService->delete($id);

        return redirect()->back();
    }

    public function fetch_expenses(Request $request)
    {
        $data['type'] = $request->type;
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;

        return $this->expenseService->fetch_expenses($data);
    }
}
