<h1 class="mt-4">Student List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Student List</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-primary" ng-click="state_go('student.add', 0)" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Add Student</button>
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Student List');">Print</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="filter">
            <div class="row">
                <div class="col-lg-5 col-sm-12 col-md-12">
                    <div class="sms-form">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
                    </div>
                </div>
                <div class="col-lg-7 col-sm-12 col-md-12">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="departments">
                                <option ng-value="0">Departments</option>
                                <option ng-repeat="dl in deptlist" ng-value="dl.deptid">{{dl.deptcode}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="course">
                                <option ng-value="0">Course</option>
                                <option ng-repeat="cl in courselist" ng-value="cl.courseid">{{cl.coursecode}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="year">
                                <option ng-value="0">Year</option>
                                <option ng-repeat="yl in yslist" ng-value="yl.ysid">{{yl.years}} - {{yl.section}}</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="student_list(departments, course, year)"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="table-css table-responsive">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%" nowrap>Student No.</th>
                        <th class="fw-bold" nowrap>First Name</th>
                        <th class="fw-bold" nowrap>Middle Name</th>
                        <th class="fw-bold" nowrap>Last Name</th>
                        <th class="fw-bold" nowrap>Course</th>
                        <th class="fw-bold" nowrap>Year</th>
                        <th class="fw-bold" nowrap>Department</th>
                        <th class="fw-bold text-center" nowrap width="1%">Student Status</th>
                        <th class="fw-bold text-center" ng-hide="userid.UserTypeRID == 2" nowrap>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="stud in filtered = (studlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td>{{stud.usernum}}</td>
                        <td>{{stud.fname}}</td>
                        <td>{{stud.mname}}</td>
                        <td>{{stud.lname}}</td>
                        <td>{{stud.course}}</td>
                        <td>{{stud.yas}}</td>
                        <td>{{stud.dept}}</td>
                        <td class="text-center">{{student_status(stud.userstatus)}}</td>
                        <td width="8%" ng-hide="userid.UserTypeRID == 2">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="state_go('student.files', stud.userid)"><i class="fa-light fa-file text-dark"></i></a>
                                <a type="button" ng-click="state_go('student.add', stud.userid)"><i class="fa-light fa-pen-to-square text-success"></i></a>
                                <a type="button" ng-click="student_delete(stud.userid)" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)"><i class="fa-regular fa-trash text-danger"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex align-items-center justify-content-end pt-3">
            <ul style="margin-bottom: 0 !important;" uib-pagination total-items="filtered.length" num-pages="numPages" items-per-page="items_per_page" ng-model="current_page" max-size="5" boundary-link-numbers="true" ng-change="pageChanged()"></ul>
        </div>
    </div>
</div>
<?php include "../print/student_print.php" ?>