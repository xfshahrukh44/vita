@csrf
<div class="modal-body row">

    <!-- vendor_id -->
    <div class="form-group col-md-12 vendor_id_wrapper">
        <label for=""><i class="nav-icon fas fa-users"></i> Vendor</label>
        <select id="vendor_id" name="vendor_id" class="form-control vendor_id" style="width: 100%; height: 35px;">
            <option value="">Select vendor</option>
            @foreach($vendors as $vendor)
                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
            @endforeach
        </select>
    </div>

    <table class="table table-bordered table-striped">
        <tbody>
            <tr role="row">
                <td>Outstanding Balance</td>
                <td class="outstanding_balance"></td>
            </tr>
            <tr role="row">
                <td>Amount</td>
                <td class=""><input id="amount" type="number" name="amount" placeholder="Enter amount" class="form-control amount" required min=0></td>
            </tr>
        </tbody>
    </table>

</div>