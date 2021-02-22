<?php

namespace App\Repositories;

use App\Exceptions\Invoice\AllInvoiceException;
use App\Exceptions\Invoice\CreateInvoiceException;
use App\Exceptions\Invoice\UpdateInvoiceException;
use App\Exceptions\Invoice\DeleteInvoiceException;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\CustomerService;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

abstract class InvoiceRepository implements RepositoryInterface
{
    private $model;
    private $customerService;
    
    public function __construct(Invoice $invoice, CustomerService $customerService)
    {
        $this->model = $invoice;
        $this->customerService = $customerService;
    }
    
    public function create(array $data)
    {
        try 
        {
            $invoice = $this->model->create($data);
            
            return [
                'invoice' => $this->find($invoice->id)
            ];
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
                return [
                    'success' => false,
                    'message' => 'Could`nt find invoice',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'invoice' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteInvoiceException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find invoice',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'invoice' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateInvoiceException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $invoice = $this->model::with('customer.market.area', 'invoice_products.product.brand', 'invoice_products.product.unit', 'invoice_products.product.category', 'order.customer.market.area')->find($id);
            if(!$invoice)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find invoice',
                ];
            }
            return [
                'success' => true,
                'invoice' => $invoice,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('customer.market.area', 'invoice_products.product')->get();
        }
        catch (\Exception $exception) {
            throw new AllInvoiceException($exception->getMessage());
        }
    }

    public function paginate($pagination)
    {
        try {
            return $this->model::where('amount_pay', '>=', 'total')->with('customer')->orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllInvoiceException($exception->getMessage());
        }
    }

    public function search_invoices($query)
    {
        // foreign fields
        // customers
        $customers = $this->customerService->search_customers($query);
        $customer_ids = [];
        foreach($customers as $customer){
            array_push($customer_ids, $customer->id);
        }

        // search block
        $invoices = Invoice::whereIn('customer_id', $customer_ids)
                        ->orWhere('total', 'LIKE', '%'.$query.'%')
                        ->orWhere('amount_pay', 'LIKE', '%'.$query.'%')
                        ->paginate(env('PAGINATION'));

        return $invoices;
    }

    public function generate_invoice_pdf($invoice_id)
    {
        $invoice = ($this->find($invoice_id))['invoice'];
        $pdf_name = $invoice->id.' - '.return_date_pdf(Carbon::now()).' - '.$invoice->customer->name.'.pdf';
        $customPaper = array(0,0,800,600);
        $pdf = PDF::loadview('admin.invoice.invoice_pdf', compact('invoice', 'pdf_name'))->setPaper( $customPaper , 'landscape');
        return $pdf->stream($pdf_name, array('Attachment'=>0));
        // return redirect()->away($pdf->stream(Carbon::now() . '.pdf'));
    }

    public function fetch_by_customer($data)
    {
        return $this->model::with('invoice_products')
                            ->where('customer_id', $data['customer_id'])
                            ->where('created_at', '>=', $data['date_from'])
                            ->where('created_at', '<=', $data['date_to'])
                            ->get();
    }
}