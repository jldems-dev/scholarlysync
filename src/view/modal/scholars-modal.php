<div class="modal-header">
    <h6 class="modal-title">Add Scholars</strong></h6>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 px-4">
            <div class="bg-light px-3 py-1 rounded">
                <p class="mt-3"><small class="fw-semibold">Scholarship Name: </small><small>{{schinfo.schname}}</small></p>
                <p class="mt-3"><small class="fw-semibold">Student Name: </small><small>{{studinfo.fname}} {{studinfo.mname}} {{studinfo.lname}}</small></p>
                <p class="mt-3"><small class="fw-semibold">Applied Date:</small> <small>{{appinfo.apldate | date:'MMM dd, yyyy'}}</small></p>
                <p class="mt-3"><small class="fw-semibold">Application Current Status:</small> <small>{{aplstatus(appinfo.aplstatus)}}</small></p>
            </div>
        </div>
        <div class="col-lg-12 px-4">
            <div class="my-3">
                <span class="text-label fw-semibold">Remarks <small class="text-danger">*</small></span>
                <textarea class="form-input" rows="5" ng-model="appinfo.remarks" placeholder="Remarks"></textarea>
            </div>
            <div class="my-3">
                <span class="text-label fw-semibold">Update Application Status <small class="text-danger">*</small></span>
                <select class="form-input" ng-model="appinfo.aplstatus">
                    <option ng-value="0" ng-disabled="appinfo.aplstatus > 0">Submitted</option>
                    <option ng-value="1" ng-disabled="appinfo.aplstatus > 1">Under Review</option>
                    <option ng-value="2" ng-disabled="appinfo.aplstatus > 2">Checking Documents</option>
                    <option ng-value="3" ng-disabled="appinfo.aplstatus > 3">Approved</option>
                    <option ng-value="4" ng-disabled="appinfo.aplstatus > 4">Rejected</option>
                    <option ng-value="5" ng-disabled="appinfo.aplstatus > 5">Hold</option>
                </select>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="application_status(appinfo)" ng-disabled="checkstudapp == 1">Update</button>
</div>