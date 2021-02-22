@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-sign-in-alt"></i> Expenses</h1>
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
        <!-- <h3 class="card-title">Expenses</h3> -->
        <div class="card-tools">
          <button class="btn btn-success" id="add_expense" data-toggle="modal" data-target="#addExpenseModal">
            <i class="fas fa-plus"></i> Add New Expense</button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="col-md-12" style="overflow-x:auto;">
          <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
            <thead>
              <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Type</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Amount</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Detail</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Transaction Date</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Created By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Modified By</th>
                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1">Actions</th>
              </tr>
            </thead>
            <tbody>
              @if(count($expenses) > 0)
                @foreach($expenses as $expense)
                  <tr role="row" class="odd">
                    <td class="{{'type'.$expense->id}}">{{$expense->type ? $expense->type : NULL}}</td>
                    <td class="{{'amount'.$expense->id}}">{{$expense->amount ? 'Rs.' . number_format($expense->amount) : NULL}}</td>
                    <td class="{{'detail'.$expense->id}}">{{$expense->detail ? $expense->detail : NULL}}</td>
                    <td class="{{'date'.$expense->id}}">{{$expense->date ? return_date($expense->date) : NULL}}</td>
                    <td class="{{'created_by'.$expense->id}}">{{return_user_name($expense->created_by)}}</td>
                    <td class="{{'modified_by'.$expense->id}}">{{return_user_name($expense->modified_by)}}</td>
                    <td>
                      <!-- Edit -->
                      <a href="#" class="editButton" data-id="{{$expense->id}}" data-object="{{$expense}}">
                        <i class="fas fa-edit blue ml-1"></i>
                      </a>
                      <!-- Delete -->
                      <a href="#" class="deleteButton" data-id="{{$expense->id}}" data-object="{{$expense}}">
                        <i class="fas fa-trash red ml-1"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="7"><h6 align="center">No expense(s) found</h6></td></tr>
              @endif
            </tbody>
            <tfoot>
            
            </tfoot>
          </table>
        </div>
      <!-- /.card-body -->
      <div class="card-footer">
        @if(count($expenses) > 0)
        {{$expenses->appends(request()->except('page'))->links()}}
        @endif
      </div>
    </div>
  </div>
</div>

 <!-- Create view -->
<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addExpenseModalLabel">Add New Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{route('expense.store')}}" enctype="multipart/form-data">
        @include('admin.expense.expense_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editExpenseModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm" method="POST" action="{{route('expense.update', 1)}}" enctype="multipart/form-data">
        <!-- hidden input -->
        @method('PUT')
        <input id="hidden" type="hidden" name="hidden">
        @include('admin.expense.expense_master')
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="createButton">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteExpenseModal" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteExpenseModalLabel">Delete Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="deleteForm" method="POST" action="{{route('expense.destroy', 1)}}">
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
  var expense = "";

  // fetch expense
  function fetch_expense(id){
    // fetch expense
    $.ajax({
        url: "<?php echo(route('expense.show', 1)); ?>",
        type: 'GET',
        async: false,
        data: {expense_id: id},
        dataType: 'JSON',
        success: function (data) {
            expense = data.expense;
        }
    });
  }

  // create
  $('#add_expense').on('click', function(){
    // fetch_all_stores();
    // fetch_all_brands();
  });

  // edit
  $('.editButton').on('click', function(){
    var id = $(this).data('id');
    var expense = $(this).data('object');
    $('#hidden').val(id);

    $('#editForm .type option[value="'+ expense.type +'"]').prop('selected', true);
    $('#editForm .amount').val(expense.amount);
    $('#editForm .date').val(expense.date);
    $('#editForm .detail').val(expense.detail);
    
    $('#editExpenseModal').modal('show');
  });

  // delete
  $('.deleteButton').on('click', function(){
    var id = $(this).data('id');
    $('#deleteForm').attr('action', "{{route('expense.destroy', 1)}}");
    $('#deleteForm .hidden').val(id);

    $('#deleteExpenseModalLabel').text('Delete Expense?');
    $('#deleteExpenseModal').modal('show');
  });

});
</script>
@endsection('content_body')