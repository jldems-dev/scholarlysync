<h1 class="mt-4">Scholar Application Status Report</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Scholar Application Status</li>
</ol>
<div class="row">
    <div class="col-lg-12" ng-hide="userid.UserTypeRID == 1">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Applications List')">Print</button>
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
                        <div class="d-flex align-items-center">
                            <select class="form-input" ng-model="appstatus">
                                <option ng-value="-1">Application Status</option>
                                <option ng-repeat="ass in appstatuslist" ng-value="ass.value">{{ass.name}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <select class="form-input" ng-model="schtype">
                                <option ng-value="0">Scholarship type</option>
                                <option ng-repeat="sl in schtlist" ng-value="sl.typeid">{{sl.typename}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <input type="date" class="form-input"  ng-model="fromdate" date-input><div class="px-2">:</div>
                            <input type="date" class="form-input"  ng-model="todate" date-input>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="report_application(appstatus, schtype, fromdate, todate)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="1%" nowrap>ApplicationID</th>
                        <th class="fw-bold" nowrap>Name</th>
                        <th class="fw-bold" nowrap>Scholarship Name</th>
                        <th class="fw-bold" nowrap>Application Date</th>
                        <th class="fw-bold" nowrap>Approved Name</th>
                        <th class="fw-bold" nowrap>Remarks</th>
                        <th class="fw-bold text-center" nowrap>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="ra in filtered = (ra_list | filter: search)">
                        <td nowrap>{{ra.aplid}}</td>
                        <td nowrap>{{ra.userName}} </td>
                        <td>{{ra.schname}}</td>
                        <td>{{ra.apldate | date: 'MMM dd yyyy'}}</td>
                        <td>{{ra.approveName}}</td>
                        <td>{{ra.remarks}}</td>
                        <td class="text-center">{{aplstatus(ra.aplstatus)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "../print/sasr_report.php" ?>