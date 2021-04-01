@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fa fa-clipboard"></i> Invoices</h1>
</div>
<!-- /.col -->
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="#">Admin</a></li>
      <li class="breadcrumb-item"><a href="#">Invoices</a></li>
      <li class="breadcrumb-item active">Invoice</li>
  </ol>
</div>
<!-- /.col -->
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection

@section('content_body')
<!-- Index view -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <!-- <h3 class="">Invoices</h3> -->
                    <!-- <button class="btn btn-success testbtn" id="add_program" data-route="{{route('invoice.store')}}"">
                        <i class="fas fa-plus"></i> Add New Invoice
                    </button> -->
                </div>
                <!-- search bar -->
                <form action="{{route('search_invoices')}}" class="form-wrapper">
                    <div class="row">
                        <!-- search bar -->
                        <div class="topnav col-md-4">
                            <input name="query" class="form-control" id="search_content" type="text" placeholder="Search..">
                        </div>
                        <!-- search button-->
                        <button type="submit" class="btn btn-primary col-md-0 justify-content-start" id="search_button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-md-12" style="overflow-x:auto;">
                    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Invoice ID: activate to sort column ascending"><i  class="fa fa-arrow-up arrow_up_down"></i><i class="fa fa-arrow-down arrow_up_down"></i>Invoice ID</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Invoice ID: activate to sort column ascending"><i  class="fa fa-arrow-up arrow_up_down"></i><i class="fa fa-arrow-down arrow_up_down"></i>Order ID</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending">Date</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Customer Name: activate to sort column ascending">Customer Name</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">Phone</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Address</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Total: activate to sort column ascending">Total</th>
                              <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Amount Paid: activate to sort column ascending">Amount Paid</th>
                                @can('isSuperAdmin')
                                    <th class="sorting">Created By</th> 
                                    <th class="sorting">Modified By</th> 
                                @endcan
                              <!-- <th class="sorting_asc" tabindex="0" rowspan="1" colspan="1">Items</th> -->
                              <th tabindex="0" rowspan="1" colspan="1">Actions</th>
                          </tr>
                        </thead>

                        <tbody>
                            @if(count($invoices) > 0)
                                @foreach($invoices as $invoice)
                                    <tr role="row" class="odd">
                                        <td class="{{'invoice_id'.$invoice->id}}">{{$invoice->id}}</td>
                                        <td class="{{'order_id'.$invoice->id}}">{{$invoice->order ? $invoice->order->id : NULL}}</td>
                                        <td class="{{'total'.$invoice->id}}">{{$invoice->created_at ? return_date($invoice->created_at) : NULL}}</td>
                                        <td class="{{'customer_id'.$invoice->id}}" data-id="{{$invoice->customer ? $invoice->customer_id : NULL}}" data-object="{{$invoice->customer ? $invoice->customer : NULL}}">
                                            <a href="#" class="viewProfileButton" data-id="{{$invoice->customer ? $invoice->customer_id : NULL}}" data-type="{{$invoice->customer ? $invoice->customer->type : NULL}}" data-route="{{$invoice->customer ? route('customer.show',$invoice->customer->id) : '#'}}">
                                                {{$invoice->customer ? $invoice->customer->name : NULL}}
                                            </a>
                                        </td>
                                        <td class="{{'contact_number'.$invoice->id}}">{{$invoice->customer && $invoice->customer->contact_number ? $invoice->customer->contact_number : NULL}}</td>
                                        <td class="{{'address'.$invoice->id}}">{{($invoice->customer && $invoice->customer->shop_name ? $invoice->customer->shop_name : NULL) . ' - ' . ($invoice->customer && $invoice->customer->market ? $invoice->customer->market->name : NULL) . ' - ' .($invoice->customer && $invoice->customer->market ? $invoice->customer->market->area->name : NULL)}}</td>
                                        <td class="{{'total'.$invoice->id}}">Rs.{{number_format($invoice->total)}}</td>
                                        <td class="{{'amount_pay'.$invoice->id}}">Rs.{{number_format($invoice->amount_pay)}}</td>
                                        @can('isSuperAdmin')
                                            <td>{{return_user_name($invoice->created_by)}}</td>
                                            <td>{{return_user_name($invoice->modified_by)}}</td>
                                        @endcan
                                        <td>
                                            <!-- Detail -->
                                            <a href="#" class="detailButton" data-id="{{$invoice->id}}" data-type="{{$invoice->id}}">
                                                <i class="fas fa-shopping-basket blue ml-1"></i>
                                            </a>
                                            <!-- Edit -->
                                            <a href="#" class="editButton" data-id="{{$invoice->id}}" data-object="{{$invoice}}">
                                                <i class="fas fa-edit blue ml-1"></i>
                                            </a>
                                            @can('isSuperAdmin')
                                                <!-- Delete -->
                                                <a href="#" class="deleteButton" data-id="{{$invoice->id}}" data-type="{{$invoice->id}}">
                                                    <i class="fas fa-trash red ml-1"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan=11><h6 align="center">No invoice(s) found</h6></td>
                                </tr>
                            @endif
                        </tbody>

                        <tfoot>
                        </tfoot>

                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                @if(count($invoices) > 0)
                {{$invoices->appends(request()->except('page'))->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInvoiceModalLabel">Add New invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="col-md-12" method="POST" action="{{route('invoice.store')}}">
                @method('POST')
                @include('admin.invoice.invoice_master')

                <!-- buttons -->
                <div class="modal-footer">
                    <button name="completed_status" type="submit" class="btn btn-primary" id="createButton">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteInvoiceModalLabel">Delete Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" method="POST" action="{{route('invoice.destroy', 1)}}">
                <!-- hidden input -->
                @method('DELETE')
                @csrf
                <input class="hidden" type="hidden" name="hidden">
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger" id="deleteButton">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail view -->
<div class="modal fade" id="detailInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="detailInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{route('generate_invoice_pdf', 13)}}" method="GET" id="invoice_detail_form" target="_blank">
                @method('GET')
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="detailInvoiceModalLabel">Invoice details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="overflow-x:auto;">
                    <table class=" table-binvoiceed table-striped p-2" style="width:100%; binvoice: 1px solid black;height: 20px;">
                        <tr role="row">
                            <th>Invoice id:</th>
                            <td><h6 id="invoice_id"></td>
                        </tr>
                        <tr>
                            <th>Customer name:</th>
                            <td><h6 id="customer_name"></h6></td>
                        </tr>
                        <tr>
                            <th>Contact:</th>
                            <td><h6 id="contact_number"></h6></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td><h6 id="address"></h6></td>
                        </tr>
                        <tr>
                            <th>Total Amount:</th>
                            <td><h6 id="detailTotal"></h6></td>
                        </tr>
                        <tr>
                            <th>Amount Payment:</th>
                            <td><h6 id="amount_pay"></h6></td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td><p id="description"></p></td>
                        </tr>
                    </table>
                    <div class="col-md-12">
                        <!-- MASTER INFO -->
                        <!-- Invoice id -->
                        <br>
                        <div class="row" style="overflow-x:auto;">
                            <!-- CHILD INFO -->
                            <table id="itemTable" class="table table-bordered table-hover dtr-inline" role="grid" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Category</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Brand</th>
                                        <th class="sorting_asc" tabindex="0" rowspan="1" colspan="1">Product</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Quantity</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Price</th>
                                        <th class="sorting" tabindex="0" rowspan="1" colspan="1" >Unit</th>
                                    </tr>
                                </thead>

                                <tbody id="table_row_wrapper">
                                    <tr role="row" class="odd">
                                        <td name="invoice_products[]" value="'+invoice.invoice_products[i].id+'" hidden></td>
                                        <td><input type="checkbox" name="invoiced_items[]"></td>
                                        <td class="">'+invoice.invoice_products[i].product.category.name+'</td>
                                        <td class="">'+invoice.invoice_products[i].product.brand.name+'</td>
                                        <td class="">'+invoice.invoice_products[i].product.article+'</td>
                                        <td class="">'+invoice.invoice_products[i].quantity+'</td>
                                        <td class="">'+invoice.invoice_products[i].price+'</td>
                                        <td class="">'+invoice.invoice_products[i].product.unit.name+'</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success generate_invoice" type="submit">Generate Invoice</button>
                    <button class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInvoiceModalLabel">Edit invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="{{route('invoice.update', 1)}}">
                <!-- hidden input -->
                @method('PUT')
                <input type="hidden" class="hidden" name="hidden">
                @include('admin.invoice.invoice_master')
                <!-- buttons -->
                <div class="modal-footer">
                    <button name="completed_status" type="submit" class="btn btn-primary" id="createButton">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Profile view --> 
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                <img class="profile" src="{{asset('img/logo.png')}}" width="150" style="position: relative; left:33%;">
                <hr style="color:gray;">
                <table class="table table-binvoiceed table-striped">
                    <tbody id="table_row_wrapper">
                        <tr role="row" class="odd">
                            <td class="">Full Name</td>
                            <td class="fullname"></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="">Phone</td>
                            <td class="phone"></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="">Address</td>
                            <td class="address"></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="">Email Address</td>
                            <td class="emailAddress"></td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="">Registration Date</td>
                            <td class="registrationDate"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" data-dismiss="modal" style="float: right;">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

  $(document).ready(function(){
    // persistent active sidebar
    var element = $('li a[href*="'+ window.location.pathname +'"]');
    element.parent().parent().parent().addClass('menu-open');
    element.addClass('active');

    // on ready functions
    $('.customer_id').select2();
    fetch_product_labels();

    // GLLOBAL VARS
    var product_labels = "";
    var product = "";
    var customer = "";
    var invoice = "";
    var current_invoice_id = 0;
    var special_discount = 0;
    var invoice_id = "";

    // adding items dynamically*
    var x = 1; //Initial field counter is 1
    var maxField = 40; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var minField = 1; //Input fields decrement limitation
    var removeButton = $('.remove_button'); //Remove button selector
    var wrapper = $('.field_wrapper'); //Input field wrappervar x = 1; //Initial field counter is 1

    // div strings
    var startDiv = '<div class="row">';
    var productDiv = '<div class="col-md-4"><div class="ui-widget"><input class="form-control product_search" name="products[]"><input class="hidden_product_search" type="hidden" name="hidden_product_ids[]"></div></div>';
    var priceDiv = '<div class="form-group col-md-4"><input type="number" class="form-control prices" name="prices[]" required min=0></div>';
    var quantityDiv = '<div class="form-group col-md-3"><input type="number" class="form-control quantities" name="quantities[]" required value=0></div>';
    var removeChildDiv = '<div class="form-group col-md-0 ml-1 remove_button mt-1" style="display: table; vertical-align: middle;"><a class="btn btn-primary"><i class="fas fa-minus" style="color:white;"></i></a></div>';
    var endDiv = '</div>';
    var fieldHTML = startDiv + productDiv + priceDiv + quantityDiv + removeChildDiv + endDiv;

    // fetch product labels
    function fetch_product_labels(){
        // fetch products
        $.ajax({
            url: "<?php echo(route('fetch_product_labels')); ?>",
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                product_labels = data;
            }
        });
    }

    // fetch product
    function fetch_product(id){
        // fetch product
        $.ajax({
            url: "<?php echo(route('product.show', 1)); ?>",
            type: 'GET',
            async: false,
            data: {id: id},
            dataType: 'JSON',
            success: function (data) {
                product = data.product;
            }
        });
    }

    // fetch invoice
    function fetch_invoice(id){
        // fetch invoice
        $.ajax({
            url: "<?php echo(route('invoice.show', 1)); ?>",
            type: 'GET',
            async: false,
            data: {invoice_id: id},
            dataType: 'JSON',
            success: function (data) {
                invoice = data.invoice;
            }
        });
    }

    // fetch customer
    function fetch_customer(id){
        // fetch customer
        $.ajax({
            url: "<?php echo(route('customer.show', 1)); ?>",
            type: 'GET',
            async: false,
            data: {id: id},
            dataType: 'JSON',
            success: function (data) {
                customer = data.customer;
            }
        });
    }
    
    // count order total
    function get_order_total(form){
        var quantities = $(form + ' .quantities');
        var prices = $(form + ' .prices');
        var total = 0;
        for(var i = 0; i < prices.length; i++){
            total += ($(form + ' .prices')[i].value * $(form + ' .quantities')[i].value);
        }
        $(form + ' .total').val(total);
        // final_amount
        $(form + ' .final_amount').val(parseInt($(form + ' .total').val()) + parseInt($(form + ' .previous_amount').val()));
        $(form + ' .balance_due').val(parseInt($(form + ' .final_amount').val()) - parseInt($(form + ' .amount_pay').val()));
    }

    // fetch_by_customer_and_product
    function fetch_by_customer_and_product(){
        $.ajax({
            url: "<?php echo(route('fetch_by_customer_and_product')); ?>",
            type: 'GET',
            async: false,
            data: {
                customer_id: customer.id,
                product_id: product.id
            },
            dataType: 'JSON',
            success: function (data) {
                if(data.success == true){
                    special_discount = data.special_discount.amount;
                }
                else{
                    special_discount = 0;
                }
            }
        });
    }

    // ONCHANGE FUNCTIONS
    $('#addInvoiceModal').on('change', '.quantities', function(){
        get_order_total('#addInvoiceModal');
    });
    // on customer change
    $('.customer_id').on('change', function(){
        var user_id = $(this).val();
        fetch_customer(user_id);
        $('.previous_amount').val(customer.outstanding_balance);
        get_order_total('#addInvoiceModal');
    })
    // on payment change
    $('.payment').on('change', function(){
        if($(this).val() == 'credit'){
            $('.amount_pay').val(0);
            $('.amount_pay').prop('readonly', true);
            $('.amount_pay').change();
            return 0;
        }
        $('.amount_pay').prop('readonly', false);
    })
    // on amount_pay change
    $('.amount_pay').on('change', function(){
        $('.balance_due').val(parseInt($('.final_amount').val()) - parseInt($('.amount_pay').val()));
    })

    // autocomplete items only
    function initAutocompleteItems(input, wrapper, source){
        $(input).autocomplete({
            source: source,
            appendTo: wrapper,
            select: function(event, ui) {
                // update current product
                fetch_product(ui.item.value);

                $(this).val(ui.item.label);
                $(this).siblings('input').val(ui.item.value);
                
                // check for special discount
                fetch_by_customer_and_product();
                if(special_discount != 0){
                    $(this).parent().parent().next().find('input').val(special_discount);
                }
                else{
                    if(customer.type == "consumer"){
                        $(this).parent().parent().next().find('input').val(ui.item.consumer_selling_price);
                    }
                    if(customer.type == "retailer" || customer.type == "distributor"){
                        $(this).parent().parent().next().find('input').val(ui.item.retailer_selling_price);
                    }
                }

                return false;
            },
        });
    }

    //Add Items
    $('#detailInvoiceModal').on("click", ".addItem", function(){
    // $('.addItem').on('click', function(){
        $('#detailInvoiceModal').modal('hide');
        $('a[data-id="'+ current_invoice_id +'"]')[1].click();
    });

    // create*
    $('#add_program').on('click', function(){
        var url = $(this).data('route');

        // empty wrapper
        $('.field_wrapper').html('');
        
        // append in wrapper
        $('#addInvoiceModal .field_wrapper').prepend(fieldHTML);

        initAutocompleteItems(".product_search", "#addInvoiceModal .ui-widget", product_labels);

        $('#addInvoiceModal').modal('show');
    });

    //*** delete ***//
    $('.deleteButton').on('click', function(){
        var id = $(this).data('id');
        $('#deleteForm').attr('action', "{{route('invoice.update', 1)}}");
        $('#deleteForm .hidden').val(id);
        
        $('#deleteInvoiceModalLabel').text('Delete Invoice: ' + $('.invoice_id' + id).html() + "?");
        $('#deleteInvoiceModal').modal('show');
    });

    // detail
    $('.detailButton').on('click', function(){
        invoice_id = $(this).data('id');

        // set invoice url for pdf generation
        var temp_route = "{{route('generate_invoice_pdf', ':id')}}";
        temp_route = temp_route.replace(':id', invoice_id);
        $('#invoice_detail_form').attr('action', temp_route);

        fetch_invoice(invoice_id);

        // empty wrapper
        $('#table_row_wrapper').html('');
        // loop over retrieved items
        for(var i = 0; i < invoice.invoice_products.length; i++)
        {
            $('#table_row_wrapper').append(' <tr role="row" class="odd"><td name="invoice_products[]" value="'+invoice.invoice_products[i].id+'" hidden></td><td class="">'+invoice.invoice_products[i].product.category.name+'</td><td class="">'+invoice.invoice_products[i].product.brand.name+'</td><td class="">'+invoice.invoice_products[i].product.article+'</td><td class="">'+invoice.invoice_products[i].quantity+'</td><td class="">'+invoice.invoice_products[i].price+'</td><td class="">'+invoice.invoice_products[i].product.unit.name+'</td></tr>');
        }

        $('#invoice_id').text(invoice.id);
        $('#customer_name').text(invoice.customer.name);
        $('#contact_number').text(invoice.customer.contact_number);
        $('#address').text(invoice.customer.shop_name + ' - Shop # ' + invoice.customer.shop_number + ' - Floor # ' + invoice.customer.floor + ((invoice.customer && invoice.customer.market && invoice.customer.market.area) ? (' - ' + invoice.customer.market.name + ' - ' + invoice.customer.market.area.name) : ''));
        $('#detailTotal').text(invoice.total);
        $('#amount_pay').text(invoice.amount_pay);
        $('#description').text(invoice.description);

        $('#detailInvoiceModal').modal('show');

        // append in table_row_wrapper empty first
        // $('#table_row_wrapper').child('td').remove();

    });

    // edit
    $('.editButton').on('click', function(){
        fetch_invoice($(this).data('id'));
        $('#editOrderModal .hidden').val($(this).data('id'));

        // select customer
        $('#editInvoiceModal .customer_id option[value="'+ invoice.customer_id +'"]').prop('selected', true);
        $('#editInvoiceModal .customer_id').trigger('change.select2'); 
        $('#editInvoiceModal .customer_id').change();
        // $('#editInvoiceModal .customer_id').attr("readonly", true);
        // $('#editInvoiceModal .customer_id').selectmenu();
        // $('#editInvoiceModal .customer_id').selectmenu('refresh', true);
        $('#editInvoiceModal .dispatch_date').val(invoice.dispatch_date);
        
        // remove required attribute on rider_id
        $("#editInvoiceModal .rider_id").prop("required", false);

        // empty wrapper
        $('.field_wrapper').html('');

        for(var i = 0; i < invoice.invoice_products.length; i++){
            var productDiv = '<div class="col-md-4"><div class="ui-widget"><input class="form-control product_search" name="products[]" value="'+ invoice.invoice_products[i].product.article +'"><input class="hidden_product_search" type="hidden" name="hidden_product_ids[]" value="'+ invoice.invoice_products[i].product.id +'"></div></div>';
            var priceDiv = '<div class="form-group col-md-4"><input type="number" class="form-control prices" name="prices[]" required min=0 value="'+ invoice.invoice_products[i].price +'"></div>';
            var quantityDiv = '<div class="form-group col-md-3"><input type="number" class="form-control quantities" name="quantities[]" required min=0  value="'+ invoice.invoice_products[i].quantity +'"></div>';
            var fieldHTML = startDiv + productDiv + priceDiv + quantityDiv + removeChildDiv + endDiv;

            $('.field_wrapper').prepend(fieldHTML);
            x++;
        }

        initAutocompleteItems(".product_search", "#editInvoiceModal .ui-widget", product_labels);

        get_invoice_total('#editInvoiceModal');

        $('#editInvoiceModal').modal('show');
    });

    //Once add button is clicked on create*
    $('#addInvoiceModal').on("click", ".add_button", function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).prepend(fieldHTML); //Add field html

            // initialize autocomplete
            initAutocompleteItems(".product_search", "#addInvoiceModal .ui-widget", product_labels);
        }
    });

    //Once remove button is clicked on create
    $('#addInvoiceModal').on("click", ".remove_button", function(e){
        e.preventDefault();
        if(x > minField){
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
            get_order_total('#addInvoiceModal');
        }
    });

  });

</script>
@endsection('content_body')