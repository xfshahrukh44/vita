@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fab fa-bootstrap"></i> Payment Methods</h1>
</div>
</div>

@endsection


@section('content_body')
<!-- Index view -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <button class="btn btn-success" id="add_program" data-toggle="modal" data-target="#addPaymentMethodModal">
                        <i class="fas fa-plus"></i> Add New Payment Method
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-md-12" style="overflow-x:auto;">
                    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Name</th>
                                <th tabindex="0" rowspan="1" colspan="1" >Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($paymentMethods) > 0)
                            @foreach($paymentMethods as $paymentMethod)
                            <tr role="row" class="odd">
                                <td class="{{'name'.$paymentMethod->id}}">{{$paymentMethod->name}}</td>
                                <td>
                                    @can('isSuperAdmin')
                                        <!-- Edit -->
                                        <a href="#" class="editButton" data-id="{{$paymentMethod->id}}">
                                            <i class="fas fa-edit blue ml-1"></i>
                                        </a>
                                        <!-- Delete -->
                                        <a href="#" class="deleteButton" data-id="{{$paymentMethod->id}}">
                                            <i class="fas fa-trash red ml-1"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4"><h6 align="center">No paymentMethod(s) found</h6></td>
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
             @if(count($paymentMethods) > 0)
             {{$paymentMethods->links()}}
             @endif
         </div>
     </div>
 </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentMethodModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addPaymentMethodModalLabel">Add New Payment Method</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="{{route('payment_method.store')}}">
            @include('admin.paymentMethod.paymentMethod_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Create</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentMethodModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editPaymentMethodModalLabel">Edit PaymentMethod</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="editForm" method="POST" action="{{route('payment_method.update', 1)}}">
            <!-- hidden input -->
            @method('PUT')
            <input id="hidden" type="hidden" name="hidden">
            @include('admin.paymentMethod.paymentMethod_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Update</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deletePaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentMethodModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deletePaymentMethodModalLabel">Delete PaymentMethod</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="deleteForm" method="POST" action="{{route('payment_method.destroy', 1)}}">
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
         //*** datatables ***//
        $('#example1').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "searching":false
        });

        // persistent active sidebar
        var element = $('li a[href*="'+ window.location.pathname +'"]');
        element.parent().parent().parent().addClass('menu-open');
        element.addClass('active');

        // edit
        $('.editButton').on('click', function(){
            var id = $(this).data('id');
            $('#editForm').attr('action', "{{route('payment_method.update', 1)}}");
            $('#hidden').val(id);
            
            $('#editForm #name').val($('.name' + id).html());
            $('#editForm #placeholder').val($('.placeholder' + id).html());
            $('#editForm #slug').val($('.slug' + id).html());

            $('#editPaymentMethodModal').modal('show');
        });

        // delete
        $('.deleteButton').on('click', function(){
            var id = $(this).data('id');
            $('#deleteForm').attr('action', "{{route('payment_method.update', 1)}}");
            $('#deleteForm .hidden').val(id);
            
            $('#deletePaymentMethodModalLabel').text('Delete PaymentMethod: ' + $('.name' + id).html() + "?");
            $('#deletePaymentMethodModal').modal('show');
        });
    });
</script>
@endsection('content_body')