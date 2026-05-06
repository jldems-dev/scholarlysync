<h1 class="mt-4">View Scholarship</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage View Scholarship</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-warning" role="alert">
       Please be advised that if you are already a scholar and your scholarship has been approved in the system, you are not eligible to submit another scholarship application.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('scheme')">Scholarship List</button>
                    <button type="button" class="btn btn-sms-light" ng-click="scheme_new()" ng-show="schid > 0" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID)">Add New</button>
                    <button type="button" class="btn btn-sms-delete" ng-click="scheme_delete(schid)" ng-show="schid > 0" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID)">Delete</button>
                    <button type="button" class="btn btn-sms-update" ng-click="scheme_update(schinfo)" ng-show="schid > 0" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID)">Update</button>
                    <button type="button" class="btn btn-sms-save" ng-click="scheme_add(schinfo)" ng-show="schid == 0 || !schid" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID)">Save</button>
                    <button type="button" class="btn btn-sms-save" ng-click="application_add()" ng-if="userid.UserTypeRID == 1" ng-disabled="chkinfo.approved > 0 || schinfo.schstatus == 2 || [2, 3, 4, 5, 6].includes(userid.UserStatus)">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="forms-container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12 border-bottom">
                            <div class="my-2">
                                <span class="text-label"><strong>Name Of Scheme</strong></span>
                                <p class="mt-3">{{schinfo.schname}}</p>
                            </div>
                        </div>
                        <div class="col-lg-12 border-bottom">
                            <div class="my-2">
                                <span class="text-label"><strong>Type of Scholarship</strong></span>
                                <p class="mt-3" ng-bind="(schtlist | filter: {typeid: schinfo.schtype})[0].typename"></p>
                            </div>
                        </div>
                        <div class="col-lg-12 border-bottom">
                            <div class="my-2">
                                <span class="text-label"><strong>Application Deadline</strong></span>
                                <p class="mt-3">{{schinfo.schdate |  date:'MMM dd, yyyy' }}</p>
                            </div>
                        </div>
                        <div class="col-lg-12 border-bottom">
                            <div class="my-2">
                                <span class="text-label"><strong>Assestance</strong></span>
                                <p class="mt-3">{{schinfo.amount}}</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label"><strong>Funding Source</strong></span>
                                <p class="mt-3">{{schinfo.fundsource? schinfo.fundsource: 'N/A'}}</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label"><strong>Scheme Status</strong></span>
                                <p class="mt-3" ng-bind-html="schinfo.schstatus == 1 ? '<span class=\'badge bg-success\'>Active</span>' : '<span class=\'badge bg-dark\'>Closed</span>'"></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label"><strong>Criteria</strong></span>
                                <p class="mt-3" ng-bind-html="schinfo.criteria"></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="my-2">
                                <span class="text-label"><strong>Document Required</strong></span>
                                <p class="mt-3" ng-bind-html="schinfo.docrequired"></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-sms-save" ng-if="userid.UserTypeRID == 1" ng-show="chkinfo.aplstatus == 2" ng-click="show_modal('appfiles')">Upload Documents</button>
                            <button type="button" class="btn" ng-if="userid.UserTypeRID == 1" ng-show="chkinfo.aplstatus > 2" ng-class="chkinfo.aplstatus == 3? 'btn-sms-save': chkinfo.aplstatus == 4? 'bg-danger text-white' : 'bg-dark text-white'" disabled>{{schmestatus(chkinfo.aplstatus)}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>