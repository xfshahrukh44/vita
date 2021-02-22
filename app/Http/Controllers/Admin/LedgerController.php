<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LedgerService;
use App\Services\CustomerService;
use App\Services\VendorService;
use Illuminate\Support\Facades\Validator;

class LedgerController extends Controller
{
    private $ledgerService;
    private $customerService;
    private $vendorService;

    public function __construct(LedgerService $ledgerService, CustomerService $customerService, VendorService $vendorService)
    {
        $this->ledgerService = $ledgerService;
        $this->customerService = $customerService;
        $this->vendorService = $vendorService;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $ledgers = $this->ledgerService->paginate(env('PAGINATE'));
        $customers = $this->customerService->all();
        $vendors = $this->vendorService->all();
        return view('admin.ledger.ledger', compact('ledgers', 'customers', 'vendors'));
    }

    public function get_customer_ledgers()
    {
        $ledgers = $this->ledgerService->paginate_customer_ledgers(env('PAGINATE'));
        $customers = $this->customerService->all();
        $vendors = $this->vendorService->all();
        $client_type = 'customer';
        return view('admin.ledger.ledger', compact('ledgers', 'client_type', 'customers', 'vendors'));
    }

    public function get_vendor_ledgers()
    {
        $ledgers = $this->ledgerService->paginate_vendor_ledgers(env('PAGINATE'));
        $customers = $this->customerService->all();
        $vendors = $this->vendorService->all();
        $client_type = 'vendor';
        return view('admin.ledger.ledger', compact('ledgers', 'client_type', 'customers', 'vendors'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'vendor_id' => 'sometimes',
            'invoice_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'payment_id' => 'sometimes',
            'amount' => 'required',
            'type' => 'required',
            'transaction_date' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->ledgerService->create($request->all());

        return redirect()->back();
    }
    
    public function show($id)
    {
        return $this->ledgerService->find($id);
    }
    
    public function update(Request $request, $id)
    {
        $id = $request->hidden;
        $ledger = ($this->show($id))['ledger'];

        if(!(auth()->user()->id == $id || auth()->user()->type == "superadmin"))
        {
            return response()->json([
                'success' => FALSE,
                'message' => 'Not allowed.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes',
            'vendor_id' => 'sometimes',
            'invoice_id' => 'sometimes',
            'receiving_id' => 'sometimes',
            'payment_id' => 'sometimes',
            'amount' => 'sometimes',
            'type' => 'sometimes',
            'transaction_date' => 'sometimes',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toArray(), 400);

        $this->ledgerService->update($request->all(), $id);

        return redirect()->back();
    }
    
    public function destroy(Request $request, $id)
    {
        $id = $request->hidden;

        $this->ledgerService->delete($id);

        return redirect()->back();
    }

    public function search_ledgers(Request $request)
    {
        $query = $request['query'];
        
        $ledgers = $this->ledgerService->search_ledgers($query);
        $customers = $this->customerService->all();

        return view('admin.ledger.ledger', compact('ledgers', 'customers'));
    }

    public function search_customer_ledgers(Request $request){
        $query = $request['query'];
        $ledgers = $this->customerService->search_customers($query);
        $customers = $this->customerService->all();
        $vendors = $this->vendorService->all();
        $client_type = 'customer';
        return view('admin.ledger.ledger', compact('ledgers', 'client_type', 'customers', 'vendors'));
    }

    public function search_vendor_ledgers(Request $request){
        $query = $request['query'];
        $ledgers = $this->vendorService->search_vendors($query);
        $customers = $this->customerService->all();
        $vendors = $this->vendorService->all();
        $client_type = 'vendor';
        return view('admin.ledger.ledger', compact('ledgers', 'client_type', 'customers', 'vendors'));
    }
}
