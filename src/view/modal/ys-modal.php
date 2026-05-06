<div class="modal-header">
    <h5 class="modal-title">Year & Section</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-6 mb-3">
            <span class="fw-semibold me-2">Year: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Year" ng-model="ys.year">
        </div>
        <div class="col-lg-6 mb-3">
            <span class="fw-semibold me-2">Section: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Section" ng-model="ys.sec">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Section Name: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Section Name" ng-model="ys.name">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Section Code: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Section Code" ng-model="ys.code">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="save_yearsec(ys)">Save Changes</button>
</div>