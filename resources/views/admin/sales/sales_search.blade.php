@extends('admin.layouts.master')

@section('content_header')
<style>
.modal-body {
    overflow : auto;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection

@section('content_body')

    <!-- markup to be injected -->
    <!-- search form -->
    <h2 class="text-center display-3">Sales Ledgers</h2>

    <!-- all form-->
    <h3 class="offset-md-1">All</h3>
    <form class="all" data-select2-id="13">
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- date from -->
                    <div class="col-5">
                        <div class="form-group">
                            <label>Date(from):</label>
                            <input type="date" class="form-control date_from" name="date_from">
                        </div>
                    </div>
                    <!-- date to -->
                    <div class="col-5">
                        <div class="form-group">
                            <label>Date(to):</label>
                            <input type="date" class="form-control date_to" name="date_to">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-2">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-block btn-primary form-control fetch_sales" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <!-- customer_wise form-->
    <h3 class="offset-md-1">Customer-Wise</h3>
    <form class="customer_wise" data-select2-id="13">
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- customer_id -->
                    <div class="col-5">
                        <div class="form-group">
                            <label>Customer:</label>
                            <select class="form-control customer_id" name="customer_ids[]" multiple="multiple">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- date from -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(from):</label>
                            <input type="date" class="form-control date_from" name="date_from">
                        </div>
                    </div>
                    <!-- date to -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(to):</label>
                            <input type="date" class="form-control date_to" name="date_to">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-1">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-block btn-primary form-control fetch_sales" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <hr>

    <!-- product_wise form-->
    <h3 class="offset-md-1">Product-Wise</h3>
    <form class="product_wise" data-select2-id="13">
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- product_id -->
                    <div class="col-5">
                        <div class="form-group">
                            <label>Product:</label>
                            <select class="form-control product_id" name="product_id" multiple="multiple">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{($product->category ? $product->category->name : '').($product->brand ? '-'.$product->brand->name : '').($product->article ? '-'.$product->article : '')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- date from -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(from):</label>
                            <input type="date" class="form-control date_from" name="date_from">
                        </div>
                    </div>
                    <!-- date to -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Date(to):</label>
                            <input type="date" class="form-control date_to" name="date_to">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-1">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-block btn-primary form-control fetch_sales" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <hr>

    <!-- combined form-->
    <h3 class="offset-md-1">Combined</h3>
    <form class="combined" data-select2-id="13">
        <div class="row" data-select2-id="12">
            <div class="col-md-10 offset-md-1" data-select2-id="11">
                <div class="row" data-select2-id="10" style="border: 1px solid #dee2e6; border-radius: 8px; padding: 10px;">
                    <!-- customer_id -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Customer:</label>
                            <select class="form-control customer_id" name="customer_id" multiple="multiple">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- product_id -->
                    <div class="col-3">
                        <div class="form-group">
                            <label>Product:</label>
                            <select class="form-control product_id" name="product_id" multiple="multiple">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{($product->category ? $product->category->name : '').($product->brand ? '-'.$product->brand->name : '').($product->article ? '-'.$product->article : '')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- date from -->
                    <div class="col-2">
                        <div class="form-group">
                            <label>Date(from):</label>
                            <input type="date" class="form-control date_from" name="date_from">
                        </div>
                    </div>
                    <!-- date to -->
                    <div class="col-2">
                        <div class="form-group">
                            <label>Date(to):</label>
                            <input type="date" class="form-control date_to" name="date_to">
                        </div>
                    </div>
                    <!-- search button -->
                    <div class="col-2">
                        <div class="form-group">
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-block btn-primary form-control fetch_sales" disabled="disabled"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>

    <!-- all view -->
    <div class="modal fade" id="all_modal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title title">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_sales_excel" data-form="all_modal">
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
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="total"></td>
                    <td class="total_qty"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>Advance wala customer</td>
                        <td>Dhaaga</td>
                        <td>Price</td>
                        <td>Quantity</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- customer_wise view -->
    <div class="modal fade" id="customer_wise_modal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title title">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_sales_excel" data-form="customer_wise_modal">
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
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="total"></td>
                    <td class="total_qty"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>3R</td>
                        <td>Product</td>
                        <td>Price</td>
                        <td>Quantity</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- product_wise view -->
    <div class="modal fade" id="product_wise_modal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title title">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_sales_excel" data-form="product_wise_modal">
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
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="total"></td>
                    <td class="total_qty"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>Customer</td>
                        <td>Dhaaga</td>
                        <td>Price</td>
                        <td>Quantity</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- combined view -->
    <div class="modal fade" id="combined_modal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
            <div class="modal-header row">
                <h5 class="modal-title title">Ledger</h5>
                <!-- generate excel -->
                <div class="text-right">
                    <button type="button" class="btn btn-success generate_sales_excel" data-form="combined_modal">
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
                    <td></td>
                    <td class="text-bold">Total</td>
                    <td class="total"></td>
                    <td class="total_qty"></td>
                    </tr>
                    <!-- headers -->
                    <tr>
                    <th>Transaction Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    </tr>
                </thead>
                <tbody class="ledger_wrapper">
                    <tr>
                        <td>12.12.12</td>
                        <td>Customer</td>
                        <td>Product</td>
                        <td>Price</td>
                        <td>Quantity</td>
                    </tr>
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- dummy form -->
    <form id="dummy_form" action="{{route('generate_sales_excel')}}" method="POST" target="_blank" hidden>
        @csrf
    </form>

    <script>
        $(document).ready(function(){
            // persistent active sidebar
            var element = $('li a[href*="'+ window.location.pathname +'"]');
            element.parent().parent().parent().addClass('menu-open');
            element.addClass('active');

            // global vars
            var all_sales = {};
            var customer_wise_sales = {};
            var product_wise_sales = {};
            var combined_sales = {};

            // fetch_all_sales
            function fetch_all_sales(date_from, date_to){
                $.ajax({
                    url: "<?php echo(route('all_sales')); ?>",
                    type: 'GET',
                    async: false,
                    data: {date_from: date_from, date_to: date_to},
                    dataType: 'JSON',
                    success: function (data) {
                        all_sales = data;
                    }
                });
            }
            // fetch_customer_wise_sales
            function fetch_customer_wise_sales(customer_id, date_from, date_to){
                $.ajax({
                    url: "<?php echo(route('customer_wise_sales')); ?>",
                    type: 'GET',
                    async: false,
                    data: {customer_id: customer_id, date_from: date_from, date_to: date_to},
                    dataType: 'JSON',
                    success: function (data) {
                        customer_wise_sales = data;
                    }
                });
            }
            // fetch_product_wise_sales
            function fetch_product_wise_sales(product_id, date_from, date_to){
                $.ajax({
                    url: "<?php echo(route('product_wise_sales')); ?>",
                    type: 'GET',
                    async: false,
                    data: {product_id: product_id, date_from: date_from, date_to: date_to},
                    dataType: 'JSON',
                    success: function (data) {
                        product_wise_sales = data;
                    }
                });
            }
            // fetch_combined_sales
            function fetch_combined_sales(customer_id, product_id, date_from, date_to){
                $.ajax({
                    url: "<?php echo(route('combined_sales')); ?>",
                    type: 'GET',
                    async: false,
                    data: {customer_id: customer_id, product_id: product_id, date_from: date_from, date_to: date_to},
                    dataType: 'JSON',
                    success: function (data) {
                        combined_sales = data;
                    }
                });
            }

            // init select2
            $('.customer_wise .customer_id').select2();
            $('.product_wise .product_id').select2();
            $('.combined .customer_id').select2();
            $('.combined .product_id').select2();
            

            // check if all fields are filled
            function check_fields_all(){
                // get all parameteres
                var date_from = $('.all .date_from').val();
                var date_to = $('.all .date_to').val();

                if(date_from && date_to){
                    $('.all .fetch_sales').removeAttr('disabled');
                }
                else{
                    $('.all .fetch_sales').prop('disabled', true);
                }
            }
            function check_fields_customer_wise(){
                // get all parameteres
                var customer_id = $('.customer_wise .customer_id').val();
                var date_from = $('.customer_wise .date_from').val();
                var date_to = $('.customer_wise .date_to').val();

                if(customer_id.length > 0 && date_from && date_to){
                    $('.customer_wise .fetch_sales').removeAttr('disabled');
                }
                else{
                    $('.customer_wise .fetch_sales').prop('disabled', true);
                }
            }
            function check_fields_product_wise(){
                // get all parameteres
                var product_id = $('.product_wise .product_id').val();
                var date_from = $('.product_wise .date_from').val();
                var date_to = $('.product_wise .date_to').val();

                if(product_id.length > 0 && date_from && date_to){
                    $('.product_wise .fetch_sales').removeAttr('disabled');
                }
                else{
                    $('.product_wise .fetch_sales').prop('disabled', true);
                }
            }
            function check_fields_combined(){
                // get all parameteres
                var customer_id = $('.combined .customer_id').val();
                var product_id = $('.combined .product_id').val();
                var date_from = $('.combined .date_from').val();
                var date_to = $('.combined .date_to').val();

                if(customer_id.length > 0 && product_id.length > 0 && date_from && date_to){
                    $('.combined .fetch_sales').removeAttr('disabled');
                }
                else{
                    $('.combined .fetch_sales').prop('disabled', true);
                }
            }

            // ON CHANGE FIELDS
            // all
            $('.all .date_from').on('change', function(){check_fields_all();});
            $('.all .date_to').on('change', function(){check_fields_all();});
            // customer_wise
            $('.customer_wise .customer_id').on('change', function(){check_fields_customer_wise();});
            $('.customer_wise .date_from').on('change', function(){check_fields_customer_wise();});
            $('.customer_wise .date_to').on('change', function(){check_fields_customer_wise();});
            // product_wise
            $('.product_wise .product_id').on('change', function(){check_fields_product_wise();});
            $('.product_wise .date_from').on('change', function(){check_fields_product_wise();});
            $('.product_wise .date_to').on('change', function(){check_fields_product_wise();});
            // combined
            $('.combined .customer_id').on('change', function(){check_fields_combined();});
            $('.combined .product_id').on('change', function(){check_fields_combined();});
            $('.combined .date_from').on('change', function(){check_fields_combined();});
            $('.combined .date_to').on('change', function(){check_fields_combined();});

            // ON SEARCH BUTTON CLICK
            // all
            $('.all .fetch_sales').on('click', function(){
                var date_from = $('.all .date_from').val();
                var date_to = $('.all .date_to').val();

                // fetch sales
                fetch_all_sales(date_from, date_to);

                // set modal title
                var df = new Date(date_from).toDateString();
                var dt = new Date(date_to).toDateString();
                var dates = ' '+df+'-'+dt;
                var small_dates = '<small>('+dates+')</small>';
                $('#all_modal .title').html('All Sales ' + small_dates);

                // empty table wrapper
                $('#all_modal .ledger_wrapper').html('');
                // if sales > 0 append in wrapper
                if(all_sales.sales.length > 0){
                    for(var i = 0; i < all_sales.sales.length; i++){
                        $('#all_modal .ledger_wrapper').prepend('<tr><td class="transaction_dates">'+new Date(all_sales.sales[i].transaction_date).toDateString()+'</td><td class="customers">'+all_sales.sales[i].customer+'</td><td class="products">'+all_sales.sales[i].product+'</td><td class="prices">Rs.'+(all_sales.sales[i].price ? (all_sales.sales[i].price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : '')+'</td><td class="quantities">'+all_sales.sales[i].quantity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td></tr>');
                    }
                }
                else{
                    $('#all_modal .ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=5>No Entries</td></tr>');
                }

                // set total
                $('#all_modal .total').html('Rs.'+all_sales.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#all_modal .total_qty').html(all_sales.total_qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units.');

                $('#all_modal').modal('show');

            });
            // customer_wise
            $('.customer_wise .fetch_sales').on('click', function(){
                var customer_id = $('.customer_wise .customer_id').val();
                var date_from = $('.customer_wise .date_from').val();
                var date_to = $('.customer_wise .date_to').val();

                // fetch sales
                fetch_customer_wise_sales(customer_id, date_from, date_to);

                // set modal title
                var customer_names = "";
                var customer_count = 0;
                $('.customer_wise .customer_id option:selected').each(function(){
                    customer_names += (customer_count + 1) + '. ' + $(this).text() + '\n';
                    customer_count += 1;
                });
                var df = new Date(date_from).toDateString();
                var dt = new Date(date_to).toDateString();
                var dates = '<small>('+df+' - '+dt+')</small>';
                var main_message = 'Sales for: <span title="'+customer_names+'">'+customer_count+' customers.</span>';
                $('#customer_wise_modal .title').html(main_message + dates);

                // empty table wrapper
                $('#customer_wise_modal .ledger_wrapper').html('');
                // if sales > 0 append in wrapper
                if(customer_wise_sales.sales.length > 0){
                    for(var i = 0; i < customer_wise_sales.sales.length; i++){
                        $('#customer_wise_modal .ledger_wrapper').prepend('<tr><td class="transaction_dates">'+new Date(customer_wise_sales.sales[i].transaction_date).toDateString()+'</td><td class="customers">'+customer_wise_sales.sales[i].customer+'</td><td class="products">'+customer_wise_sales.sales[i].product+'</td><td class="prices">Rs.'+(customer_wise_sales.sales[i].price ? (customer_wise_sales.sales[i].price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : '')+'</td><td class="quantities">'+customer_wise_sales.sales[i].quantity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td></tr>');
                    }
                }
                else{
                    $('#customer_wise_modal .ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=5>No Entries</td></tr>');
                }

                // set total
                $('#customer_wise_modal .total').html('Rs.'+customer_wise_sales.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#customer_wise_modal .total_qty').html(customer_wise_sales.total_qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units.');

                $('#customer_wise_modal').modal('show');
            });
            // product_wise
            $('.product_wise .fetch_sales').on('click', function(){
                var product_id = $('.product_wise .product_id').val();
                var date_from = $('.product_wise .date_from').val();
                var date_to = $('.product_wise .date_to').val();

                // fetch sales
                fetch_product_wise_sales(product_id, date_from, date_to);

                // set modal title
                var product_names = "";
                var product_count = 0;
                $('.product_wise .product_id option:selected').each(function(){
                    product_names += (product_count + 1) + '. ' + $(this).text() + '\n';
                    product_count += 1;
                });
                var df = new Date(date_from).toDateString();
                var dt = new Date(date_to).toDateString();
                var dates = '<small>('+df+' - '+dt+')</small>';
                var main_message = 'Sales for: <span title="'+product_names+'">'+product_count+' products.</span>';
                $('#product_wise_modal .title').html(main_message + dates);

                // empty table wrapper
                $('#product_wise_modal .ledger_wrapper').html('');
                // if sales > 0 append in wrapper
                if(product_wise_sales.sales.length > 0){
                    for(var i = 0; i < product_wise_sales.sales.length; i++){
                        $('#product_wise_modal .ledger_wrapper').prepend('<tr><td class="transaction_dates">'+new Date(product_wise_sales.sales[i].transaction_date).toDateString()+'</td><td class="customers">'+product_wise_sales.sales[i].customer+'</td><td class="products">'+product_wise_sales.sales[i].product+'</td><td class="prices">Rs.'+(product_wise_sales.sales[i].price ? (product_wise_sales.sales[i].price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : '')+'</td><td class="quantities">'+product_wise_sales.sales[i].quantity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td></tr>');
                    }
                }
                else{
                    $('#product_wise_modal .ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=5>No Entries</td></tr>');
                }

                // set total
                $('#product_wise_modal .total').html('Rs.'+product_wise_sales.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#product_wise_modal .total_qty').html(product_wise_sales.total_qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units.');

                $('#product_wise_modal').modal('show');

            });
            // combined
            $('.combined .fetch_sales').on('click', function(){
                var customer_id = $('.combined .customer_id').val();
                var product_id = $('.combined .product_id').val();
                var date_from = $('.combined .date_from').val();
                var date_to = $('.combined .date_to').val();

                // fetch sales
                fetch_combined_sales(customer_id, product_id, date_from, date_to);

                // set modal title
                var customer_names = "";
                var customer_count = 0;
                $('.combined .customer_id option:selected').each(function(){
                    customer_names += (customer_count + 1) + '. ' + $(this).text() + '\n';
                    customer_count += 1;
                });
                var product_names = "";
                var product_count = 0;
                $('.combined .product_id option:selected').each(function(){
                    product_names += (product_count + 1) + '. ' + $(this).text() + '\n';
                    product_count += 1;
                });
                var df = new Date(date_from).toDateString();
                var dt = new Date(date_to).toDateString();
                var dates = '<small>('+df+' - '+dt+')</small>';
                var customer_span = '<span title="'+customer_names+'">'+customer_count+' customers.</span>'
                var product_span = '<span title="'+product_names+'">'+product_count+' products.</span>'
                var main_message = 'Sales for: ' + customer_span + ' | ' + product_span;
                $('#combined_modal .title').html(main_message + dates);

                // empty table wrapper
                $('#combined_modal .ledger_wrapper').html('');
                // if sales > 0 append in wrapper
                if(combined_sales.sales.length > 0){
                    for(var i = 0; i < combined_sales.sales.length; i++){
                        $('#combined_modal .ledger_wrapper').prepend('<tr><td class="transaction_dates">'+new Date(combined_sales.sales[i].transaction_date).toDateString()+'</td><td class="customers">'+combined_sales.sales[i].customer+'</td><td class="products">'+combined_sales.sales[i].product+'</td><td class="prices">Rs.'+(combined_sales.sales[i].price ? (combined_sales.sales[i].price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : '')+'</td><td class="quantities">'+combined_sales.sales[i].quantity.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</td></tr>');
                    }
                }
                else{
                    $('#combined_modal .ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=3>No Entries</td></tr>');
                }

                // set total
                $('#combined_modal .total').html('Rs.'+combined_sales.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#combined_modal .total_qty').html(combined_sales.total_qty.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' units.');

                $('#combined_modal').modal('show');

            });

            // on generate_sales_excel click
            $('.generate_sales_excel').on('click', function(){
                // identify source form
                var form_id = '#' + $(this).data('form');
                // empty wrapper
                $('#dummy_form').html('@csrf');

                // transaction_dates
                $(form_id + ' .transaction_dates').each(function(){
                    $('#dummy_form').append('<input name="transaction_dates[]" value="'+$(this).text()+'"></input>')
                });
                // customers
                $(form_id + ' .customers').each(function(){
                    $('#dummy_form').append('<input name="customers[]" value="'+$(this).text()+'"></input>')
                });
                // products
                $(form_id + ' .products').each(function(){
                    $('#dummy_form').append('<input name="products[]" value="'+$(this).text()+'"></input>')
                });
                // prices
                $(form_id + ' .prices').each(function(){
                    $('#dummy_form').append('<input name="prices[]" value="'+$(this).text()+'"></input>')
                });
                // quantities
                $(form_id + ' .quantities').each(function(){
                    $('#dummy_form').append('<input name="quantities[]" value="'+$(this).text()+'"></input>')
                });
                // title
                $('#dummy_form').append('<input name="title" value="'+$(form_id + ' .title').text()+'"></input>')
                // total
                $('#dummy_form').append('<input name="total" value="'+$(form_id + ' .total').text()+'"></input>')
                // total_qty
                $('#dummy_form').append('<input name="total_qty" value="'+$(form_id + ' .total_qty').text()+'"></input>')
                // submit
                $('#dummy_form').submit();
            });
        });
    </script>

@endsection('content_body')