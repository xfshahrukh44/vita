@extends('admin.layouts.master')

@section('content_header')
    <style>
    .collapsible {
    background-color: #777;
    color: white;
    cursor: pointer;
    padding: 8px;
    width: 100%;
    border: none;
    box-shadow: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    }

    .active, .collapsible:hover {
    background-color: #555;
    }

    .cntnt {
    padding: 0 18px;
    display: none;
    overflow: hidden;
    background-color: #f1f1f1;
    }
    </style>
@endsection


@section('content_body')
    <!-- markup to be injected -->
    <!-- search form -->
    <h2 class="text-center display-3">Your Marketing Tasks</h2>
    <form action="{{route('search_marketing_tasks')}}" method="get">
        @csrf
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- date -->
                    <div class="col-10">
                        <div class="form-group">
                            <label>Search by Date:</label>
                            <input type="date" class="form-control date" name="date">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-2">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="submit" class="btn btn-block btn-primary form-control fetch_marketing_tasks" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
    
    <hr>
    
    <!-- today -->
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h2 class="text-center" style="font-weight: normal;">Tasks of {{return_date_pdf($date)}}</h2>
            <div class="row">
                <!-- customers_to_visit -->
                <button type="button" class="collapsible">
                    <h5>Customers to Visit: {{count($customer_marketings)}}</h5>
                </button>
                <div class="col-md-12 cntnt customers_to_visit" style="overflow-x:auto;">
                    <table class="table table-striped table-bordered col-md-12 table-sm">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Shop</th>
                                <th>Market</th>
                                <th>Area</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer_marketings as $customer_marketing)
                                <tr>
                                    <td>{{$customer_marketing->customer ? $customer_marketing->customer->name : ''}}</td>
                                    <td>{{$customer_marketing->customer->contact_number ? $customer_marketing->customer->contact_number : ''}}</td>
                                    <td>{{$customer_marketing->customer->shop_name ? $customer_marketing->customer->shop_name : ''}}</td>
                                    <td>{{$customer_marketing->customer->market ? $customer_marketing->customer->market->name : ''}}</td>
                                    <td>{{($customer_marketing->customer->market && $customer_marketing->customer->market->area) ? $customer_marketing->customer->market->area->name : ''}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- payments_to_receive -->
                <button type="button" class="collapsible">
                    <h5>Payments to Receive: {{count($receiving_marketings)}}</h5>
                </button>
                <div class="col-md-12 cntnt payments_to_receive" style="overflow-x:auto;">
                    <table class="table table-striped table-bordered col-md-12 table-sm">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Shop</th>
                                <th>Market</th>
                                <th>Area</th>
                                <th>Invoice ID</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receiving_marketings as $receiving_marketing)
                                <tr>
                                    <td>{{$receiving_marketing->receiving->customer ? $receiving_marketing->receiving->customer->name : ''}}</td>
                                    <td>{{$receiving_marketing->receiving->customer ? $receiving_marketing->receiving->customer->contact_number : ''}}</td>
                                    <td>{{$receiving_marketing->receiving->customer ? $receiving_marketing->receiving->customer->shop_name : ''}}</td>
                                    <td>{{($receiving_marketing->receiving->customer && $receiving_marketing->receiving->customer->market) ? $receiving_marketing->receiving->customer->market->name : ''}}</td>
                                    <td>{{($receiving_marketing->receiving->customer && $receiving_marketing->receiving->customer->market && $receiving_marketing->receiving->customer->market->area) ? $receiving_marketing->receiving->customer->market->area->name : ''}}</td>
                                    <td>{{$receiving_marketing->receiving->invoice ? $receiving_marketing->receiving->invoice->id : ''}}</td>
                                    <td>Rs. {{$receiving_marketing->receiving->invoice ? number_format($receiving_marketing->receiving->invoice->total) : ''}}</td>
                                    <td>Rs. {{$receiving_marketing->receiving->invoice ? number_format($receiving_marketing->receiving->invoice->amount_pay) : ''}}</td>
                                    <td>Rs. {{$receiving_marketing->receiving->invoice ? number_format($receiving_marketing->receiving->invoice->total - $receiving_marketing->receiving->invoice->amount_pay) : ''}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- orders_to_dispatch -->
                <button type="button" class="collapsible">
                    <h5>Orders to Dispatch: {{count($invoice_marketings)}}</h5>
                </button>
                <div class="col-md-12 cntnt orders_to_dispatch" style="overflow-x:auto;">
                    <table class="table table-striped table-bordered col-md-12 table-sm">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Shop</th>
                                <th>Market</th>
                                <th>Area</th>
                                <th>Invoice ID</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice_marketings as $invoice_marketing)
                                <tr>
                                    <td>{{$invoice_marketing->invoice->customer ? $invoice_marketing->invoice->customer->name : ''}}</td>
                                    <td>{{$invoice_marketing->invoice->customer ? $invoice_marketing->invoice->customer->contact_number : ''}}</td>
                                    <td>{{$invoice_marketing->invoice->customer ? $invoice_marketing->invoice->customer->shop_name : ''}}</td>
                                    <td>{{($invoice_marketing->invoice->customer && $invoice_marketing->invoice->customer->market) ? $invoice_marketing->invoice->customer->market->name : ''}}</td>
                                    <td>{{($invoice_marketing->invoice->customer && $invoice_marketing->invoice->customer->market && $invoice_marketing->invoice->customer->market->area) ? $invoice_marketing->invoice->customer->market->area->name : ''}}</td>
                                    <td>{{$invoice_marketing->invoice->id}}</td>
                                    <td>Rs. {{$invoice_marketing->invoice->total ? number_format($invoice_marketing->invoice->total) : ''}}</td>
                                    <td>Rs. {{$invoice_marketing->invoice->amount_pay ? number_format($invoice_marketing->invoice->amount_pay) : ''}}</td>
                                    <td>Rs. {{($invoice_marketing->invoice->total && $invoice_marketing->invoice->amount_pay) ? number_format($invoice_marketing->invoice->total - $invoice_marketing->invoice->amount_pay) : ''}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // persistent active sidebar
            var element = $('li a[href*="'+ window.location.pathname +'"]');
            element.parent().addClass('menu-open');

            // on date change
            $('.date').on('change', function(){
                if($(this).val()){
                    $('.fetch_marketing_tasks').removeAttr('disabled');
                }
                else{
                    $('.fetch_marketing_tasks').prop('disabled', true);
                }
            })
        });
    </script>

    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.display === "block") {
            content.style.display = "none";
            } else {
            content.style.display = "block";
            }
        });
        }
    </script>
@endsection('content_body')