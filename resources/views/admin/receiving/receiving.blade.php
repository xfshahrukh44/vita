@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="fas fa-book nav-icon"></i> Receipts</h1>
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
          <button class="btn btn-success" id="add_receiving" data-toggle="modal" data-target="#addReceivingModal">
            <i class="fas fa-plus"></i> Add New Receipt</button>
        </div>
        <!-- search bar -->
        <form action="{{route('search_receivings')}}" class="form-wrapper">
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
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Invoice ID</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Order ID</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Customer Name</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Invoice Total</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Payment Received</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Created By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Modified By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($receivings) > 0)
                @foreach($receivings as $receiving)
                  <tr role="row" class="odd">
                    <td class="{{'invoice_id'.$receiving->id}}">{{$receiving->invoice ? $receiving->invoice->id : NULL}}</td>
                    <td class="{{'order_id'.$receiving->id}}">{{$receiving->invoice && $receiving->invoice->order ? $receiving->invoice->order->id : NULL}}</td>
                    <td class="{{'customer'.$receiving->id}}">{{$receiving->customer? $receiving->customer->name : NULL}}</td>
                    <td class="{{'total'.$receiving->id}}">{{$receiving->invoice ? 'Rs.' . number_format($receiving->invoice->total) : NULL}}</td>
                    <td class="{{'amount'.$receiving->id}}">{{$receiving->amount ? 'Rs.' . number_format($receiving->amount) : NULL}}</td>
                    <td class="{{'created_by'.$receiving->id}}">{{return_user_name($receiving->created_by)}}</td>
                    <td class="{{'modified_by'.$receiving->id}}">{{return_user_name($receiving->modified_by)}}</td>
                    <td>
                      @can('isSuperAdmin')
                        <!-- Edit -->
                        <a href="#" class="editButton" data-id="{{$receiving->id}}">
                          <i class="fas fa-edit blue ml-1"></i>
                        </a>
                        <!-- Delete -->
                        <a href="#" class="deleteButton" data-id="{{$receiving->id}}">
                          <i class="fas fa-trash red ml-1"></i>
                        </a>
                      @endcan
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="8"><h6 align="center">No receiving(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($receivings) > 0)
        {{$receivings->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addReceivingModal" tabindex="-1" role="dialog" aria-labelledby="addReceivingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addReceivingModalLabel">Add New Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('receiving.store')}}" enctype="multipart/form-data">
        @include('admin.receiving.receiving_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editReceivingModal" tabindex="-1" role="dialog" aria-labelledby="editReceivingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editReceivingModalLabel">Edit Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('receiving.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden" class="hidden">
        @include('admin.receiving.receiving_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteReceivingModal" tabindex="-1" role="dialog" aria-labelledby="deleteReceivingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteReceivingModalLabel">Delete Receipt</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('receiving.destroy', 1)}}">
        <!-- hidden input -->
        @method('DELETE')
        @csrf
        <input class="hidden" type="hidden" name="hidden" class="hidden">
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
  var invoice = "";
  var receiving = "";
  var customer = "";

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
            if(data.success == true){
              invoice = data.invoice;
              $('.payment_date_wrapper').removeAttr('hidden');
            }
            else{
              $('.payment_date_wrapper').prop('hidden', true);
              invoice = "";
            }
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
        },
        error: function(data){
          customer = "";
        }
    });
  }

  // fetch receiving
  function fetch_receiving(id){
    // fetch receiving
    $.ajax({
        url: "<?php echo(route('receiving.show', 1)); ?>",
        type: 'GET',
        async: false,
        data: {receiving_id: id},
        dataType: 'JSON',
        success: function (data) {
            receiving = data.receiving;
        }
    });
  }

  // on change functions
  // on invoice number selection
  $('.invoice_id').on('change', function(){
    fetch_invoice($(this).val());
    if(invoice){
      $('.order_id').html(invoice.order.id);
      $('.customer').html(invoice.order.customer.name);
      $('.outstanding_balance').html(invoice.order.customer.outstanding_balance);
      $('.total').html(invoice.total);
      $('.amount_pay').html(invoice.amount_pay);
      $('.invoice_due').html(invoice.total - invoice.amount_pay);
    }
  });
  // on customer change
  $('.customer_id').on('change', function(){
    fetch_customer($(this).val());

    // if customer found and invoices found
    if(customer && customer.invoices.length > 0){
      // empty wrapper
      $('.invoice_id').html('<option value="">Select invoice</option>');

      // append invoice items
      for(var i = (customer.invoices.length - 1); i >= 0; i--){
        $('.invoice_id').append('<option value="'+customer.invoices[i].id+'">Invoice # '+customer.invoices[i].id+', Items: '+customer.invoices[i].invoice_products.length+', Total: Rs.'+customer.invoices[i].total+'</option>');
      }
      
      // init select2
      $('.invoice_id').select2();
      $('.invoice_id').change ();
    }
    // else
    else{
      // empty wrapper
      $('.invoice_id').html('<option value="">Select invoice</option>');
      // init select2
      $('.invoice_id').select2();
      $('.invoice_id').change ();
    }
  });

  // create
  $('#add_receiving').on('click', function(){
    $('#addReceivingModal .invoice_id').select2();
    $('#addReceivingModal .customer_id').select2();
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    fetch_receiving(id);
    
    $('#editForm .hidden').val(id);

    // if customer_id
    if(receiving.customer_id){
      $('#editForm .customer_id option[value="'+ receiving.customer_id +'"]').prop('selected', true);
      $('#editForm .customer_id').change();
    }
    // if invoice_id
    if(receiving.invoice_id){
      $('#editForm .invoice_id option[value="'+ receiving.invoice_id +'"]').prop('selected', true);
      $('#editForm .invoice_id').change();
    }

    // amount
    $('#editForm .amount').val(receiving.amount);


    $('#editReceivingModal .invoice_id').select2();
    $('#editReceivingModal .customer_id').select2();
    $('#editReceivingModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('receiving.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteReceivingModalLabel').text('Delete Receipt?');
    $('#deleteReceivingModal').modal('show');
  });

});
</script>
@endsection('content_body')