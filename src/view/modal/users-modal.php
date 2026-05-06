<div class="modal-header">
    <h5 class="modal-title">User Login Details</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">UserName: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Year" ng-model="userobj.username">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Old Password: <small class="text-danger">*</small></span>
            <input type="password" class="form-input" placeholder="Old Password" ng-model="userobj.passwdt">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Confirm Password: <small class="text-danger">*</small></span>
            <input type="password" class="form-input" placeholder="Confirm Password" ng-model="userobj.cpassword">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">New Password: <small class="text-danger">*</small></span>
            <input type="password" class="form-input" placeholder="New Password" ng-model="userobj.npassword">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="users_update(userobj)">Save Changes</button>
</div>