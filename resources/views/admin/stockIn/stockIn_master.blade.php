@csrf
<div class="modal-body row">

    <!-- product_id -->
    <div class="form-group col-md-6">
        <label for=""><i class="nav-icon fab fa-product-hunt"></i> Product</label>
        <select id="product_id" name="product_id" class="form-control product_id" style="width: 100%; height: 35px;">
            <option value="">Select product</option>
            @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->article}}</option>
            @endforeach
        </select>
    </div>

    <!-- vendor_id -->
    <div class="form-group col-md-6">
        <label for=""><i class="nav-icon fab fa-vendor-hunt"></i> Vendor</label>
        <select id="vendor_id" name="vendor_id" class="form-control vendor_id" style="width: 100%; height: 35px;">
            <option value="">Select vendor</option>
            @foreach($vendors as $vendor)
                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
            @endforeach
        </select>
    </div>

    <!-- quantity -->
    <div class="form-group col-md-3">
        <label for="">Quantity</label>
        <input id="quantity" type="number" name="quantity" placeholder="Enter quantity" class="form-control quantity" required min=0>
    </div>

    <!-- rate -->
    <div class="form-group col-md-3">
        <label for="">Rate</label>
        <input id="rate" type="number" name="rate" placeholder="Enter rate" class="form-control rate" required min=0>
    </div>

    <!-- amount -->
    <div class="form-group col-md-3">
        <label for="">Amount</label>
        <input id="amount" type="number" name="amount" placeholder="Enter amount" class="form-control amount" readonly required value=0>
    </div>

    <!-- transaction_date -->
    <div class="col-md-3">
        <div class="form-group">
            <label for="">Transaction Date:</label>
            <input name="transaction_date" class="form-control transaction_date" id="transaction_date" type="date">
        </div>
    </div>

</div>