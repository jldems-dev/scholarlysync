<h1 class="mt-4">Application Form <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Application Form</li>
</ol>
<div class="row" ng-hide="[0, 2, 3].includes(userid.UserTypeRID)">
    <div class="col-lg-12">
        <div class="alert alert-warning" role="alert">
        Ensure all mandatory fields marked with an asterisk (*) are filled in to avoid incomplete submissions and ensure proper processing of the application.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center justify-content-start gap-2">
                    <span>Status: {{aplstatus(appinfo.aplstatus)}}</span>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('scheme')" ng-hide="userid.UserTypeRID == 0">Scholarship List</button>
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('application')">Application List</button>
                    <button type="button" class="btn btn-sms-update" ng-click="show_modal('uploadfiles')" ng-hide='appinfo.aplstatus < 6 ||  [2, 3].includes(userid.UserTypeRID)'>Upload Documents</button>
                    <button type="button" class="btn btn-sms-save" ng-click="application_update(appinfo, 0)" ng-hide="appinfo.aplstatus < 6 ||  [2, 3].includes(userid.UserTypeRID)">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="border">
            <div class="row">
                <div class="col-lg-12 px-5 pt-5">
                    <h5 class="fw-bold">Scholarship Details</h5>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <p class="mt-3"><small class="fw-semibold">Scholarship Name: </small><small>{{schinfo.schname}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Scholarship Type: </small><small>{{schinfo.schtypename}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Deadline: </small><small>{{schinfo.schdate |  date:'MMM dd, yyyy'}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Assistance:</small> <small>{{schinfo.amount | number: 2}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Source:</small> <small>{{schinfo.fundsource}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Status: </small><small>{{schinfo.schstatus == 1? 'Active' : 'Closed'}}</small></p>
                        </div>
                        <div class="col-lg-12">
                            <p class="mt-3"><small class="fw-semibold">Criteria: </small><small ng-bind-html="schinfo.criteria"></small></p>
                            <p class="mt-3"><small class="fw-semibold">Documents Required: </small><small ng-bind-html="schinfo.docrequired"></small></p>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-lg-12 px-5 pt-5">
                    <h5 class="fw-bold">Personal Information</h5>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 d-flex gap-5">
                            <img class="mt-3" ng-src="{{studinfo.photo}}" alt="item-no-img" width="25%">
                            <div>
                                <p class="mt-3"><small class="fw-semibold">Full Name: </small><small>{{studinfo.fname}} {{studinfo.mname}}. {{studinfo.lname}}</small></p>
                                <p class="mt-3"><small class="fw-semibold">Email: </small><small>{{studinfo.email}}</small></p>
                                <p class="mt-3"><small class="fw-semibold">Date of Birth: </small><small>{{studinfo.dob |  date:'MMM dd, yyyy'}}</small></p>
                                <p class="mt-3"><small class="fw-semibold">Gender: </small><small>{{studinfo.gender == 0? 'Male' : 'Female'}}</small></p>
                                <p class="mt-3"><small class="fw-semibold">Course:</small> <small>{{studinfo.course}}</small></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <p class="mt-3"><small class="fw-semibold">Year&Section:</small> <small>{{studinfo.yas}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Department:</small> <small>{{studinfo.department}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Phone Number: </small><small>{{studinfo.pnumber? studinfo.pnumber : 'N/A'}}</small></p>
                            <p class="mt-3"><small class="fw-semibold">Address:</small> <small>{{studinfo.address? studinfo.address : 'Not Specified Yet'}}</small></p>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-lg-12 px-5 pt-5">
                    <h5 class="fw-bold">Academic Information</h5>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Major/Field of Study <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Major/Field of Study" ng-model="appinfo.major" ng-disabled="appinfo.aplstatus < 6">
                            </div>
                            <small class="text-danger" ng-if="!appinfo.major && msg != ''">
                                {{msg}}
                            </small>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">GPA (If applicable, include scale e.g., 3.5/4.0) <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter GPA" ng-model="appinfo.gpa" ng-disabled="appinfo.aplstatus < 6">
                            </div>
                            <small class="text-danger" ng-if="appinfo.gpa == 0 && msg != ''">
                                {{msg}}
                            </small>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Previous Academic Institutions Attended</span>
                                <input type="text" class="form-input" placeholder="Enter Previous Academic" ng-model="appinfo.paia" ng-disabled="appinfo.aplstatus < 6">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 px-5 pt-5">
                    <h5 class="fw-bold">Financial Information</h5>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Household Income</span>
                                <input type="text" class="form-input" placeholder="Enter household income" ng-model="appinfo.hincome" numbers-only ng-disabled="appinfo.aplstatus < 6">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Number of Dependents in Household</span>
                                <input type="text" class="form-input" placeholder="Enter Number of Dependents in Household" ng-model="appinfo.ndh" numbers-only ng-disabled="appinfo.aplstatus < 6">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Reason for Financial Need</span>
                                <input type="text" class="form-input" placeholder="Enter Reason for Financial" ng-model="appinfo.rfn" ng-disabled="appinfo.aplstatus < 6">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Currently Receiving Financial Aid?</span>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="radio" name="Financial" id="financial1" ng-model="appinfo.crfa" ng-value="0" ng-disabled="appinfo.aplstatus < 6">
                                    <label class="form-check-label" for="financial1">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Financial" id="financial12" ng-model="appinfo.crfa" ng-value="1" ng-disabled="appinfo.aplstatus < 6">
                                    <label class="form-check-label" for="financial12">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Have an Existing Scholarship?</span>
                                <div class="form-check my-2">
                                    <input class="form-check-input" type="radio" name="Existing" id="Existing1" ng-model="appinfo.dyhes" ng-value="0" ng-disabled="appinfo.aplstatus < 6">
                                    <label class="form-check-label" for="Existing1">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Existing" id="Existing2" ng-model="appinfo.dyhes" ng-value="1" ng-disabled="appinfo.aplstatus < 6">
                                    <label class="form-check-label" for="Existing2">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label">Scholarship</span>
                                <select class="form-input" ng-model="appinfo.aplschid" ng-disabled="appinfo.aplstatus < 6 || appinfo.dyhes == 1">
                                    <option ng-value="0">Select Scholarship</option>
                                    <option ng-repeat="sch in schlist" ng-value="sch.schid">{{sch.schname}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Why Are You Applying?</span>
                                <textarea class="form-input" rows="5" ng-model="appinfo.whyapply" placeholder="Type essay" ng-disabled="appinfo.aplstatus < 6"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="my-2">
                                <span class="text-label fw-semibold">Message or Concern</span>
                                <textarea class="form-input" rows="5" ng-model="appinfo.msgcnrn" placeholder="Message or concern" ng-disabled="appinfo.aplstatus < 6"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 px-5 pt-5">
                    <h5 class="fw-bold">Uploaded Documents</h5>
                    <hr>
                    <div class="row">
                        <div class="d-flex gap-3">
                            <div ng-repeat="fl in filelist">
                                <div class="files-container" type="button">
                                    <i class="fa-solid" ng-class="fl.FileType == 'application/pdf'? 'fa-file-pdf': 'fa-image' "></i>
                                    <div class="mx-3">
                                        <div class="filename"><strong>{{fl.FileNames}}</strong></div>
                                        <div class="filecomp">Size: {{getReadableFileSize(fl.FileSize)}}
                                            | Date: {{fl.FileDate | date:'MMM dd, yyyy'}}</div>
                                    </div>
                                    <div class="mx-3">
                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <a ng-click="viewfile(fl.FileNames)"><i class="far fa-file-code text-dark"></i></a>
                                            <a ng-click="downloadFile(fl.FileNames)"><i class="far fa-file-download text-success"></i></a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>