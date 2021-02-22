<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StockOutService;
use App\Services\CustomerService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Validator;

class StockOutController extends Controller
{
    private $stockOutService;
    private $customerService;
    private $productService;

    public function __construct(StockOutService $stockOutService, ProductService $productService, CustomerService $customerService)
    {
        $this->stockOutService = $stockOutService;
        $this->customerService = $customerService;
        $this->productService = $productService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $stockOuts = $this->stockOutService->paginate(env('PAGINATE'));
        $customers = $this->customerService->all();
        $products = $this->productService->all();
        return view('admin.stockOut.stockOut', compact('stockOuts', 'customers', 'products'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'sometimes',
            'price' => 'sometimes',
            'transaction_date' => 'sometimes',
            'expense_type' => 'sometimes',
            'narration' => 'sometimes',
        ]);

        if($validator->fails())
        {
            $errors = $validator->errors();
            return redirect()->back()->with(compact('errors'));
        }

        $this->stockOutService->create($request->all());

        return redirect()->back();
    }
    
    public function show($id)
    {
        return $this->stockOutService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $stockOut = ($this->show($id))['stockOut'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'product_id' => 'sometimes',
            'quantity' => 'somtimes',
            'price' => 'sometimes',
            'transaction_date' => 'sometimes',
            'expense_type' => 'sometimes',
            'narration' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->stockOutService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->stockOutService->delete($id);

        return redirect()->back();
    }

    public function search_stockOuts(Request $request)
    {
        $query = $request['query'];
        
        $stockOuts = $this->stockOutService->search_stockOuts($query);
        $customers = $this->customerService->all();
        $products = $this->productService->all();

        return view('admin.stockOut.stockOut', compact('stockOuts', 'customers', 'products'));
    }
}
