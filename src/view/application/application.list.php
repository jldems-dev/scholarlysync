<h1 class="mt-4">Application List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Application List</li>
</ol>
<div class="row">
    <div class="col-lg-12 " ng-hide="userid.UserTypeRID == 1">
        <div class=" filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Applications List');">Print</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="filter">
            <div class="row">
                <div class="col-lg-5 col-sm-12 col-md-12">
                    <div class="sms-form ">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
                    </div>
                </div>
                <div class="col-lg-7 col-sm-12 col-md-12">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <div class="d-flex align-items-center filter-fields">
                            <select ng-model="filter.schtype" class="form-input w-100">
                                <option ng-value="-1">Scholarship Type</option>
                                <option ng-repeat="st in schlist" ng-value="st.typeid">{{st.typename}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select ng-model="filter.date" class="form-input w-100">
                                <option ng-value="-1">Date As Of</option>
                                <option ng-value="0">Today</option>
                                <option ng-value="1">This Week</option>
                                <option ng-value="2">This Month</option>
                                <option ng-value="3">This Year</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select ng-model="filter.appstatus" class="form-input w-100">
                                <option ng-value="-1">Application Status</option>
                                <option ng-value="0">Submitted</option>
                                <option ng-value="1">Under Review</option>
                                <option ng-value="2">Awaiting Documents</option>
                                <option ng-value="3">Approved</option>
                                <option ng-value="4">Rejected</option>
                                <option ng-value="5">Hold</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="application_filter(filter)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="8%" nowrap>ApplicationID</th>
                        <th class="fw-bold" nowrap>Scholarship Name</th>
                        <th class="fw-bold" nowrap>Scholarship Types</th>
                        <th class="fw-bold" nowrap>Student Name</th>
                        <th class="fw-bold" nowrap>Application Date</th>
                        <th class="fw-bold text-center" nowrap>Application Status</th>
                        <th class="fw-bold text-center" nowrap ng-hide="userid.UserTypeRID == 4">Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="app in filtered = (aplist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td>{{app.aplid}}</td>
                        <td>{{app.schname}}</td>
                        <td>{{app.schtype}}</td>
                        <td>{{app.userName}}</td>
                        <td>{{app.apldate | date:'MMM dd, yyyy'}}</td>
                        <td class="text-center">{{aplstatus(app.aplstatus)}}</td>
                        <td width="8%" ng-hide="userid.UserTypeRID == 4">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="application_view(app)" ng-hide="[1, 3].includes(userid.UserTypeRID)"><i class="far fa-clock text-success"></i></a>
                                <a type="button" ng-click="state_go('application.form', app)"><i class="fab fa-wpforms text-dark"></i></a>
                                <a type="button" ng-click="application_delete(app.aplid)" ng-hide="[1,2, 3].includes(userid.UserTypeRID)"><i class="fa-regular fa-trash text-danger"></i></a>
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
<?php include "../print/app_print.php" ?>