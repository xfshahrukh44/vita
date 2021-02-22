@csrf
<div class="modal-body">
    <!-- name -->
    <div class="form-group">
        <label for="">Name</label>
        <input id="name" type="text" name="name" placeholder="Area name"
        class="form-control" required max="50">
    </div>
    <!-- markets button-->
    <div class="row">
        <p class="m-0 p-1" style="font-size: 20px; font-weight: lighter;">Add Market</p>
        <!-- add children -->
        <button type="button" class="btn bg-transparent m-0 p-1 addMarketButton2">
            <i class="fa fa-plus green"></i>
        </button>
        <!-- remove children -->
        <button type="button" class="btn bg-transparent m-0 p-1 removeMarketButton2">
            <i class="fa fa-minus red"></i>
        </button>
    </div>
    <!-- children -->
    <div class="children_wrapper" id="children_wrapper">
        <div class="form-group">
            <input type="text" name="market_names[]" placeholder="Market name" class="form-control" required>
        </div>
    </div>
</div>