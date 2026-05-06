<h1 class="mt-4">{{studentid > 0? 'Edit' : 'Add'}} Employee</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage {{studentid > 0? 'Edit' : 'Add'}} Employee <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="fw-bold">Employee Information</h5>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('employee')">Employee List</button>
                    <button type="button" class="btn btn-sms-light" ng-click="employee_new()" ng-show="employeeid > 0">Add New</button>
                    <button type="button" class="btn btn-sms-delete" ng-click="employee_delete(employeeid)" ng-show="employeeid > 0">Delete</button>
                    <button type="button" class="btn btn-sms-update" ng-click="employee_update(empinfo)" ng-show="employeeid > 0">Update</button>
                    <button type="button" class="btn btn-sms-save" ng-click="employee_add(empinfo)" ng-show="employeeid == 0 || !employeeid">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="forms-container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="item-img">
                        <!-- <img ng-src="src\assets\images\no-image.png" alt="item-no-img"> -->
                        <img ng-src="{{empinfo.photo}}" alt="item-no-img">
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <input type="file" style="display:none" id="pimg" file-change="file_change()">
                        <label class="btn btn-sms-light py-3 rounded me-2 w-50" for="pimg" style="cursor:pointer">
                            <i class="fa-regular fa-image me-2"></i>
                            Browse
                        </label>
                        <button class="btn border btn-light text-danger rounded w-50 py-3" ng-click="remove_img('src/assets/images/no-image.png')">
                            <i class="fa-regular fa-trash-alt me-2"></i>
                            Remove
                        </button>
                    </div>
                </div>
                <div class="col-lg-9">
                    <h5 class="fw-bold">Profile</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Employee Number <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Employee Number" ng-model="empinfo.usernum" numbers-only>
                                <small class="text-danger" ng-if="!empinfo.usernum && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">First Name <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter First Name" ng-model="empinfo.fname">
                                <small class="text-danger" ng-if="!empinfo.fname && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Middle Name <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Middle Name" ng-model="empinfo.mname">
                                <small class="text-danger" ng-if="!empinfo.mname && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Last Name <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Last Name" ng-model="empinfo.lname">
                                <small class="text-danger" ng-if="!empinfo.lname && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Email <small class="text-danger">*</small></span>
                                <input type="email" class="form-input" placeholder="Enter Email" ng-model="empinfo.email" email-validator required>
                                <small
                                    ng-show="studinfo.email && isInvalidEmail"
                                    class="text-danger">
                                    Invalid email address
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Phone Number
                                </span>
                                <input type="text" class="form-input" placeholder="Enter Phone Number" ng-model="empinfo.pnumber" numbers-only>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Date Of Birth</span>
                                <input type="date" class="form-input" ng-model="empinfo.dob" date-input>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Gender</span>
                                <select class="form-input" ng-model="empinfo.gender">
                                    <option ng-value="0">Male</option>
                                    <option ng-value="1">Female</option>
                                    <option ng-value="2">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h5 class="fw-bold mt-5">Address</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="my-2">
                                <span class="text-label">Address <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Address" ng-model="empinfo.address">
                                <small class="text-danger" ng-if="!empinfo.address && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">City</span>
                                <select class="form-input" ng-model="empinfo.city">
                                    <option ng-value="0">Victorias City</option>
                                    <option ng-value="1">Bacolod City</option>
                                    <option ng-value="2">Silay City</option>
                                    <option ng-value="2">Manapla</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Barangay</span>
                                <input type="text" class="form-input" placeholder="Enter Barangay" ng-model="empinfo.brgy">
                            </div>
                        </div>
                    </div>
                    <h5 class="fw-bold mt-5">Employment</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Hire Date</span>
                                <input type="date" class="form-input" ng-model="empinfo.hrdate" date-input>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Job Title</span>
                                <input type="text" class="form-input" placeholder="Enter Job Title" ng-model="empinfo.jobtitle">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Department <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="empinfo.department">
                                    <option ng-value="0">--Select Department--</option>
                                    <option ng-repeat="dept in deptlist" ng-value="dept.deptid">{{dept.deptname}}</option>
                                </select>
                                <small class="text-danger" ng-if="empinfo.department == 0 && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Employee Type <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="empinfo.usertype">
                                    <option ng-value="-1">--Select Type--</option>
                                    <option ng-value="0">Administrator</option>
                                    <option ng-value="2">Coach</option>
                                    <option ng-value="3">Department Head</option>
                                    <option ng-value="4">Teacher</option>
                                </select>
                                <small class="text-danger" ng-if="empinfo.usertype == -1 && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Status <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="empinfo.userstatus">
                                    <option ng-value="-1">--Select Status--</option>
                                    <option ng-value="4">Full Time</option>
                                    <option ng-value="5">Part Time</option>
                                    <option ng-value="6">Consultant</option>
                                    <option ng-value="7">Probationary</option>
                                </select>
                                <small class="text-danger" ng-if="empinfo.userstatus == -1 && msg != ''">
                                    {{msg}}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>