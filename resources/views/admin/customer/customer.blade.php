@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-users"></i> Customers</h1>
  </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

@endsection

@section('content_body')
<!-- Index view -->
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <!-- generate excel -->
          <form action="{{route('generate_customers_excel')}}" target="_blank" method="post">
            @csrf
            <button type="submit" class="btn btn-success generate_ledgers_excel">
                <i class="fas fa-file-excel"></i>
                Generate Excel
            </button>
            <button class="btn btn-success" type="button" id="add_customer" data-toggle="modal" data-target="#addCustomerModal">
              <i class="fas fa-plus"></i>
              Add New Customer
            </button>
          </form>
        </div>
        <!-- search bar -->
        <form action="{{route('search_customers')}}" class="form-wrapper">
          <div class="row">
              <!-- search bar -->
              <div class="topnav col-md-4 col-sm-4">
                <input name="query" class="form-control" id="search_content" type="text" placeholder="Search..">
              </div>
              <!-- search button-->
              <button type="submit" class="btn btn-primary col-md-0 col-sm-0 justify-content-start" id="search_button">
                <i class="fas fa-search"></i>
              </button>
          </div>
        </form>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="col-md-12 col-sm-12" style="overflow-x:auto;">
          <table id="example1" class="table table-bordered table-striped dataTable dtr-inline " role="grid" aria-describedby="example1_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Type</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Area</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Channel</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Hub</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Contact #</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Business to Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Outstanding Balance</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($customers) > 0)
                @foreach($customers as $customer)
                  <tr role="row" class="odd">
                    <td class="{{'name'.$customer->id}}">{{$customer->name}}</td>
                    <td class="{{'type'.$customer->id}}">{{$customer->type}}</td>
                    <td class="{{'area'.$customer->id}}">{{$customer->area ? $customer->area->name : NULL}}</td>
                    <td class="{{'channel'.$customer->id}}">{{$customer->channel ? $customer->channel->name : NULL}}</td>
                    <td class="{{'hub'.$customer->id}}">{{$customer->hub ? $customer->hub->name : NULL}}</td>
                    <td class="{{'contact_number'.$customer->id}}">{{$customer->contact_number ? $customer->contact_number : NULL}}</td>
                    <td class="{{'business_to_date'.$customer->id}}">{{$customer->business_to_date ? 'Rs. ' . number_format($customer->business_to_date) : NULL}}</td>
                    <td class="{{'outstanding_balance'.$customer->id}}">{{$customer->outstanding_balance ? 'Rs. ' . number_format($customer->outstanding_balance) : NULL}}</td>
                    <td>
                      <!-- Detail -->
                      <a href="#" class="detailButton" data-id="{{$customer->id}}" data-object="{{$customer}}" data-shopkeeper="{{asset('img/shopkeepers') . '/' . $customer->shop_keeper_picture}}" data-shop="{{asset('img/shops') . '/' . $customer->shop_picture}}">
                        <i class="fas fa-eye green ml-1"></i>
                      </a>
                      @can('isSuperAdmin')
                        <!-- Edit -->
                        <a href="#" class="editButton" data-id="{{$customer->id}}" data-object="{{$customer}}">
                          <i class="fas fa-edit blue ml-1"></i>
                        </a>
                        <!-- Delete -->
                        <a href="#" class="deleteButton" data-id="{{$customer->id}}" data-object="{{$customer}}">
                          <i class="fas fa-trash red ml-1"></i>
                        </a>
                      @endcan
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="8"><h6 align="center">No customer(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($customers) > 0)
        {{$customers->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('customer.store')}}" enctype="multipart/form-data">
        @include('admin.customer.customer_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('customer.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.customer.customer_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Detail view -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Detail</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- TABS -->
            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active bci" data-toggle="tab" href="#bci">Basic Customer Information</a>
              </li> 
              <li class="nav-item" role="presentation" >
                <a class="nav-link" data-toggle="tab" href="#si">Shop Information</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-toggle="tab" href="#pi">Payment Information</a>
              </li>
            </ul>
            
            <!-- TAB CONTENT -->
            <div class="tab-content" id="myTabContent">
              <!-- basic customer info -->
              <div class="tab-pane fade show active" id="bci">
                <div class="card-body">
                  <div class="col-md-12 col-sm-12">
                    <img class="shop_keeper_picture" src="{{asset('img/logo.png')}}" width="150">
                    <hr style="color:gray;">
                    <table class="table table-bordered table-striped">
                        <tbody id="table_row_wrapper">
                            <tr role="row" class="odd">
                                <td class="">Name</td>
                                <td class="name"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Contact #</td>
                                <td class="contact_number"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Whatsapp #</td>
                                <td class="whatsapp_number"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Customer Type</td>
                                <td class="type"></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- Shop info -->
              <div class="tab-pane fade" id="si">
                <div class="card-body">
                  <div class="col-md-12 col-sm-12">
                    <img class="shop_picture" src="{{asset('img/logo.png')}}" width="150">
                    <hr style="color:gray;">
                    <table class="table table-bordered table-striped">
                        <tbody id="table_row_wrapper">
                            <tr role="row" class="odd">
                                <td class="">Shop Name</td>
                                <td class="shop_name"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Shop #</td>
                                <td class="shop_number"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Floor #</td>
                                <td class="floor"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Area</td>
                                <td class="area"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Channel</td>
                                <td class="channel"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Hub</td>
                                <td class="hub"></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- Payment info -->
              <div class="tab-pane fade" id="pi">
                <div class="card-body">
                  <div class="col-md-12 col-sm-12">
                    <hr style="color:gray;">
                    <table class="table table-bordered table-striped">
                        <tbody id="table_row_wrapper">
                            <tr role="row" class="odd">
                                <td class="">Status</td>
                                <td class="status"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Visting Days</td>
                                <td class="visiting_days"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Cash on Delivery</td>
                                <td class="cash_on_delivery"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Opening Balance</td>
                                <td class="opening_balance"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Business to Date</td>
                                <td class="business_to_date"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Outstanding Balance</td>
                                <td class="outstanding_balance"></td>
                            </tr>
                            <tr role="row" class="odd" hidden>
                                <td class="">Special Discount</td>
                                <td class="special_discount"></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-primary" data-dismiss="modal" style="float: right;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCustomerModalLabel">Delete Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('customer.destroy', 1)}}">
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

<script>
$(document).ready(function(){
  // $('#area_id').select2();
  // $('#market_id').select2();
  // datatable
  // $('#example1').DataTable();
  // $('#example1').dataTable({
  //   "bPaginate": false,
  //   "bLengthChange": false,
  //   "bFilter": true,
  //   "bInfo": false,
  //   "searching":false
  // });

  // persistent active sidebar
  var element = $('li a[href*="'+ window.location.pathname +'"]');
  element.parent().parent().parent().addClass('menu-open');
  element.addClass('active');

  // global vars
  var customer = "";

  // on ready function calls
  $('.products').select2();

  // adding items dynamically*
  var x = 1; //Initial field counter is 1
  var maxField = 40; //Input fields increment limitation
  var addButton = $('.add_button'); //Add button selector
  var minField = 1; //Input fields decrement limitation
  var removeButton = $('.remove_button'); //Remove button selector
  var wrapper = $('.field_wrapper'); //Input field wrappervar x = 1; //Initial field counter is 1

  // div strings
  var startDiv = '<div class="row">';
  var productDiv = '<div class="col-md-6 col-sm-6 col-sm-6 form-group"><select name="products[]" class="form-control products" style="width: 100%; max-height: 20px;"><option value="">Select Product</option>@foreach($products as $product)<option value="{{$product->id}}">{{$product->article}}</option>@endforeach</select></div>';
  var amountDiv = '<div class="form-group col-md-5 col-sm-5 col-sm-5"><input type="number" class="form-control amounts" name="amounts[]" min=0></div>';
  var removeChildDiv = '<div class="form-group col-md-0 col-sm-0 col-sm-0 remove_button ml-1" style="display: table; vertical-align: middle;"><a class="btn btn-primary"><i class="fas fa-minus" style="color:white;"></i></a></div>';
  var endDiv = '</div>';
  var fieldHTML = startDiv + productDiv + amountDiv + removeChildDiv + endDiv;

  // fetch customer
  function fetch_customer(id){
    $.ajax({
        url: '<?php echo(route("customer.show", 0)); ?>',
        type: 'GET',
        data: {id: id},
        dataType: 'JSON',
        async: false,
        success: function (data) {
          customer = data.customer;
        }
    });
  }

  // create
  $('#add_customer').on('click', function(){
    
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    fetch_customer(id);
    // console.log(customer);
    $('#hidden').val(id);
    
    $('#editForm .name').val(customer.name ? customer.name : '');
    $('#editForm .contact_number').val(customer.contact_number ? customer.contact_number : '');
    $('#editForm .whatsapp_number').val(customer.whatsapp_number ? customer.whatsapp_number : '');
    $('#editForm .type').val(customer.type ? customer.type : '');

    $('#editForm .shop_name').val(customer.shop_name ? customer.shop_name : '');
    $('#editForm .shop_number').val(customer.shop_number ? ustomer.shop_number : '');
    $('#editForm .floor').val(customer.floor ? customer.floor : '');

    if(customer.area){
      $('#editForm .area_id option[value="'+ customer.area.id +'"]').prop('selected', true);
      $('#editForm  .area_id').change();
    }

    if(customer.channel){
      $('#editForm .channel_id option[value="'+ customer.channel.id +'"]').prop('selected', true);
      $('#editForm  .channel_id').change();
    }

    if(customer.hub){
      $('#editForm .hub_id option[value="'+ customer.hub.id +'"]').prop('selected', true);
      $('#editForm  .hub_id').change();
    }

    $('#editForm .status option[value="'+ customer.status +'"]').prop('selected', true);
    $('#editForm .visiting_days option[value="'+ customer.visiting_days +'"]').prop('selected', true);
    $('#editForm .cash_on_delivery option[value="'+ customer.cash_on_delivery +'"]').prop('selected', true);

    $('#editForm .opening_balance').val(customer.opening_balance ? customer.opening_balance : '');
    $('#editForm .business_to_date').val(customer.business_to_date ? customer.business_to_date : '');
    $('#editForm .outstanding_balance').val(customer.outstanding_balance ? customer.outstanding_balance : '');
    $('#editForm .special_discount').val(customer.special_discount ? customer.special_discount : '');

    $('#editForm .payment_terms').val(customer.payment_terms ? customer.payment_terms : '');

    // children work
    if(customer.special_discounts.length > 0){
      $('.field_wrapper').html('');
      x = 0;
      for(var i = 0; i < customer.special_discounts.length; i++){
        $('.field_wrapper').prepend(fieldHTML);
        $('#editCustomerModal .products:first option[value="'+ customer.special_discounts[i].product_id +'"]').prop('selected', true);
        $('#editCustomerModal .amounts:first').val(customer.special_discounts[i].amount);
        x++;
        $('.products').select2();
      }
    }

    $('#editCustomerModal').modal('show');

  });

  // detail
  $('.detailButton').on('click', function(){
    $('.bci').trigger('click');
    var id = $(this).data('id');
    fetch_customer(id);

    $('.name').html(customer.name ? customer.name : '');
    $('.contact_number').html(customer.contact_number ? customer.contact_number : '');
    $('.whatsapp_number').html(customer.whatsapp_number ? customer.whatsapp_number : '');
    if(customer.shop_keeper_picture){
      var shop_path = $(this).data('shopkeeper');
      $('.shop_keeper_picture').attr('src', shop_path);
    }
    $('.type').html(customer.type ? customer.type : '');

    $('.shop_name').html(customer.shop_name ? customer.shop_name : '');
    $('.shop_number').html(customer.shop_number ? customer.shop_number : '');
    $('.floor').html(customer.floor ? customer.floor : '');
    $('.area').html(customer.area ? customer.area.name : '');
    $('.channel').html(customer.channel ? customer.channel.name : '');
    $('.hub').html(customer.hub ? customer.hub.name : '');

    if(customer.shop_picture){
      var shop_path = $(this).data('shop');
      $('.shop_picture').attr('src', shop_path);
    }

    $('.status').html(customer.status ? customer.status : '');
    $('.visiting_days').html(customer.visiting_days ? customer.visiting_days : '');
    $('.cash_on_delivery').html(customer.cash_on_delivery ? customer.cash_on_delivery : '');
    $('.opening_balance').html("Rs. " + (customer.opening_balance ? (customer.opening_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : ''));
    $('.business_to_date').html("Rs. " + (customer.business_to_date ? (customer.business_to_date.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : ''));
    $('.outstanding_balance').html("Rs. " + (customer.outstanding_balance ? (customer.outstanding_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")) : ''));
    $('.special_discount').html(customer.special_discount ? ("Rs. " + customer.special_discount) : '');

    $('#viewCustomerModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('customer.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteCustomerModalLabel').text('Delete Customer: ' + $('.name' + id).html() + "?");
    $('#deleteCustomerModal').modal('show');
  });

  //Once add button is clicked on create*
  $('#addCustomerModal').on("click", ".add_button", function(){
      //Check maximum number of input fields
      if(x < maxField){ 
          x++; //Increment field counter
          $(wrapper).prepend(fieldHTML); //Add field html
          $('.products').select2();
      }
  });

  //Once add button is clicked on edit*
  $('#editCustomerModal').on("click", ".add_button", function(){
      //Check maximum number of input fields
      if(x < maxField){ 
          x++; //Increment field counter
          $(wrapper).prepend(fieldHTML); //Add field html
          $('.products').select2();
      }
  });
    
  //Once remove button is clicked on create*
  $('#addCustomerModal').on("click", ".remove_button", function(e){
      // e.preventDefault();
      if(x > minField){
          $(this).parent('div').remove(); //Remove field html
          x--; //Decrement field counter
          $('#addCustomerModal .products').select2();
      }
  });

  //Once remove button is clicked*
  $('#editCustomerModal').on("click", ".remove_button", function(e){
      // e.preventDefault();
      if(x > minField){
          $(this).parent('div').remove(); //Remove field html
          x--; //Decrement field counter
          $('#editCustomerModal .products').select2();
      }
  });

});
</script>
@endsection('content_body')