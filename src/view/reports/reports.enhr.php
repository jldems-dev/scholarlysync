<h1 class="mt-4">Email Notification History Report</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Email Notification History</li>
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
                        <div class="d-flex align-items-center">
                            <input type="date" class="form-input"  ng-model="fromdate" date-input><div class="px-2">:</div>
                            <input type="date" class="form-input"  ng-model="todate" date-input>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="report_emailnotif(fromdate, todate)"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="table-css table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%" nowrap>Email No </th>
                        <th class="fw-bold" nowrap>From</th>
                        <th class="fw-bold" nowrap>To</th>
                        <th class="fw-bold" nowrap>Subject</th>
                        <th class="fw-bold" nowrap>Date</th>
                        <th class="fw-bold text-center" nowrap>Email Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="eml in filtered = (re_list | filter: search)">
                        <td>{{eml.enid}}</td>
                        <td>{{eml.fromemail}}</td>
                        <td>{{eml.email}}</td>
                        <td>{{eml.subjects}}</td>
                        <td>{{eml.sentdate | date: 'MMM dd yyyy'}}</td>
                        <td class="text-center">{{eml.emailstatus == 0? 'Draft' : eml.emailstatus == 1 ? 'Sent' : 'Received'}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "../print/enhr_report.php" ?>