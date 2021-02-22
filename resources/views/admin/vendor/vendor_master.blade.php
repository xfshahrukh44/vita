@csrf
<div class="modal-body row">
    <!-- name -->
    <div class="form-group col-md-3">
        <label for="">Name</label>
        <input type="text" name="name" placeholder="Enter name" class="form-control name" required>
    </div>
    <!-- contact_number -->
    <div class="form-group col-md-2">
        <label for="">Contact #</label>
        <input type="text" name="contact_number" placeholder="Enter Contact #" class="form-control contact_number" required>
    </div>
    <!-- whatsapp_number -->
    <div class="form-group col-md-2">
        <label for="">Whatsapp #</label>
        <input type="text" name="whatsapp_number" placeholder="Enter Whatsapp #" class="form-control whatsapp_number" required>
    </div>
    <!-- type -->
    <div class="form-group col-md-2">
        <label for="">Vendor Type</label>
        <select name="type" class="form-control type" style="width: 100%; height: 35px;">
            <option value="">Select Vendor Type</option>
            <option value="manufacturer">Manufacturer</option>
            <option value="distributor">Distributor</option>
        </select>
    </div>
    <!-- shop_keeper_picture -->
    <div class="form-group col-md-3">
        <label for="">Shopkeeper Picture</label>
        <input type="file" name="shop_keeper_picture" placeholder="Upload Shopkeeper Picture"
        class="form-control">
    </div>
    <!-- shop_name -->
    <div class="form-group col-md-4">
        <label for="">Shop Name</label>
        <input type="text" name="shop_name" placeholder="Enter Shop Name" class="form-control shop_name" required>
    </div>
    <!-- shop_number -->
    <div class="form-group col-md-4">
        <label for="">Shop #</label>
        <input type="text" name="shop_number" placeholder="Enter Shop #" class="form-control shop_number" required>
    </div>
    <!-- shop_picture -->
    <div class="form-group col-md-4">
        <label for="">Shop Picture</label>
        <input type="file" name="shop_picture" placeholder="Upload Shop Picture"
        class="form-control">
    </div>
    <!-- area_id -->
    <div class="form-group col-md-4">
        <label for=""><i class="nav-icon  fas fa-map-marked-alt"></i> Area</label>
        <select name="area_id" class="form-control area_id" style="width: 100%; height: 35px;">
            <option value="">Select area</option>
            @foreach($areas as $area)
                <option value="{{$area->id}}">{{$area->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- channel_id -->
    <div class="form-group col-md-4">
        <label for="">Channel</label>
        <select name="channel_id" class="form-control channel_id" style="width: 100%; height: 35px;">
            <option value="">Select channel</option>
            @foreach($channels as $channel)
                <option value="{{$channel->id}}">{{$channel->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- hub_id -->
    <div class="form-group col-md-4">
        <label for="">Hub</label>
        <select name="hub_id" class="form-control hub_id" style="width: 100%; height: 35px;">
            <option value="">Select hub</option>
            @foreach($hubs as $hub)
                <option value="{{$hub->id}}">{{$hub->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- status -->
    <div class="form-group col-md-12">
        <label for="">Status</label>
        <select name="status" class="form-control status" style="width: 100%; height: 35px;">
            <option value="">Select Method</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="interested">Interested</option>
            <option value="follow_up">Follow up</option>
            <option value="not_interested">Not Interested</option>
        </select>
    </div>
    <!-- business_to_date -->
    <div class="form-group col-md-3" hidden>
        <label for="">Business to Date</label>
        <input type="number" min=0 name="business_to_date" placeholder="Enter Business to Date" class="form-control business_to_date" required value=0>
    </div>
    <!-- outstanding_balance -->
    <div class="form-group col-md-3" hidden>
        <label for="">Outstanding Balance</label>
        <input type="number" min=0 name="outstanding_balance" placeholder="Enter Outstanding Balance" class="form-control outstanding_balance" required value=0>
    </div>
    <!-- special_discount -->
    <div class="form-group col-md-3" hidden>
        <label for="">Special Discount</label>
        <input type="number" min=0 name="special_discount" placeholder="Enter Special Discount" class="form-control special_discount" required value=0>
    </div>
</div>