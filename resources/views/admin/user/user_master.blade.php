@csrf
<div class="modal-body">
    <!-- name -->
    <div class="form-group">
        <label for="">Name</label>
        <input id="name" type="text" name="name" placeholder="Enter name"
        class="form-control" required max="50">
    </div>
    <!-- email -->
    <div class="form-group">
        <label for="">Email</label>
        <input id="email" type="email" name="email" placeholder="Enter email"
        class="form-control" max="50">
    </div>
    <!-- password -->
    <div class="form-group">
    <label for="">Password</label>
    <input id="password" type="password" name="password" placeholder="Enter password"
    class="form-control" min=4>
    </div>
    <!-- phone -->
    <div class="form-group">
        <label for="">Phone</label>
        <input id="phone" type="text" name="phone" placeholder="Enter phone"
        class="form-control" required>
    </div>
    <!-- type -->
    @if($user_type == 'rider')
        <div class="form-group hidden_select" hidden>
            <label for="">Type</label>
            <select id="type" name="type" class="form-control">
                <option value="rider">Rider</option>
            </select>
        </div>
        <input type="hidden" value="rider" name="identifier">
    @else
        <div class="form-group">
            <label for="">Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="">Select user type</option>
                <option value="superadmin">Super Admin</option>
                <option value="user">User</option>
                <option value="rider">Rider</option>
            </select>
        </div>
    @endif
</div>