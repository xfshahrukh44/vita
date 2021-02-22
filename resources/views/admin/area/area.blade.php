@extends('admin.layouts.master')

@section('content_header')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-map-marked-alt"></i> Areas and Markets</h1>
</div>
</div>

@endsection


@section('content_body')
<!-- Index view -->
@if($errors->any())
    <div style="background: red; color:white; font-size: 15px; font-weight: 700; width: 100%; padding: 10px; text-align: center;">
        {{ implode($errors->all(':message')) }}
    </div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <!-- <h3 class="">areas</h3> -->
                    <button class="btn btn-success" id="add_program" data-toggle="modal" data-target="#addAreaModal">
                        <i class="fas fa-plus"></i> Add New Area</button>
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
                               <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Markets</th>
                               <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($areas) > 0)
                                @foreach($areas as $area)
                                    <tr role="row" class="odd">
                                        <td class="{{'name'.$area->id}}" id="{{'name'.$area->id}}">{{$area->name}}</td>
                                        <td class="{{'name'.$area->id}}" id="{{'name'.$area->id}}">
                                            @foreach($area->markets as $market)
                                                {{ $loop->first ? '' : ', ' }}
                                                {{$market->name}}
                                                {{ $loop->last ? '.' : '' }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('isSuperAdmin')
                                                <!-- Detail -->
                                                <a href="#" class="editButton" data-id="{{$area->id}}" data-route="{{route('area.show', 0)}}">
                                                    <i class="fas fa-edit blue ml-1"></i>
                                                </a>
                                                <!-- Delete -->
                                                <a href="#" class="deleteButton" data-id="{{$area->id}}">
                                                    <i class="fas fa-trash red ml-1"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="3"><h6 align="center">No area(s) found</h6></td>
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
                @if(count($areas) > 0)
                {{$areas->links()}}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create view -->
<div class="modal fade" id="addAreaModal" tabindex="-1" role="dialog" aria-labelledby="addAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAreaModalLabel">Add New area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('area.store')}}" enctype="multipart/form-data">
                @include('admin.area.area_master')
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="createButton">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit view -->
<div class="modal fade" id="editAreaModal" tabindex="-1" role="dialog" aria-labelledby="editAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAreaModalLabel">Edit area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="{{route('area.update', 1)}}" enctype="multipart/form-data">
                <!-- hidden input -->
                @method('PUT')
                <input id="hidden" type="hidden" name="hidden">
                @include('admin.area.area_master')
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="createButton">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete view -->
<div class="modal fade" id="deleteAreaModal" tabindex="-1" role="dialog" aria-labelledby="deleteAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAreaModalLabel">Delete Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteForm" method="POST" action="{{route('area.destroy', 1)}}">
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

<!-- Detail view -->
<div class="modal fade" id="detailAreaModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="areaName"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <p class="marketHeading m-0 p-1" style="font-size: 20px; font-weight: lighter;"></p>
                    <button type="button" id="addMarketButton" class="btn bg-transparent m-0 p-1" data-route="{{route('market.store')}}">
                        <i class="fa fa-plus green"></i>
                    </button>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody class="table_row_wrapper" id="#table_row_wrapper">
                        
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" data-dismiss="modal" style="float: right;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Create market view -->
<div class="modal fade" id="addMarketModal" tabindex="-1" role="dialog" aria-labelledby="addMarketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Add new market</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- name -->
                    <div class="form-group">
                        <label for="">Name</label>
                        <input id="marketName" type="text" name="name" placeholder="Enter name"
                        class="form-control" required max="50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="storeMarketButton">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete market view -->
