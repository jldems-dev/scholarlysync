<div class="modal-header">
    <h5 class="modal-title">Course</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Course Name: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Course Name" ng-model="course.name">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Course Code: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Course Code" ng-model="course.code">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Desription: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Description" ng-model="course.description">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Major: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Course Major" ng-model="course.major">
        </div>
        <div class="col-lg-12 mb-3">
            <span class="fw-semibold me-2">Credits: <small class="text-danger">*</small></span>
            <input type="text" class="form-input" placeholder="Credits" ng-model="course.credit" numbers-only>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="save_course(course)">Save Changes</button>
</div>