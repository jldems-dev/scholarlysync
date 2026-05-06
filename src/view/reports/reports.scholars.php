<h1 class="mt-4">Scholars Overview Report</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Scholars Overview</li>
</ol>
<div class="row">
    <div class="col-lg-12" ng-hide="userid.UserTypeRID == 1">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Applications List');">Print</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="filter">
            <div class="row">
                <div class="col-lg-5">
                    <div class="sms-form">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="filter-body">
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="scholarstatus">
                                <option ng-value="0">Select Scholar Status</option>
                                <option ng-value="1">Active</option>
                                <option ng-value="2">Stopped</option>
                                <option ng-value="3">Graduate</option>
                                <option ng-value="4">Alumni</option>
                                <option ng-value="5">Dropout</option>
                                <option ng-value="6">Transferred</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="schtype">
                                <option ng-value="0">Scholarship type</option>
                                <option ng-repeat="sl in schtlist" ng-value="sl.typeid">{{sl.typename}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="ysfrom">
                                <option value="">From Year</option>
                                <option ng-repeat="ys in yearsfrom" ng-value="ys.years">{{ys.years}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="ysto">
                                <option value="">To Year</option>
                                <option ng-repeat="ys in yearsto" ng-value="ys.years">{{ys.years}}</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="report_scholars(scholarstatus, schtype, ysfrom, ysto)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="1%" nowrap>ScholarID</th>
                        <th class="fw-bold" nowrap>Name</th>
                        <th class="fw-bold" nowrap>Course</th>
                        <th class="fw-bold text-center" nowrap>Year Level</th>
                        <th class="fw-bold" nowrap>Scholarship Name</th>
                        <th class="fw-bold" nowrap>Scholarship Type</th>
                        <th class="fw-bold text-center" nowrap>Scholar Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="scholar in filtered = (srlist | filter: search)">
                        <td>{{scholar.UserNumber}}</td>
                        <td>{{scholar.FirstName}} {{scholar.MiddleName}} {{scholar.LastName}}</td>
                        <td>{{scholar.CourseName}}</td>
                        <td class="text-center">{{scholar.Years}}</td>
                        <td>{{scholar.ScholarshipName}}</td>
                        <td>{{scholar.TypeName}}</td>
                        <td class="text-center">{{scholar_status(scholar.ScholarStatus)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "../print/scholar_report.php" ?>