<div class="modal fade" id="deleteMarketModal" tabindex="-1" role="dialog" aria-labelledby="deleteMarketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMarketModalLabel">Delete Market</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="deleteMarketButton">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit market view -->
<div class="modal fade" id="editMarketModal" tabindex="-1" role="dialog" aria-labelledby="editMarketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMarketModalLabel">Edit Market</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editMarketForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- name -->
                    <div class="form-group">
                        <label for="">Name</label>
                        <input id="marketName" type="text" name="marketName" placeholder="Enter name"
                        class="form-control" required max="50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="editMarketButton">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){

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

        var current_area_id = 0;
        var current_route = "";
        var current_market_id = 0;
        var parent_tr_to_remove = {};

        // create modal
        $('#add_program').on('click', function(){
            $('.children_wrapper').html('');
        });

        // edit
        $('.editButton').on('click', function(){
            var id = $(this).data('id');
            current_route = $(this).data('route');
            $('#hidden').val(id);
            $('#editForm #name').val($('#name' + id).html());
            // alert(current_route);

            $.ajax({
                url: current_route,
                type: 'GET',
                data: {id: id},
                dataType: 'JSON',
                success: function (data) {
                    data = data.area;
                    if(data.markets.length > 0)
                    {
                        $('.children_wrapper').html('');
                        for(var i = 0; i < data.markets.length; i++)
                        {
                            $('.children_wrapper').prepend('<div class="form-group"><input type="text" name="market_names[]" placeholder="Market name" class="form-control" required value="'+data.markets[i].name+'"></div>');
                        }
                    }
                    return 0;
                }
            });


            $('#editAreaModal').modal('show');
        });

        // detail
        $('.detailButton').on('click', function(){
            var area_id = $(this).data('id');
            var loadAreaRoute = $(this).data('route');
            current_area_id = area_id;
            current_route = loadAreaRoute;
            var area = {};

            $.ajax({
                url: loadAreaRoute,
                type: 'GET',
                data: {id: area_id},
                dataType: 'JSON',
                success: function (data) {
                    area = data.area;

                    $('#areaName').text($('#name' + area_id).html());

                    if(area.markets.length > 0)
                    {
                        $('.marketHeading').text('Markets');
                        $('.table_row_wrapper').html('');
                        for(var i = 0; i < area.markets.length; i++)
                        {
                            $('.table_row_wrapper').append('<tr role="row" class="odd"><td class="">'+area.markets[i].name+'</td><td class="" width="20%"><a href="#" class="editMarketButton" data-id="'+area.markets[i].id+'" data-route="{{route("market.update", 1)}}"><i class="fas fa-edit blue ml-1"></i></a><a href="#" class="deleteMarketButton" data-id="'+area.markets[i].id+'" data-route="{{route("market.destroy", 1)}}"><i class="fas fa-trash red ml-1"></i></a></td></tr>');
                        }
                    }

                    else
                    {
                        $('.marketHeading').text('No markets available');
                        $('.table_row_wrapper').html('');
                    }

                    $('#detailAreaModal').modal('show');
                }
            });
        });

        // delete
        $('.deleteButton').on('click', function(){
            var id = $(this).data('id');
            $('#deleteForm').attr('action', "{{route('area.update', 1)}}");
            $('#deleteForm .hidden').val(id);
            
            $('#deleteAreaModalLabel').text('Delete area: ' + $('.name' + id).html() + "?");
            $('#deleteAreaModal').modal('show');
        });

        // create market modal
        $('#addMarketButton').on('click', function(){
            var route = $(this).data('route');
            var area_id = current_area_id;
            current_route = route;
            current_area_id = area_id;

            $('#marketName').val('');
            $('#addMarketModal').modal('show');
        });
        // create market
        $('#storeMarketButton').on('click', function(){
            var marketName = $('#marketName').val();
            $('#addMarketModal').modal('hide');

            $.ajax({
                url: current_route,
                type: 'GET',
                data: {area_id: current_area_id, marketName: marketName},
                dataType: 'JSON',
                success: function (data) {
                    $('.marketHeading').text('Markets');
                    $('.table_row_wrapper').append('<tr role="row" class="odd"><td class="">'+data.name+'</td><td class="" width="20%"><a href="#" class="editMarketButton" data-id="'+data.id+'" data-route="{{route("market.update", 1)}}"><i class="fas fa-edit blue ml-1"></i></a><a href="#" class="deleteMarketButton" data-id="'+data.id+'" data-route="{{route("market.destroy", 1)}}"><i class="fas fa-trash red ml-1"></i></a></td></tr>');
                }
            }); 
        });

        // delete market modal
        $('#detailAreaModal').on('click', '.deleteMarketButton', function(){
            current_market_id = $(this).data('id');
            current_route = $(this).data('route');
            parent_tr_to_remove = $(this).closest("tr");
            
            $('#deleteMarketModalLabel').text('Delete market?');
            $('#deleteMarketModal').modal('show');
        });
        // delete market
        $('#deleteMarketModal').on('click', '#deleteMarketButton', function(){
            var market_id = current_market_id;

            $.ajax({
                url: current_route,
                type: 'GET',
                data: {market_id: market_id},
                dataType: 'JSON',
                success: function (data) {
                    
                }
            });

            $('#deleteMarketModal').modal('hide');
            parent_tr_to_remove.remove();

            if($('.table_row_wrapper').html() == "")
            {
                $('.marketHeading').text('No markets available');
            }
        });

        // edit market modal
        $('#detailAreaModal').on('click', '.editMarketButton', function(){
            current_market_id = $(this).data('id');
            current_route = $(this).data('route');
            parent_tr_to_remove = $(this).closest("tr");

            $('#editMarketModal #marketName').val($(this).parent().siblings('td').html());
            $('#editMarketModal').modal('show');
        });
        // edit market
        $('#editMarketModal').on('click', '#editMarketButton', function(){
            var market_id = current_market_id;
            var name = $('#editMarketModal #marketName').val();

            $.ajax({
                url: current_route,
                type: 'GET',
                data: {market_id: market_id, name: name},
                dataType: 'JSON',
                success: function (data) {
                    $('#editMarketModal').modal('hide');
                    parent_tr_to_remove.remove();
                    $('.table_row_wrapper').append('<tr role="row" class="odd"><td class="">'+data.name+'</td><td class="" width="20%"><a href="#" class="editMarketButton" data-id="'+data.id+'" data-route="{{route("market.update", 1)}}"><i class="fas fa-edit blue ml-1"></i></a><a href="#" class="deleteMarketButton" data-id="'+data.id+'" data-route="{{route("market.destroy", 1)}}"><i class="fas fa-trash red ml-1"></i></a></td></tr>');
                }
            });
        });

        // add markets on create area form
        $('.addMarketButton2').on('click', function(){
            $('.children_wrapper').prepend('<div class="form-group"><input type="text" name="market_names[]" placeholder="Market name" class="form-control" required></div>');
        });
        // remove markets on create area form
        $('.removeMarketButton2').on('click', function(){
            $('.children_wrapper div:first-child').remove();
        });

    });
</script>
@endsection('content_body')