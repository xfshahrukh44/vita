@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-sign-out-alt"></i> Stock Out</h1>
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
          <button class="btn btn-success" id="add_stockOut" data-toggle="modal" data-target="#addStockOutModal">
            <i class="fas fa-plus"></i> Add New StockOut</button>
        </div>
        <!-- search bar -->
        <form action="{{route('search_stockOuts')}}" class="form-wrapper">
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
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Customer</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Product</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Quantity</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Transaction Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Created By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Modified By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($stockOuts) > 0)
                @foreach($stockOuts as $stockOut)
                  <tr role="row" class="odd">
                    <td class="{{'customer_id'.$stockOut->id}}">{{$stockOut->customer ? $stockOut->customer->name : NULL}}</td>
                    <td class="{{'product_id'.$stockOut->id}}">{{$stockOut->product ? $stockOut->product->category->name . ' - ' . $stockOut->product->brand->name . ' - ' . $stockOut->product->article : NULL}}</td>
                    <td class="{{'quantity'.$stockOut->id}}">{{$stockOut->quantity}}</td>
                    <td class="{{'transaction_date'.$stockOut->id}}">{{return_date($stockOut->transaction_date)}}</td>
                    <td class="{{'created_by'.$stockOut->id}}">{{return_user_name($stockOut->created_by)}}</td>
                    <td class="{{'modified_by'.$stockOut->id}}">{{return_user_name($stockOut->modified_by)}}</td>
                    <td>
                      <!-- Edit -->
                      <a href="#" class="editButton" data-id="{{$stockOut->id}}" data-object="{{$stockOut}}">
                        <i class="fas fa-edit blue ml-1"></i>
                      </a>
                      <!-- Delete -->
                      <a href="#" class="deleteButton" data-id="{{$stockOut->id}}" data-object="{{$stockOut}}">
                        <i class="fas fa-trash red ml-1"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="7"><h6 align="center">No stockOut(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($stockOuts) > 0)
        {{$stockOuts->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addStockOutModal" tabindex="-1" role="dialog" aria-labelledby="addStockOutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStockOutModalLabel">Add New Stock Out</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('stock_out.store')}}" enctype="multipart/form-data">
        @include('admin.stockOut.stockOut_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editStockOutModal" tabindex="-1" role="dialog" aria-labelledby="editStockOutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStockOutModalLabel">Edit Stock Out</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('stock_out.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.stockOut.stockOut_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteStockOutModal" tabindex="-1" role="dialog" aria-labelledby="deleteStockOutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteStockOutModalLabel">Delete Stock Out</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('stock_out.destroy', 1)}}">
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
  // hide expense type element on document ready
  $('.expense_type_wrapper').fadeOut();
  $('.narration_wrapper').fadeOut();

  // create
  $('#add_stockOut').on('click', function(){
    
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    var stockOut = $(this).data('object');
    $('#hidden').val(id);

    $('#editForm #customer_id option[value="'+ stockOut.customer_id +'"]').prop('selected', true);
    $('#editForm #product_id option[value="'+ stockOut.product_id +'"]').prop('selected', true);
    $('#editForm #quantity').val(stockOut.quantity);
    $('#editForm #transaction_date').val(stockOut.transaction_date);
    
    $('#editStockOutModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('stock_out.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteStockOutModalLabel').text('Delete Stock Out?');
    $('#deleteStockOutModal').modal('show');
  });

  // on is_adjustment change
  $('.is_adjustment').on('click', function(){
    if($(this).is(':checked')){
      $('.expense_type_wrapper').fadeIn(200);
      $('.narration_wrapper').fadeIn(200);
    }
    else{
      $('.expense_type_wrapper').fadeOut(200);
      $('.narration_wrapper').fadeOut(200);
      $('.expense_type').val("");
      $('.narration').val("");
    }
  });

});
</script>
@endsection('content_body')