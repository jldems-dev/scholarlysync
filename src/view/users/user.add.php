<h1 class="mt-4">{{studentid > 0? 'Edit' : 'Add'}} Student</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage {{studentid > 0? 'Edit' : 'Add'}} Student</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5 class="fw-bold">Student Information</h5>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('student')">Student List</button>
                    <button type="button" class="btn btn-sms-light" ng-click="student_new()" ng-show="studentid > 0">Add New</button>
                    <button type="button" class="btn btn-sms-delete" ng-click="student_delete(studentid)" ng-show="studentid > 0">Delete</button>
                    <button type="button" class="btn btn-sms-update" ng-click="student_update(studinfo)" ng-show="studentid > 0">Update</button>
                    <button type="button" class="btn btn-sms-save" ng-click="student_add(studinfo)" ng-show="studentid == 0 || !studentid">Save</button>
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
                        <img ng-src="{{studinfo.photo}}" alt="item-no-img">
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
                                <span class="text-label">Student Number <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Student Number" ng-model="studinfo.studnum" numbers-only>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">First Name <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter First Name" ng-model="studinfo.fname">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Middle Name </span>
                                <input type="text" class="form-input" placeholder="Enter Middle Name" ng-model="studinfo.mname">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Last Name <small class="text-danger">*</small></span>
                                <input type="text" class="form-input" placeholder="Enter Last Name" ng-model="studinfo.lname">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Email <small class="text-danger">*</small></span>
                                <input type="email" class="form-input" placeholder="Enter Email" ng-model="studinfo.email" email-validator>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Phone Number
                                </span>
                                <input type="text" class="form-input" placeholder="Enter Phone Number" ng-model="studinfo.pnumber" numbers-only>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Date Of Birth</span>
                                <input type="date" class="form-input" ng-model="studinfo.dob" date-input>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Gender</span>
                                <select class="form-input" ng-model="studinfo.gender">
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
                                <input type="text" class="form-input" placeholder="Enter Address" ng-model="studinfo.address">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">City <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="studinfo.city">
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
                                <input type="text" class="form-input" placeholder="Enter Barangay" ng-model="studinfo.brgy">
                            </div>
                        </div>
                    </div>
                    <h5 class="fw-bold mt-5">Academic</h5>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Course <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="studinfo.course">
                                    <option ng-value="0">--Select Course--</option>
                                    <option ng-repeat="cr in courselist" ng-value="cr.courseid">{{cr.coursecode}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Year & Section <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="studinfo.yas">
                                    <option ng-value="0">--Select Year & Section</option>
                                    <option ng-repeat="ys in yslist" ng-value="ys.ysid">{{ys.years}}-{{ys.section}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Department <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="studinfo.department">
                                    <option ng-value="0">--Select Department--</option>
                                    <option ng-repeat="dept in deptlist" ng-value="dept.deptid">{{dept.meaning}}({{dept.deptcode}})</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="my-2">
                                <span class="text-label">Student Status <small class="text-danger">*</small></span>
                                <select class="form-input" ng-model="studinfo.studstatus">
                                    <option ng-value="0">Active</option>
                                    <option ng-value="1">Inactive</option>
                                    <option ng-value="2">Graduated</option>
                                    <option ng-value="3">Transferred</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>