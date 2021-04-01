@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fab fa-bootstrap"></i> Customer Types</h1>
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
                    <button class="btn btn-success" id="add_program" data-toggle="modal" data-target="#addCustomerTypeModal">
                        <i class="fas fa-plus"></i> Add New Customer Type
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
                            @if(count($customerTypes) > 0)
                            @foreach($customerTypes as $customerType)
                            <tr role="row" class="odd">
                                <td class="{{'name'.$customerType->id}}">{{$customerType->name}}</td>
                                <td>
                                    @can('isSuperAdmin')
                                        <!-- Edit -->
                                        <a href="#" class="editButton" data-id="{{$customerType->id}}">
                                            <i class="fas fa-edit blue ml-1"></i>
                                        </a>
                                        <!-- Delete -->
                                        <a href="#" class="deleteButton" data-id="{{$customerType->id}}">
                                            <i class="fas fa-trash red ml-1"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4"><h6 align="center">No customerType(s) found</h6></td>
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
             @if(count($customerTypes) > 0)
             {{$customerTypes->links()}}
             @endif
         </div>
     </div>
 </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addCustomerTypeModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addCustomerTypeModalLabel">Add New CustomerType</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="{{route('customer_type.store')}}">
            @include('admin.customerType.customerType_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Create</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editCustomerTypeModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editCustomerTypeModalLabel">Edit CustomerType</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="editForm" method="POST" action="{{route('customer_type.update', 1)}}">
            <!-- hidden input -->
            @method('PUT')
            <input id="hidden" type="hidden" name="hidden">
            @include('admin.customerType.customerType_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Update</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteCustomerTypeModal" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerTypeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteCustomerTypeModalLabel">Delete CustomerType</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="deleteForm" method="POST" action="{{route('customer_type.destroy', 1)}}">
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
            $('#editForm').attr('action', "{{route('customer_type.update', 1)}}");
            $('#hidden').val(id);
            
            $('#editForm #name').val($('.name' + id).html());
            $('#editForm #placeholder').val($('.placeholder' + id).html());
            $('#editForm #slug').val($('.slug' + id).html());

            $('#editCustomerTypeModal').modal('show');
        });

        // delete
        $('.deleteButton').on('click', function(){
            var id = $(this).data('id');
            $('#deleteForm').attr('action', "{{route('customer_type.update', 1)}}");
            $('#deleteForm .hidden').val(id);
            
            $('#deleteCustomerTypeModalLabel').text('Delete CustomerType: ' + $('.name' + id).html() + "?");
            $('#deleteCustomerTypeModal').modal('show');
        });
    });
</script>
@endsection('content_body')