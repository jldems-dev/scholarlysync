<h1 class="mt-4">{{schid > 0? 'Edit' : 'Add'}} Scholarship</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage {{schid > 0? 'Edit' : 'Add'}} Scholarship</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('scheme')">Scholarship List</button>
                    <button type="button" class="btn btn-sms-light" ng-click="scheme_new()" ng-show="schid > 0">Add New</button>
                    <button type="button" class="btn btn-sms-delete" ng-click="scheme_delete(schid)" ng-show="schid > 0">Delete</button>
                    <button type="button" class="btn btn-sms-update" ng-click="scheme_update(schinfo)" ng-show="schid > 0">Update</button>
                    <button type="button" class="btn btn-sms-save" ng-click="scheme_add(schinfo)" ng-show="schid == 0 || !schid">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="forms-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Name<small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Scheme Name" ng-model="schinfo.schname">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Type of Scholarship<small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="schinfo.schtype">
                                    <option ng-value="0">Select Scholarship Type</option>
                                    <option ng-repeat="sch in schtlist" ng-value="sch.typeid">{{sch.typename}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Application Deadline</span>
                                <input type="date" class="form-input" ng-model="schinfo.schdate" date-input>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Assistance</span>
                                <input type="text" class="form-input" placeholder="Enter Assistance" ng-model="schinfo.amount">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Funding Source</span>
                                <input type="text" class="form-input" placeholder="Enter Funding Source" ng-model="schinfo.fundsource">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Scholarship Status</span>
                                <select class="form-input" ng-model="schinfo.schstatus">
                                    <option ng-value="0">Select Status</option>
                                    <option ng-value="1">Active</option>
                                    <option ng-value="2">Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Responsible for Scholarship<small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="schinfo.respsch">
                                    <option ng-value="0">Select Scholarship Type</option>
                                    <option ng-repeat="emp in emplist" ng-value="emp.userid">{{emp.lname}}, {{emp.fname}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label">Criteria <small class="text-danger">*</small></span>
                                <textarea class="form-input drEditor" rows="10" ng-model="schinfo.criteria" my-tinymce>{{schinfo.criteria}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label">Document Required <small class="text-danger">*</small></span>
                                <textarea class="form-input drEditor" rows="10" ng-model="schinfo.docrequired" my-tinymce>{{schinfo.docrequired}}</textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>