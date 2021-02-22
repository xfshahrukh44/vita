@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-sign-in-alt"></i> Stock In</h1>
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
          <button class="btn btn-success" id="add_stockIn" data-toggle="modal" data-target="#addStockInModal">
            <i class="fas fa-plus"></i> Add New StockIn</button>
        </div>
        <!-- search bar -->
        <form action="{{route('search_stockIns')}}" class="form-wrapper">
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
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Product</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Quantity</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Rate</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Amount</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Vendor</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Transaction Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Created By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Modified By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($stockIns) > 0)
                @foreach($stockIns as $stockIn)
                  <tr role="row" class="odd">
                    <td class="{{'product_id'.$stockIn->id}}">{{$stockIn->product ? $stockIn->product->category->name . ' - ' . $stockIn->product->brand->name . ' - ' . $stockIn->product->article : NULL}}</td>
                    <td class="{{'quantity'.$stockIn->id}}">{{$stockIn->quantity ? $stockIn->quantity : NULL}}</td>
                    <td class="{{'rate'.$stockIn->id}}">{{$stockIn->rate ? 'Rs. ' . number_format($stockIn->rate, 2) : NULL}}</td>
                    <td class="{{'amount'.$stockIn->id}}">{{$stockIn->amount ? 'Rs. ' . number_format($stockIn->amount) : NULL}}</td>
                    <td class="{{'vendor'.$stockIn->id}}">{{$stockIn->vendor ? $stockIn->vendor->name : NULL}}</td>
                    <td class="{{'transaction_date'.$stockIn->id}}">{{return_date($stockIn->created_at)}}</td>
                    <td class="{{'created_by'.$stockIn->id}}">{{return_user_name($stockIn->created_by)}}</td>
                    <td class="{{'modified_by'.$stockIn->id}}">{{return_user_name($stockIn->modified_by)}}</td>
                    <td>
                      <!-- Edit -->
                      <a href="#" class="editButton" data-id="{{$stockIn->id}}" data-object="{{$stockIn}}">
                        <i class="fas fa-edit blue ml-1"></i>
                      </a>
                      <!-- Delete -->
                      <a href="#" class="deleteButton" data-id="{{$stockIn->id}}" data-object="{{$stockIn}}">
                        <i class="fas fa-trash red ml-1"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="9"><h6 align="center">No stockIn(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($stockIns) > 0)
        {{$stockIns->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addStockInModal" tabindex="-1" role="dialog" aria-labelledby="addStockInModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStockInModalLabel">Add New Stock In</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('stock_in.store')}}" enctype="multipart/form-data">
        @include('admin.stockIn.stockIn_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editStockInModal" tabindex="-1" role="dialog" aria-labelledby="editStockInModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStockInModalLabel">Edit Stock In</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('stock_in.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.stockIn.stockIn_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteStockInModal" tabindex="-1" role="dialog" aria-labelledby="deleteStockInModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteStockInModalLabel">Delete Stock In</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('stock_in.destroy', 1)}}">
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

  // create
  $('#add_stockIn').on('click', function(){
    
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    var stockIn = $(this).data('object');
    $('#hidden').val(id);

    $('#editForm #product_id option[value="'+ stockIn.product_id +'"]').prop('selected', true);
    $('#editForm #vendor_id option[value="'+ stockIn.vendor_id +'"]').prop('selected', true);
    $('#editForm #quantity').val(stockIn.quantity);
    $('#editForm #rate').val(stockIn.rate);
    $('#editForm #amount').val(stockIn.amount);
    $('#editForm #transaction_date').val(stockIn.transaction_date);
    
    $('#editStockInModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('stock_in.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteStockInModalLabel').text('Delete Stock In?');
    $('#deleteStockInModal').modal('show');
  });

  // on quantity change edit
  $('#editStockInModal .quantity').on('change', function(){
    $('#editStockInModal .amount').val($('#editStockInModal .quantity').val() * $('#editStockInModal .rate').val());
  });

  // on rate change edit
  $('#editStockInModal .rate').on('change', function(){
    $('#editStockInModal .amount').val($('#editStockInModal .quantity').val() * $('#editStockInModal .rate').val());
  });

  // on quantity change create
  $('#addStockInModal .quantity').on('change', function(){
    $('#addStockInModal .amount').val($('#addStockInModal .quantity').val() * $('#addStockInModal .rate').val());
  });

  // on rate change create
  $('#addStockInModal .rate').on('change', function(){
    $('#addStockInModal .amount').val($('#addStockInModal .quantity').val() * $('#addStockInModal .rate').val());
  });

});
</script>
@endsection('content_body')