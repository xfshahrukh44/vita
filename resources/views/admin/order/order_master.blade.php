@csrf
<div class="modal-body">

    <div class="upper_section row">
        <!-- customer_id -->
        <div class="col-md-4">
            <div class="form-group">
                <label for=""><i class="nav-icon fas fa-users"></i> Customer</label>
                <select name="customer_id" required class="form-control customer_id" style="width: 100%;">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- rider_id -->
        <div class="col-md-4 rider_wrapper" hidden>
            <div class="form-group">
                <label for=""><i class="nav-icon fas fa-motorcycle"></i> Rider</label>
                <select name="rider_id" required class="form-control rider_id" style="width: 100%;">
                    <option value="">Select Rider</option>
                    @foreach($riders as $rider)
                    <option value="{{$rider->id}}">{{$rider->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- description -->
        <div class="col-md-4 description_wrapper">
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="description" placeholder="Enter Subject" class="form-control description"></input>
            </div>
        </div>
        <!-- order_id -->
        <input type="hidden" class="order_id" name="order_id">
        <!-- total -->
        <div class="col-md-3 p-1 total_wrapper">
            <div class="form-group">
                <label>Current Amount(Total)</label>
                <input type="number" name="total" class="total form-control" min=0 readonly style="background-color: white;" value=0>
            </div>
        </div>
        <!-- previous_amount -->
        <div class="col-md-3 p-1 previous_amount_wrapper">
            <div class="form-group">
                <label>Previous Amount</label>
                <input type="number" name="previous_amount" class="previous_amount form-control" min=0 readonly style="background-color: white;" value=0>
            </div>
        </div>
        <!-- final_amount -->
        <div class="col-md-3 p-1 final_amount_wrapper">
            <div class="form-group">
                <label>Final Amount</label>
                <input type="number" name="final_amount" class="final_amount form-control" min=0 readonly style="background-color: white;" value=0>
            </div>
        </div>
        <!-- dispatch_date -->
        <div class="col-md-3 p-1 dispatch_date_wrapper">
            <div class="form-group">
                <label for="">Dispatch Date:</label>
                <input name="dispatch_date" class="form-control dispatch_date" type="date" required>
            </div>
        </div>
        <!-- payment -->
        <div class="col-md-4 p-1 payment_wrapper" hidden>
            <div class="form-group">
                <label for="">Payment</label>
                <select name="payment" class="form-control payment" style="width: 100%;">
                    <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="credit">Credit</option>
                </select>
            </div>
        </div>
        <!-- amount_pay -->
        <div class="col-md-4 p-1 amount_pay_wrapper" hidden>
            <div class="form-group">
                <label>Amount Pay</label>
                <input type="number" name="amount_pay" class="amount_pay form-control" min=0 style="background-color: white;" value=0>
            </div>
        </div>
        <!-- balance_due -->
        <div class="col-md-4 p-1 balance_due_wrapper" hidden>
            <div class="form-group">
                <label>Balance Due</label>
                <input type="number" name="balance_due" class="balance_due form-control" min=0 readonly style="background-color: white;" value=0>
            </div>
        </div>
    </div>

    <hr>
    <h5 class="text-center">Products</h5>
    <hr>

    <!-- Children Labels -->
    <div class="col-md-12">
        <div class="row labels">
            <!-- product -->
            <div class="form-group col-md-4">
                <label>Product:</label> <br>
            </div>
            <!-- Selling price -->
            <div class="form-group col-md-4">
                <label for="">Selling Price:</label>
            </div>
            <!-- quantity -->
            <div class="form-group col-md-3">
                <label for="">Quantity:</label>
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
            <div class="col-md-3">
                <div class="ui-widget">
                    <input class="form-control product_search" name="products[]">
                    <input class="hidden_product_search" type="hidden" name="hidden_product_ids[]">
                </div>
            </div>
            <!-- price -->
            <div class="form-group col-md-2">
                <input type="number" class="form-control prices" name="prices[]" required min=0>
            </div>
            <!-- quantity -->
            <div class="form-group col-md-1">
                <input type="number" class="form-control quantities" name="quantities[]" required value=0>
            </div>
            <!-- foc -->
            <div class="form-group col-md-1">
                <input type="number" class="form-control focs" name="focs[]" required value=0>
            </div>
            <!-- discount -->
            <div class="form-group col-md-2">
                <input type="number" class="form-control discounts" name="discounts[]" required value=0>
            </div>
            <!-- gross_amount -->
            <div class="form-group col-md-2">
                <input type="number" class="form-control gross_amounts" name="gross_amounts[]" required value=0>
            </div>
        </div>
    </div>
</div>