<div class="modal-header">
    <h5 class="modal-title">Scholarship Type</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Type Name: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="e.g., Merit-Based Scholarships" ng-model="sch.typename">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Description: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Enter Description" ng-model="sch.description">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="save_schtype(sch)">Save Changes</button>
</div>