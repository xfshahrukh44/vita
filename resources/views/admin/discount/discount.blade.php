@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fab fa-bootstrap"></i> Discounts</h1>
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
                    <button class="btn btn-success" id="add_program" data-toggle="modal" data-target="#addDiscountModal">
                        <i class="fas fa-plus"></i> Add New Discount
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-md-12" style="overflow-x:auto;">
                    <table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Level: activate to sort column ascending">Level</th>
                                <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Percentage: activate to sort column ascending">Percentage</th>
                                <th tabindex="0" rowspan="1" colspan="1" >Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($discounts) > 0)
                            @foreach($discounts as $discount)
                            <tr role="row" class="odd">
                                <td class="{{'level'.$discount->id}}">{{$discount->level}}</td>
                                <td class="{{'percentage'.$discount->id}}">{{($discount->percentage) ? ($discount->percentage . '%') : ''}}</td>
                                <td>
                                    @can('isSuperAdmin')
                                        <!-- Edit -->
                                        <a href="#" class="editButton" data-id="{{$discount->id}}">
                                            <i class="fas fa-edit blue ml-1"></i>
                                        </a>
                                        <!-- Delete -->
                                        <a href="#" class="deleteButton" data-id="{{$discount->id}}">
                                            <i class="fas fa-trash red ml-1"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4"><h6 align="center">No discount(s) found</h6></td>
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
             @if(count($discounts) > 0)
             {{$discounts->links()}}
             @endif
         </div>
     </div>
 </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addDiscountModal" tabindex="-1" role="dialog" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addDiscountModalLabel">Add New Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" action="{{route('discount.store')}}">
            @include('admin.discount.discount_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Create</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog" aria-labelledby="editDiscountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editDiscountModalLabel">Edit Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="editForm" method="POST" action="{{route('discount.update', 1)}}">
            <!-- hidden input -->
            @method('PUT')
            <input id="hidden" type="hidden" name="hidden">
            @include('admin.discount.discount_master')
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="createButton">Update</button>
            </div>
        </form>
    </div>
</div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteDiscountModal" tabindex="-1" role="dialog" aria-labelledby="deleteDiscountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteDiscountModalLabel">Delete Discount</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form id="deleteForm" method="POST" action="{{route('discount.destroy', 1)}}">
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
            $('#editForm').attr('action', "{{route('discount.update', 1)}}");
            $('#hidden').val(id);
            
            $('#editForm #name').val($('.name' + id).html());
            $('#editForm #placeholder').val($('.placeholder' + id).html());
            $('#editForm #slug').val($('.slug' + id).html());

            $('#editDiscountModal').modal('show');
        });

        // delete
        $('.deleteButton').on('click', function(){
            var id = $(this).data('id');
            $('#deleteForm').attr('action', "{{route('discount.update', 1)}}");
            $('#deleteForm .hidden').val(id);
            
            $('#deleteDiscountModalLabel').text('Delete Discount: ' + $('.name' + id).html() + "?");
            $('#deleteDiscountModal').modal('show');
        });
    });
</script>
@endsection('content_body')