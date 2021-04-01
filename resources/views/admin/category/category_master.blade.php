@csrf
<div class="modal-body">
    <!-- name -->
    <div class="form-group col-md-12">
        <label for="">Name</label>
        <input id="name" type="text" name="name" placeholder="Enter brand name"
        class="form-control" required max="50">
    </div>
    <!-- parent_id -->
    <div class="form-group col-md-12">
        <label for=""><i class="nav-icon  fas fa-map-marked-alt"></i> Area</label>
        <select name="parent_id" class="form-control parent_id" style="width: 100%; height: 35px;">
            <option value="">Select parent</option>
            @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
            @endforeach
        </select>
    </div>
</div>