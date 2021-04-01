@csrf
<div class="modal-body row">
    <!-- level -->
    <div class="form-group col-md-6">
        <label for="">Level</label>
        <input id="level" type="number" name="level" placeholder="Enter discount level" class="form-control level" required value=0>
    </div>
    <!-- percentage -->
    <div class="form-group col-md-6">
        <label for="">Percentage</label>
        <input id="percentage" type="number" name="percentage" placeholder="Enter discount percentage" class="form-control percentage" required value=0 step=".01">
    </div>
</div>