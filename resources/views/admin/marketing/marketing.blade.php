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
    <h2 class="text-center display-3">Marketing Plan</h2>
    <form action="{{route('search_marketing')}}" method="get">
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
                            <button type="submit" class="btn btn-block btn-primary form-control fetch_marketings" disabled="disabled"><i class="fas fa-search"></i></button>
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
            <h2 class="text-center" style="font-weight: normal;">Marketing Plan of {{return_date_pdf($date)}}</h2>
            <div class="row">
                <!-- customers_to_visit -->
                <button type="button" class="collapsible">
                    <h5>Customers to Visit: {{count($customers)}}</h5>
                </button>
                <div class="col-md-12 cntnt customers_to_visit" style="overflow-x:auto;">
                    <!-- <h5>Customers to Visit: {{count($customers)}}</h5> -->
                    <table class="table table-striped table-bordered col-md-12 table-sm">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Shop</th>
                                <th>Market</th>
                                <th>Area</th>
                                <th>Designated Rider</th>
                                <th>Assign Rider</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        {{$customer->name ? $customer->name : ''}}
                                        <input class="customer_id" type="hidden" value="{{$customer->id}}"></input>
                                        <input class="date" type="hidden" value="{{$ymd}}"}}></input>
                                    </td>
                                    <td>{{$customer->contact_number ? $customer->contact_number : ''}}</td>
                                    <td>{{$customer->shop_name ? $customer->shop_name : ''}}</td>
                                    <td>{{$customer->market ? $customer->market->name : ''}}</td>
                                    <td>{{($customer->market && $customer->market->area) ? $customer->market->area->name : ''}}</td>
                                    <td class="designated_rider">{{return_marketing_rider_for_customer($customer->id, $ymd)}}</td>
                                    <td>
                                        <div class="form-group">
                                            <select name="riders[]" class="form-control rider_selections">
                                                <option value="">Select rider</option>
                                                @foreach($riders as $rider)
                                                    <option value="{{$rider->id}}">{{$rider->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- payments_to_receive -->
                <button type="button" class="collapsible">
                    <h5>Payments to Receive: {{count($receivings)}}</h5>
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
                                <th>Designated Rider</th>
                                <th>Assign Rider</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receivings as $receiving)
                                <tr>
                                    <td>
                                        {{$receiving->customer ? $receiving->customer->name : ''}}
                                        <input class="receiving_id" type="hidden" value="{{$receiving->id}}"></input>
                                        <input class="date" type="hidden" value="{{$ymd}}"}}></input>
                                    </td>
                                    <td>{{$receiving->customer ? $receiving->customer->contact_number : ''}}</td>
                                    <td>{{$receiving->customer ? $receiving->customer->shop_name : ''}}</td>
                                    <td>{{($receiving->customer && $receiving->customer->market) ? $receiving->customer->market->name : ''}}</td>
                                    <td>{{($receiving->customer && $receiving->customer->market && $receiving->customer->market->area) ? $receiving->customer->market->area->name : ''}}</td>
                                    <td>{{$receiving->invoice ? $receiving->invoice->id : ''}}</td>
                                    <td>Rs. {{$receiving->invoice ? number_format($receiving->invoice->total) : ''}}</td>
                                    <td>Rs. {{$receiving->invoice ? number_format($receiving->invoice->amount_pay) : ''}}</td>
                                    <td>Rs. {{$receiving->invoice ? number_format($receiving->invoice->total - $receiving->invoice->amount_pay) : ''}}</td>
                                    <td class="designated_rider">{{return_marketing_rider_for_receiving($receiving->id, $ymd)}}</td>
                                    <td>
                                        <div class="form-group">
                                            <select name="riders[]" class="form-control rider_selections">
                                                <option value="">Select rider</option>
                                                @foreach($riders as $rider)
                                                    <option value="{{$rider->id}}">{{$rider->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- orders_to_dispatch -->
                <button type="button" class="collapsible">
                    <h5>Orders to Dispatch: {{count($invoices)}}</h5>
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
                                <th>Designated Rider</th>
                                <th>Assign Rider</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>
                                        {{$invoice->customer ? $invoice->customer->name : ''}}
                                        <input class="invoice_id" type="hidden" value="{{$invoice->id}}"></input>
                                        <input class="date" type="hidden" value="{{$ymd}}"}}></input>
                                    </td>
                                    <td>{{$invoice->customer ? $invoice->customer->contact_number : ''}}</td>
                                    <td>{{$invoice->customer ? $invoice->customer->shop_name : ''}}</td>
                                    <td>{{($invoice->customer && $invoice->customer->market) ? $invoice->customer->market->name : ''}}</td>
                                    <td>{{($invoice->customer && $invoice->customer->market && $invoice->customer->market->area) ? $invoice->customer->market->area->name : ''}}</td>
                                    <td>{{$invoice->id}}</td>
                                    <td>Rs. {{$invoice->total ? number_format($invoice->total) : ''}}</td>
                                    <td>Rs. {{$invoice->amount_pay ? number_format($invoice->amount_pay) : ''}}</td>
                                    <td>Rs. {{($invoice->total && $invoice->amount_pay) ? number_format($invoice->total - $invoice->amount_pay) : ''}}</td>
                                    <td class="designated_rider">{{return_marketing_rider_for_invoice($invoice->id, $ymd)}}</td>
                                    <td>
                                        <div class="form-group">
                                            <select name="riders[]" class="form-control rider_selections">
                                                <option value="">Select rider</option>
                                                @foreach($riders as $rider)
                                                    <option value="{{$rider->id}}">{{$rider->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Ledger view -->
    <div class="modal fade" id="detailLedgerModal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title" id="detailLedgerModalLabel">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_expenses_excel">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-x:auto;">
                <table class="table table-bordered table-striped table-condensed table-sm">
                <thead>
                    <!-- outstanding balance row -->
                    <tr class="table-info">
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="detail_total"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Amount</th>
                    <th>Details</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>444</td>
                        <td>jaksd aksj hakjsdaskj akjsd jkashd</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- dummy form -->
    <form id="dummy_form" action="{{route('generate_expenses_excel')}}" method="POST" target="_blank" hidden>
        @csrf
    </form>


    <script>
        $(document).ready(function(){
            // persistent active sidebar
            var element = $('li a[href*="'+ window.location.pathname +'"]');
            element.parent().addClass('menu-open');

            // on date change
            $('.date').on('change', function(){
                if($(this).val()){
                    $('.fetch_marketings').removeAttr('disabled');
                }
                else{
                    $('.fetch_marketings').prop('disabled', true);
                }
            })

            // customers_to_visit
            $('.customers_to_visit .rider_selections').on('change', function(){
                var tr = $(this).parent().parent().parent();
                var customer_id = tr.find('.customer_id');
                var date = tr.find('.date');
                var designated_rider = tr.find('.designated_rider')
                customer_id = customer_id.val();
                date = date.val();
                var rider_id = $(this).val();
                if(rider_id){
                    $.ajax({
                        url: "<?php echo(route('assign_marketing_rider_for_customer')); ?>",
                        type: 'GET',
                        async: false,
                        data: {
                            customer_id: customer_id,
                            rider_id: rider_id,
                            date: date
                        },
                        success: function (data) {
                            designated_rider.text(data);
                        }
                    });
                }
            })

            // payments_to_receive
            $('.payments_to_receive .rider_selections').on('change', function(){
                var tr = $(this).parent().parent().parent();
                var receiving_id = tr.find('.receiving_id');
                var date = tr.find('.date');
                var designated_rider = tr.find('.designated_rider')
                receiving_id = receiving_id.val();
                date = date.val();
                var rider_id = $(this).val();
                if(rider_id){
                    $.ajax({
                        url: "<?php echo(route('assign_marketing_rider_for_receiving')); ?>",
                        type: 'GET',
                        async: false,
                        data: {
                            receiving_id: receiving_id,
                            rider_id: rider_id,
                            date: date
                        },
                        success: function (data) {
                            designated_rider.text(data);
                        }
                    });
                }
            })

            // orders_to_dispatch
            $('.orders_to_dispatch .rider_selections').on('change', function(){
                var tr = $(this).parent().parent().parent();
                var invoice_id = tr.find('.invoice_id');
                var date = tr.find('.date');
                var designated_rider = tr.find('.designated_rider')
                invoice_id = invoice_id.val();
                date = date.val();
                var rider_id = $(this).val();
                if(rider_id){
                    $.ajax({
                        url: "<?php echo(route('assign_marketing_rider_for_invoice')); ?>",
                        type: 'GET',
                        async: false,
                        data: {
                            invoice_id: invoice_id,
                            rider_id: rider_id,
                            date: date
                        },
                        success: function (data) {
                            designated_rider.text(data);
                        }
                    });
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