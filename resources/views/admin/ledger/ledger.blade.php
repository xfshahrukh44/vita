@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark">
      <i class="fas fa-book nav-icon"></i>
      @if($client_type == 'customer')
        Customer Ledgers
      @else
        Vendor Ledgers
      @endif
    </h1>
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
          <button class="btn btn-success" id="add_ledger" data-toggle="modal" data-target="#addLedgerModal">
            <i class="fas fa-plus"></i> Add New Ledger</button>
        </div>
        <!-- search bar -->
        @if($client_type == 'customer')
        <form action="{{route('search_customer_ledgers')}}" class="form-wrapper">
        @else
        <form action="{{route('search_vendor_ledgers')}}" class="form-wrapper">
        @endif
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
                @if($client_type == 'customer')
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Type</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Area</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Market</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Contact #</th>
                @else
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Type</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Area</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Contact #</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Whatsapp #</th>
                  <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Pre-defined sizes: activate to sort column ascending">Status</th>
                @endif
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Business to Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Outstanding Balance</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($ledgers) > 0)
                @foreach($ledgers as $ledger)
                  <tr role="row" class="odd">
                    @if($client_type == 'customer')
                      <td class="{{'name'.$ledger->id}}">{{$ledger->name}}</td>
                      <td class="{{'type'.$ledger->id}}">{{$ledger->type}}</td>
                      <td class="{{'area'.$ledger->id}}">{{$ledger->market && $ledger->market->area ? $ledger->market->area->name : NULL}}</td>
                      <td class="{{'market'.$ledger->id}}">{{$ledger->market ? $ledger->market->name : NULL}}</td>
                      <td class="{{'contact_number'.$ledger->id}}">{{$ledger->contact_number ? $ledger->contact_number : NULL}}</td>
                    @else
                      <td class="{{'name'.$ledger->id}}">{{$ledger->name}}</td>
                      <td class="{{'type'.$ledger->id}}">{{$ledger->type}}</td>
                      <td class="{{'area'.$ledger->id}}">{{$ledger->area ? $ledger->area->name : NULL}}</td>
                      <td class="{{'contact_number'.$ledger->id}}">{{$ledger->contact_number ? $ledger->contact_number : NULL}}</td>
                      <td class="{{'whatsapp_number'.$ledger->id}}">{{$ledger->whatsapp_number ? $ledger->whatsapp_number : NULL}}</td>
                      <td class="{{'status'.$ledger->id}}">{{$ledger->status ? $ledger->status : NULL}}</td>
                    @endif
                    <td class="{{'business_to_date'.$ledger->id}}">{{$ledger->business_to_date ? 'Rs. ' . number_format($ledger->business_to_date) : NULL}}</td>
                    <td class="{{'outstanding_balance'.$ledger->id}}">{{$ledger->outstanding_balance ? 'Rs. ' . number_format($ledger->outstanding_balance) : NULL}}</td>
                    <td>
                      <!-- View Ledgers -->
                      <a href="#" class="ledgerButton" data-id="{{$ledger->id}}" data-type="{{$client_type}}">
                        <i class="fas fa-book nav-icon"></i> View Ledger
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                @if($client_type == 'customer')
                  <tr><td colspan="8"><h6 align="center">No ledger(s) found</h6></td></tr>
                @else
                  <tr><td colspan="9"><h6 align="center">No ledger(s) found</h6></td></tr>
                @endif
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($ledgers) > 0)
        {{$ledgers->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addLedgerModal" tabindex="-1" role="dialog" aria-labelledby="addLedgerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addLedgerModalLabel">Add New Ledger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('ledger.store')}}" enctype="multipart/form-data">
        @include('admin.ledger.ledger_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editLedgerModal" tabindex="-1" role="dialog" aria-labelledby="editLedgerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLedgerModalLabel">Edit Ledger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('ledger.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.ledger.ledger_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteLedgerModal" tabindex="-1" role="dialog" aria-labelledby="deleteLedgerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteLedgerModalLabel">Delete Ledger</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('ledger.destroy', 1)}}">
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

<!-- Ledger view -->
<div class="modal fade" id="detailLedgerModal" tabindex="-1" role="dialog" aria-labelledby="detailLedgerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header row">
        <h5 class="modal-title" id="detailLedgerModalLabel">Ledger</h5>
        <!-- generate excel -->
        <div class="text-right">
            <button type="button" class="btn btn-success generate_ledgers_excel">
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
              <td class="text-bold">Outstanding Balance</td>
              <td class="detail_outstanding_balance"></td>
              <td></td>
            </tr>
            <!-- headers -->
            <tr>
              <th>Transaction Date</th>
              <th>Details</th>
              <th>Amount</th>
              <th>Type</th>
            </tr>
          </thead>
          <tbody class="ledger_wrapper">
            <tr class="table-danger">
              <td>12.12.12</td>
              <td>444</td>
              <td>debit</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- dummy form -->
<form id="dummy_form" action="{{route('generate_ledgers_excel')}}" method="POST" target="_blank" hidden>
    @csrf
</form>

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
  var client = "";

  // fetch vendor
  function fetch_vendor(id){
    $.ajax({
        url: '<?php echo(route("vendor.show", 0)); ?>',
        type: 'GET',
        data: {id: id},
        dataType: 'JSON',
        async: false,
        success: function (data) {
          client = data.vendor;
        }
    });
  }

  // fetch customer
  function fetch_customer(id){
    $.ajax({
        url: '<?php echo(route("customer.show", 0)); ?>',
        type: 'GET',
        data: {id: id},
        dataType: 'JSON',
        async: false,
        success: function (data) {
          client = data.customer;
        }
    });
  }

  // create
  $('#add_ledger').on('click', function(){
    
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    var ledger = $(this).data('object');
    $('#hidden').val(id);

    $('#editForm #customer_id option[value="'+ ledger.customer_id +'"]').prop('selected', true);
    $('#editForm #amount').val(ledger.amount);
    $('#editForm #type option[value="'+ ledger.type +'"]').prop('selected', true);
    $('#editForm #transaction_date').val(ledger.transaction_date);
    
    $('#editLedgerModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('ledger.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteLedgerModalLabel').text('Delete Ledger?');
    $('#deleteLedgerModal').modal('show');
  });

  // ledger(detail) view
  $('.ledgerButton').on('click', function(){
    // gather data items
    var client_type = $(this).data('type');
    var client_id = $(this).data('id');

    // check for client type
    if(client_type == 'customer'){
      fetch_customer(client_id);
    }
    if(client_type == 'vendor'){
      fetch_vendor(client_id);
    }

    // append ledger entries
    $('.ledger_wrapper').html('');

    if(client.ledgers.length == 0){
      $('.ledger_wrapper').prepend('<tr class="table-warning"><td class="text-center" colspan=3>No Ledger Entries</td></tr>');
    }
    else{
      for(var i = 0; i < client.ledgers.length; i++){
        // debit credit classification
        if(client.ledgers[i].type == 'debit'){
          var color = "table-success";
        }
        else{
          var color = "table-danger";
        }
        
        // check if record has receiving_id
        if(client.ledgers[i].receiving_id){
          var detail = " Received";
        }
        else{
          var detail = "";
        }

        // check if record has invoice_id
        if(client.ledgers[i].invoice_id){
          var temp_route = "{{route('generate_invoice_pdf', ':id')}}";
          temp_route = temp_route.replace(':id', client.ledgers[i].invoice_id);
          // var detailHTML = '<a href="'+temp_route+'" target="_blank">Invoice # '+client.ledgers[i].invoice_id+', Items: '+client.ledgers[i].invoice.invoice_products.length+', Total: '+client.ledgers[i].invoice.total+'</a>';
          var detailHTML = 'Invoice # <a href="'+temp_route+'" target="_blank">'+client.ledgers[i].invoice_id+'</a>'+', Items: '+client.ledgers[i].invoice.invoice_products.length+', Total: Rs.'+client.ledgers[i].invoice.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        else{
          var detailHTML = '';
        }

        // prepend ledger entries
        $('.ledger_wrapper').prepend('<tr class="'+ color +'"><td class="transaction_dates">'+ new Date(client.ledgers[i].transaction_date).toDateString() +'</td><td class="details">'+detailHTML+'</td><td class="amounts">Rs. '+ client.ledgers[i].amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + detail + '</td><td class="types">'+ client.ledgers[i].type +'</td></tr>');
      }
    }
    // outstanding balance
    $('.detail_outstanding_balance').html('Rs. ' + client.outstanding_balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    $('#detailLedgerModal .modal-title').html(client.name + "'s Ledger.");
    $('#detailLedgerModal').modal('show');
  });

  // on generate_ledgers_excel click
  $('.generate_ledgers_excel').on('click', function(){
      // transaction_dates
      $('.transaction_dates').each(function(){
          $('#dummy_form').append('<input name="transaction_dates[]" value="'+$(this).text()+'"></input>')
      });
      // details
      $('.details').each(function(){
          $('#dummy_form').append('<input name="details[]" value="'+$(this).text()+'"></input>')
      });
      // amounts
      $('.amounts').each(function(){
          $('#dummy_form').append('<input name="amounts[]" value="'+$(this).text()+'"></input>')
      });
      // types
      $('.types').each(function(){
          $('#dummy_form').append('<input name="types[]" value="'+$(this).text()+'"></input>')
      });
      // title
      $('#dummy_form').append('<input name="title" value="'+$('#detailLedgerModalLabel').text()+'"></input>')
      // total
      $('#dummy_form').append('<input name="outstanding_balance" value="'+$('.detail_outstanding_balance').text()+'"></input>')
      // submit
      $('#dummy_form').submit();
  });

});
</script>
@endsection('content_body')