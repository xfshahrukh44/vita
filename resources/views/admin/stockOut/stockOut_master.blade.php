@csrf
<div class="modal-body row">

    <!-- customer_id -->
    <div class="form-group col-md-6">
        <label for=""><i class="nav-icon fas fa-users"></i> Customer</label>
        <select id="customer_id" name="customer_id" class="form-control customer_id" style="width: 100%; height: 35px;">
            <option value="">Select customer</option>
            @foreach($customers as $customer)
                <option value="{{$customer->id}}">{{$customer->name}}</option>
            @endforeach
        </select>
    </div>

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

    <!-- quantity -->
    <div class="form-group col-md-6">
        <label for="">Quantity</label>
        <input id="quantity" type="number" name="quantity" placeholder="Enter quantity" class="form-control quantity" required min=0>
    </div>

    <!-- transaction_date -->
    <div class="col-md-6">
        <div class="form-group">
            <label for="">Transaction Date:</label>
            <input name="transaction_date" class="form-control transaction_date" id="transaction_date" type="date">
        </div>
    </div>

    <!-- is_adjustment -->
    <div class="col-md-12">
        <div class="form-group text-center">
            <label for="">Adjustment Entry:</label>
            <input name="is_adjustment" class="form-control is_adjustment" type="checkbox" style="box-shadow: none;">
        </div>
    </div>

    <!-- expense_type -->
    <div class="form-group col-md-6 expense_type_wrapper">
        <label for="">Expense Type</label>
        <select id="expense_type" name="expense_type" class="form-control expense_type" style="width: 100%; height: 35px;">
            <option value="">Select Expense Type</option>
            <option value="Stock damage">Stock damage</option>
            <option value="Stock misplace">Stock misplace</option>
            <option value="Stock theft">Stock theft</option>
            <option value="Marketing expense">Marketing expense</option>
        </select>
    </div>

    <!-- narration -->
    <div class="form-group col-md-6 narration_wrapper">
        <label for="">Narration</label>
        <textarea type="text" name="narration" placeholder="Enter Payment Terms" class="form-control narration"></textarea>
    </div>

</div>