@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="fas fa-book nav-icon"></i> Payments</h1>
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
          <button class="btn btn-success" id="add_payment" data-toggle="modal" data-target="#addPaymentModal">
            <i class="fas fa-plus"></i> Add New Payment</button>
        </div>
        <!-- search bar -->
        <form action="{{route('search_payments')}}" class="form-wrapper">
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
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Vendor</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Amount</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Created By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Modified By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($payments) > 0)
                @foreach($payments as $payment)
                  <tr role="row" class="odd">
                    <td class="{{'vendor_id'.$payment->id}}">{{$payment->vendor ? $payment->vendor->name : NULL}}</td>
                    <td class="{{'amount'.$payment->id}}">{{$payment->amount ? 'Rs.' . number_format($payment->amount) : NULL}}</td>
                    <td class="{{'created_at'.$payment->id}}">{{$payment->created_at ? $payment->created_at : NULL}}</td>
                    <td class="{{'created_by'.$payment->id}}">{{return_user_name($payment->created_by)}}</td>
                    <td class="{{'modified_by'.$payment->id}}">{{return_user_name($payment->modified_by)}}</td>
                    <td>
                      <!-- Edit -->
                      <a href="#" class="editButton" data-id="{{$payment->id}}">
                        <i class="fas fa-edit blue ml-1"></i>
                      </a>
                      <!-- Delete -->
                      <a href="#" class="deleteButton" data-id="{{$payment->id}}">
                        <i class="fas fa-trash red ml-1"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="6"><h6 align="center">No payment(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($payments) > 0)
        {{$payments->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPaymentModalLabel">Add New Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('payment.store')}}" enctype="multipart/form-data">
        @include('admin.payment.payment_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('payment.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.payment.payment_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('payment.destroy', 1)}}">
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

  // fetch vendor
  function fetch_vendor(id){
    // fetch vendor
    $.ajax({
        url: "<?php echo(route('vendor.show', 1)); ?>",
        type: 'GET',
        async: false,
        data: {id: id},
        dataType: 'JSON',
        success: function (data) {
            vendor = data.vendor;
        }
    });
  }

  // on change functions
  // on vendor number selection
  $('.vendor_id').on('change', function(){
    fetch_vendor($(this).val());
    $('.outstanding_balance').html(vendor.outstanding_balance);
  });

  // create
  $('#add_payment').on('click', function(){
    
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    var payment = $(this).data('object');
    $('#hidden').val(id);

    $('#editForm #customer_id option[value="'+ payment.customer_id +'"]').prop('selected', true);
    $('#editForm #amount').val(payment.amount);
    $('#editForm #type option[value="'+ payment.type +'"]').prop('selected', true);
    $('#editForm #transaction_date').val(payment.transaction_date);
    
    $('#editPaymentModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('payment.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deletePaymentModalLabel').text('Delete Payment?');
    $('#deletePaymentModal').modal('show');
  });

});
</script>
@endsection('content_body')