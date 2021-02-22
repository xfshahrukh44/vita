@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-users"></i> Vendors</h1>
  </div>
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
          <form action="{{route('generate_vendors_excel')}}" target="_blank" method="post">
            @csrf
            <button type="submit" class="btn btn-success generate_ledgers_excel">
                <i class="fas fa-file-excel"></i>
                Generate Excel
            </button>
            <button class="btn btn-success" id="add_vendor" data-toggle="modal" data-target="#addVendorModal" type="button">
              <i class="fas fa-plus"></i>
              Add New Vendor
            </button>
          </form>
        </div>
        <!-- search bar -->
        <form action="{{route('search_vendors')}}" class="form-wrapper">
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
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Type</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Area</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Channel</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Hub</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Contact #</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Whatsapp #</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Status</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Business to Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Outstanding Balance</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($vendors) > 0)
                @foreach($vendors as $vendor)
                  <tr role="row" class="odd">
                    <td class="{{'name'.$vendor->id}}">{{$vendor->name}}</td>
                    <td class="{{'type'.$vendor->id}}">{{$vendor->type}}</td>
                    <td class="{{'area'.$vendor->id}}">{{$vendor->area ? $vendor->area->name : NULL}}</td>
                    <td class="{{'channel'.$vendor->id}}">{{$vendor->channel ? $vendor->channel->name : NULL}}</td>
                    <td class="{{'hub'.$vendor->id}}">{{$vendor->hub ? $vendor->hub->name : NULL}}</td>
                    <td class="{{'contact_number'.$vendor->id}}">{{$vendor->contact_number ? $vendor->contact_number : NULL}}</td>
                    <td class="{{'whatsapp_number'.$vendor->id}}">{{$vendor->whatsapp_number ? $vendor->whatsapp_number : NULL}}</td>
                    <td class="{{'status'.$vendor->id}}">{{$vendor->status ? $vendor->status : NULL}}</td>
                    <td class="{{'business_to_date'.$vendor->id}}">{{$vendor->business_to_date ? 'Rs. ' . number_format($vendor->business_to_date) : NULL}}</td>
                    <td class="{{'outstanding_balance'.$vendor->id}}">{{$vendor->outstanding_balance ? 'Rs. ' . number_format($vendor->outstanding_balance) : NULL}}</td>
                    <td>
                      <!-- Detail -->
                      <a href="#" class="detailButton" data-id="{{$vendor->id}}" data-object="{{$vendor}}" data-shopkeeper="{{asset('img/shopkeepers') . '/' . $vendor->shop_keeper_picture}}" data-shop="{{asset('img/shops') . '/' . $vendor->shop_picture}}">
                        <i class="fas fa-eye green ml-1"></i>
                      </a>
                      <!-- Edit -->
                      <a href="#" class="editButton" data-id="{{$vendor->id}}" data-object="{{$vendor}}">
                        <i class="fas fa-edit blue ml-1"></i>
                      </a>
                      <!-- Delete -->
                      <a href="#" class="deleteButton" data-id="{{$vendor->id}}" data-object="{{$vendor}}">
                        <i class="fas fa-trash red ml-1"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="9"><h6 align="center">No vendor(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($vendors) > 0)
        {{$vendors->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addVendorModal" tabindex="-1" role="dialog" aria-labelledby="addVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVendorModalLabel">Add New Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('vendor.store')}}" enctype="multipart/form-data">
        @include('admin.vendor.vendor_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editVendorModal" tabindex="-1" role="dialog" aria-labelledby="editVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editVendorModalLabel">Edit Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('vendor.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.vendor.vendor_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Detail view -->
<div class="modal fade" id="viewVendorModal" tabindex="-1" role="dialog" aria-labelledby="addVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vendor Detail</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- TABS -->
            <ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active bci" data-toggle="tab" href="#bci">Basic Vendor Information</a>
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
              <!-- basic vendor info -->
              <div class="tab-pane fade show active" id="bci">
                <div class="card-body">
                  <div class="col-md-12">
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
                                <td class="">Vendor Type</td>
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
                  <div class="col-md-12">
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
                  <div class="col-md-12">
                    <hr style="color:gray;">
                    <table class="table table-bordered table-striped">
                        <tbody id="table_row_wrapper">
                            <tr role="row" class="odd">
                                <td class="">Status</td>
                                <td class="status"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Business to Date</td>
                                <td class="business_to_date"></td>
                            </tr>
                            <tr role="row" class="odd">
                                <td class="">Outstanding Balance</td>
                                <td class="outstanding_balance"></td>
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
<div class="modal fade" id="deleteVendorModal" tabindex="-1" role="dialog" aria-labelledby="deleteVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteVendorModalLabel">Delete Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('vendor.destroy', 1)}}">
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
  var vendor = "";

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
  var productDiv = '<div class="col-md-6 form-group"><select name="products[]" class="form-control products" style="width: 100%; max-height: 20px;"><option value="">Select Product</option>@foreach($products as $product)<option value="{{$product->id}}">{{$product->article}}</option>@endforeach</select></div>';
  var amountDiv = '<div class="form-group col-md-5"><input type="number" class="form-control amounts" name="amounts[]" required min=0></div>';
  var removeChildDiv = '<div class="form-group col-md-0 remove_button ml-1" style="display: table; vertical-align: middle;"><a class="btn btn-primary"><i class="fas fa-minus" style="color:white;"></i></a></div>';
  var endDiv = '</div>';
  var fieldHTML = startDiv + productDiv + amountDiv + removeChildDiv + endDiv;

  // fetch markets by area id
  function fetch_specific_markets(area_id){
    $.ajax({
        url: '<?php echo(route("fetch_specific_markets")); ?>',
        type: 'GET',
        data: {area_id: area_id},
        dataType: 'JSON',
        async: false,
        success: function (data) {
          $('.market_id').html('<option value="">Select market</option>');
          for(var i = 0; i < data.length; i++){
            $('.market_id').append('<option value="'+ data[i].id +'">'+ data[i].name +'</option>');
            $('.market_id').fadeIn(200);
          }
        },
        error: function(data){
          $('.market_id').html('<option value="">Select market</option>');
          $('.market_id').fadeOut(200);
        }
    });
  }

  // fetch vendor
  function fetch_vendor(id){
    $.ajax({
        url: '<?php echo(route("vendor.show", 0)); ?>',
        type: 'GET',
        data: {id: id},
        dataType: 'JSON',
        async: false,
        success: function (data) {
          vendor = data.vendor;
        }
    });
  }

  // create
  $('#add_vendor').on('click', function(){
    $('.market_id').hide();
  });

  // edit
  $('.editButton').on('click', function(){
    $('.market_id').hide();
    var id = $(this).data('id');
    fetch_vendor(id);
    $('#hidden').val(id);
    
    $('#editForm .name').val($('.name' + id).html());
    $('#editForm .contact_number').val($('.contact_number' + id).html());
    $('#editForm .whatsapp_number').val(vendor.whatsapp_number);
    $('#editForm .type').val(vendor.type);

    $('#editForm .shop_name').val(vendor.shop_name);
    $('#editForm .shop_number').val(vendor.shop_number);
    $('#editForm .floor').val(vendor.floor);

    if(vendor.area){
      $('#editForm .area_id option[value="'+ vendor.area.id +'"]').prop('selected', true);
      $('#editForm  .area_id').change();
    }

    if(vendor.channel){
      $('#editForm .channel_id option[value="'+ vendor.channel.id +'"]').prop('selected', true);
      $('#editForm  .channel_id').change();
    }

    if(vendor.hub){
      $('#editForm .hub_id option[value="'+ vendor.hub.id +'"]').prop('selected', true);
      $('#editForm  .hub_id').change();
    }

    $('#editForm .status option[value="'+ vendor.status +'"]').prop('selected', true);
    $('#editForm .visiting_days option[value="'+ vendor.visiting_days +'"]').prop('selected', true);
    $('#editForm .cash_on_delivery option[value="'+ vendor.cash_on_delivery +'"]').prop('selected', true);

    $('#editForm .opening_balance').val(vendor.opening_balance);
    $('#editForm .business_to_date').val(vendor.business_to_date);
    $('#editForm .outstanding_balance').val(vendor.outstanding_balance);
    $('#editForm .special_discount').val(vendor.special_discount);

    $('#editForm .payment_terms').val(vendor.payment_terms);
    
    $('#editVendorModal').modal('show');

  });

  // detail
  $('.detailButton').on('click', function(){
    $('.bci').trigger('click');

    var vendor = $(this).data('object');
    
    $('.name').html(vendor.name);
    $('.contact_number').html(vendor.contact_number);
    $('.whatsapp_number').html(vendor.whatsapp_number);
    if(vendor.shop_keeper_picture){
      var shop_path = $(this).data('shopkeeper');
      $('.shop_keeper_picture').attr('src', shop_path);
    }
    $('.type').html(vendor.type);

    $('.shop_name').html(vendor.shop_name);
    $('.shop_number').html(vendor.shop_number);
    $('.area').html(vendor.area.name);
    $('.channel').html(vendor.channel.name);
    $('.hub').html(vendor.hub.name);

    if(vendor.shop_picture){
      var shop_path = $(this).data('shop');
      $('.shop_picture').attr('src', shop_path);
    }

    $('.status').html(vendor.status);
    $('.business_to_date').html("Rs. " + vendor.business_to_date.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $('.outstanding_balance').html("Rs. " + vendor.outstanding_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));

    $('#viewVendorModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('vendor.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteVendorModalLabel').text('Delete Vendor: ' + $('.name' + id).html() + "?");
    $('#deleteVendorModal').modal('show');
  });

  //on area id change
  $('.area_id').on('change', function(){
    var area_id = $(this).val();
    fetch_specific_markets(area_id);
  });

  //Once add button is clicked on create*
  $('#addVendorModal').on("click", ".add_button", function(){
      //Check maximum number of input fields
      if(x < maxField){ 
          x++; //Increment field counter
          $(wrapper).prepend(fieldHTML); //Add field html
          
          $('.products').select2();
      }
  });

  //Once add button is clicked on edit*
  $('#editVendorModal').on("click", ".add_button", function(){
      //Check maximum number of input fields
      if(x < maxField){ 
          x++; //Increment field counter
          $(wrapper).prepend(fieldHTML); //Add field html

          $('.products').select2();
      }
  });
    
  //Once remove button is clicked*
  $('.modal').on("click", ".remove_button", function(e){
      e.preventDefault();
      if(x > minField){
          $(this).parent('div').remove(); //Remove field html
          x--; //Decrement field counter
          $('.products').select2();
      }
  });

});
</script>
@endsection('content_body')