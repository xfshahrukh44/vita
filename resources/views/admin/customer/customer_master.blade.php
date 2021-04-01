@csrf
<div class="modal-body row">
    <!-- name -->
    <div class="form-group col-md-3  col-sm-3">
        <label for="">Name</label>
        <input type="text" name="name" placeholder="Enter name" class="form-control name" required>
    </div>
    <!-- contact_number -->
    <div class="form-group col-md-2  col-sm-2">
        <label for="">Contact #</label>
        <input type="text" name="contact_number" placeholder="Enter Contact #" class="form-control contact_number">
    </div>
    <!-- whatsapp_number -->
    <div class="form-group col-md-2  col-sm-2">
        <label for="">Whatsapp #</label>
        <input type="text" name="whatsapp_number" placeholder="Enter Whatsapp #" class="form-control whatsapp_number">
    </div>
    <!-- type -->
    <div class="form-group col-md-2  col-sm-2">
        <label for="">Customer Type</label>
        <select name="customer_type" class="form-control customer_type" style="width: 100%; height: 35px;">
            <option value="">Select Customer Type</option>
            @foreach($customer_types as $customer_type)
                <option value="{{$customer_type->id}}">{{$customer_type->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- shop_keeper_picture -->
    <div class="form-group col-md-3">
        <label for="">Shopkeeper Picture</label>
        <input type="file" name="shop_keeper_picture" placeholder="Upload Shopkeeper Picture"
        class="form-control">
    </div>
    <!-- shop_name -->
    <div class="form-group col-md-3">
        <label for="">Shop Name</label>
        <input type="text" name="shop_name" placeholder="Enter Shop Name" class="form-control shop_name">
    </div>
    <!-- shop_number -->
    <div class="form-group col-md-3">
        <label for="">Shop #</label>
        <input type="text" name="shop_number" placeholder="Enter Shop #" class="form-control shop_number">
    </div>
    <!-- floor -->
    <div class="form-group col-md-3">
        <label for="">Floor #</label>
        <input type="text" name="floor" placeholder="Enter Floor #" class="form-control floor">
    </div>
    <!-- shop_picture -->
    <div class="form-group col-md-3">
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
    <div class="form-group col-md-3">
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
    <!-- visiting_days -->
    <div class="form-group col-md-3">
        <label for="">Visting Days</label>
        <select name="visiting_days" class="form-control visiting_days" style="width: 100%; height: 35px;">
            <option value="">Select Day</option>
            <option value="monday">Monday</option>
            <option value="tuesday">Tuesday</option>
            <option value="wednesday">Wednesday</option>
            <option value="thursday">Thursday</option>
            <option value="friday">Friday</option>
            <option value="saturday">Saturday</option>
            <option value="sunday">Sunday</option>
        </select>
    </div>
    <!-- cash_on_delivery -->
    <div class="form-group col-md-3">
        <label for="">Payment Method</label>
        <select name="cash_on_delivery" class="form-control cash_on_delivery" style="width: 100%; height: 35px;">
            <option value="">Select Method</option>
            <option value="credit">Credit</option>
            <option value="bill_to_bill">Bill to Bill</option>
            <option value="weekly">Weekly</option>
            <option value="after_15_days">After 15 Days</option>
            <option value="50%_on_delivery">50% on Delivery</option>
        </select>
    </div>
    <!-- type -->
    <div class="form-group col-md-2  col-sm-2">
        <label for="">Payment Method</label>
        <select name="payment_method" class="form-control payment_method" style="width: 100%; height: 35px;">
            <option value="">Select Payment Method</option>
            @foreach($payment_methods as $payment_method)
                <option value="{{$payment_method->id}}">{{$payment_method->name}}</option>
            @endforeach
        </select>
    </div>
    <!-- opening_balance -->
    <div class="form-group col-md-3">
        <label for="">Opening Balance</label>
        <input type="number" name="opening_balance" placeholder="Enter Opening Balance" class="form-control opening_balance">
    </div>
    <!-- payment_terms -->
    <div class="form-group col-md-12">
        <label for="">Payment Terms</label>
        <textarea type="text" name="payment_terms" placeholder="Enter Payment Terms" class="form-control payment_terms"></textarea>
    </div>

    <hr>
    <h3>Special Discounted Prices</h3>
    <hr>
     <!-- Children Labels -->
     <div class="col-md-12">
        <div class="row">
            <!-- product -->
            <div class="form-group col-md-6">
                <label>Product:</label> <br>
            </div>
            <!-- Discounted Price -->
            <div class="form-group col-md-5">
                <label for="">Discounted Price:</label>
            </div>
            <!-- add child -->
            <div class="form-group col-md-0 add_button ml-1" style="display: table; vertical-align: middle;">
                <a class="btn btn-primary">
                    <i class="fas fa-plus" style="color:white;"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- Children -->
    <div class="col-md-12 field_wrapper">
        <div class="row">
            <!-- product -->
            <div class="col-md-6 form-group">
                <select name="products[]" class="form-control products" style="width: 100%; max-height: 20px;">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{$product->id}}">{{$product->article}}</option>
                    @endforeach
                </select>
            </div>
            <!-- amount -->
            <div class="form-group col-md-5">
                <input type="number" class="form-control amounts" name="amounts[]" min=0>
            </div>
            <!-- remove child -->
            <div class="form-group col-md-0 remove_button ml-1" style="display: table; vertical-align: middle;">
                <a class="btn btn-primary">
                    <i class="fas fa-minus" style="color:white;"></i>
                </a>
            </div>
        </div>
    </div>
</div>