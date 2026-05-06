<h1 class="mt-4">Employee List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Employee List</li>
</ol>
<div class="row">
    <div class="col-lg-12 ">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-primary" ng-click="state_go('employee.add', 0)">Add Employee</button>
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Employee List');">Print</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
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
                            <select ng-model="filter.department" class="form-input w-100">
                                <option ng-value="-1">Select Department</option>
                                <option ng-repeat="dp in deptlist" ng-value="dp.deptid">{{dp.deptname}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select ng-model="filter.usertypes" class="form-input w-100">
                                <option ng-value="-1">Select Employee Type</option>
                                <option ng-value="0">Administrator</option>
                                <option ng-value="2">Coaches</option>
                                <option ng-value="3">Department Heads</option>
                                <option ng-value="4">Teacher</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="employee_filter(filter)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="8%" nowrap>Employee No.</th>
                        <th class="fw-bold" nowrap>First Name</th>
                        <th class="fw-bold" nowrap>Middle Name</th>
                        <th class="fw-bold" nowrap>Last Name</th>
                        <th class="fw-bold" nowrap>Department</th>
                        <th class="fw-bold" nowrap>Employee Type</th>
                        <th class="fw-bold text-center" nowrap>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="emp in filtered = (emplist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td>{{emp.usernum}}</td>
                        <td>{{emp.fname}}</td>
                        <td>{{emp.mname}}</td>
                        <td>{{emp.lname}}</td>
                        <td>{{emp.dept}}</td>
                        <td>{{usertype(emp.usertype)}}</td>
                        <td width="8%">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="state_go('employee.add', emp.userid)"><i class="fa-light fa-pen-to-square text-success"></i></a>
                                <a type="button" ng-click="employee_delete(emp.userid)"><i class="fa-regular fa-trash text-danger"></i></a>
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
<?php include "../print/emp_print.php" ?>