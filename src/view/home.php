<?php include "includes/header.php" ?>
<div id="layoutSidenav">
    <?php include "includes/panel.php" ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="row g-3" ng-hide="userid.UserTypeRID == 1">
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title me-2">Scholars</h5> <span>Today: {{todayDate}}</span>
                                </div>
                                <p class="card-text">Total Scholar</p>
                                <h3>{{sc.qty}}</h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar sms-progress-bar" role="progressbar" style="width: {{sc.percent}} %" aria-valuenow="{{sc.percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="ms-3">
                                        {{sc.percent | number: 0}}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light text-dark">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title me-2">Scholarhsip</h5> <span>Today: {{todayDate}}</span>
                                </div>
                                <p class="card-text">Total Scholarship</p>
                                <h3>{{sch.qty}}</h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar sms-progress-bar" role="progressbar" style="width: {{sch.percent}}%" aria-valuenow="{{sc.percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="ms-3">
                                        {{sch.percent | number: 0}}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title me-2">Employees</h5> <span>Today: {{todayDate}}</span>
                                </div>
                                <p class="card-text">Total Employees</p>
                                <h3>{{em.qty}}</h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar sms-progress-bar" role="progressbar" style="width: {{em.percent}}%" aria-valuenow="{{sch.percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="ms-3">
                                        {{em.percent | number: 0}}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-light text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title me-2">Applications</h5> <span>Today: {{todayDate}}</span>
                                </div>
                                <p class="card-text">Total Submit Applications</p>
                                <h3>{{app.qty}}</h3>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar sms-progress-bar" role="progressbar" style="width: {{app.percent}}%" aria-valuenow=" {{app.percent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="ms-3">
                                        {{app.percent | number: 0}}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-graduation-cap"></i>
                                Recent Scholarship Added
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Name</th>
                                            <th class="fw-bold" width="1%">Deadline</th>
                                            <th class="fw-bold text-center" width="1%" nowrap>Status</th>
                                            <th class="fw-bold text-center" ng-hide="[2, 3, 4, 5, 6].includes(userid.UserStatus)">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="sch in schlist">
                                            <td>{{sch.schname}}</td>
                                            <td nowrap>{{sch.awardate | date: 'MMM dd yyyy'}}</td>
                                            <td class="text-center">
                                                <div ng-bind-html="sch.schstatus == 1 ? '<span class=\'badge bg-success\'>Active</span>' : '<span class=\'badge bg-dark\'>Closed</span>'"></div>
                                            <td width="8%" nowrap ng-hide="[2, 3, 4, 5, 6].includes(userid.UserStatus)">
                                                <div class="d-flex align-items-center justify-content-center gap-3">
                                                    <a type="button" ng-click="state_go('scheme.view', sch.schid)" ng-if="userid.UserTypeRID == 1"><small>Apply</small></a>
                                                    <a type="button" ng-click="state_go('scheme.add', sch.schid)" ng-if="userid.UserTypeRID == 0"><small>View</small></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-user-graduate"></i>
                                Recent Scholars Added
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="fw-bold">Student Name</th>
                                            <th class="fw-bold" width="20%" nowrap>Approved Date</th>
                                            <th class="fw-bold" width="20%">Scholarship</th>
                                            <th class="fw-bold text-center" width="1%" nowrap>Year Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="sr in srlist | orderBy:'-ApprovedDate' | limitTo:10">
                                            <td>{{sr.FirstName}} {{sr.MiddleName}} {{sr.LastName}}</td>
                                            <td>{{sr.ApprovedDate | date: 'MMM dd yyyy'}}</td>
                                            <td nowrap>{{sr.ScholarshipName}}</td>
                                            <td class="text-center" nowrap>{{sr.Years}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fa-solid fa-party-horn"></i>
                                Graduate Student Year: {{previousYear}} - {{currentYear}} (<i class="far fa-info-square"></i> Based on Scholar Status)
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="5"><input type="text" class="form-input form-input-search w-25 mb-2" placeholder="Search" ng-model="search"></th>
                                        </tr>
                                        <tr>
                                            <th class="fw-bold">Student Name</th>
                                            <th class="fw-bold">Course</th>
                                            <th class="fw-bold">Year&Section</th>
                                            <th class="fw-bold">Department</th>
                                            <th class="fw-bold">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="srg in srglist | filter: search">
                                            <td>{{srg.FirstName}} {{srg.MiddleName}} {{srg.LastName}}</td>
                                            <td nowrap>({{srg.CourseCode}}) {{srg.CourseName}}</td>
                                            <td nowrap>{{srg.Years}} - {{srg.Section}}</td>
                                            <td nowrap>{{srg.DeptName}}</td>
                                            <td nowrap>{{srg.ScholarStatus == 3? 'Graduate' : ''}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>