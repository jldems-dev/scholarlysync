<div class="modal-header">
    <h5 class="modal-title">Department</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Department Name: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Department Name" ng-model="dept.name">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Department Code: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Department Code" ng-model="dept.code">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Descriptions: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Descriptions" ng-model="dept.desciptions">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Head Department: <small class="text-danger">*</small></span>
            <select class="form-input" ng-model="dept.depthead">
                <option ng-value="0">Select Department Head</option>
                <option ng-repeat="emp in emp_list" ng-value="emp.userid">{{emp.fname}} {{emp.lname}}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="save_dept(dept)">Save Changes</button>
</div>