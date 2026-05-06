<h1 class="mt-4">Scholars List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Scholars List</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-sm-12" ng-hide="userid.UserTypeRID == 1">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <select class="form-input" style="width: 200px;" ng-model="upschpstatus" ng-show="selectedSch.length > 0">
                    <option ng-value="0">Select Scholar Status</option>
                    <option ng-value="1">Active</option>
                    <option ng-value="2">Stopped</option>
                    <option ng-value="3">Graduate</option>
                    <option ng-value="4">Alumni</option>
                    <option ng-value="5">Dropout</option>
                    <option ng-value="6">Transferred</option>
                </select>
                <button type="button" class="btn btn-sms-save" ng-click="scholar_selected(selectedSch, upschpstatus)" ng-show="selectedSch.length > 0" >Save</button>
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Applications List');">Print</button>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="filter">
            <div class="row">
                <div class="col-lg-5 col-sm-12 col-md-12">
                    <div class="sms-form">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search w-100" placeholder="Search" ng-model="search">
                    </div>
                </div>
                <div class="col-lg-7 col-sm-12 col-mg-12">
                    <div class="d-flex align-items-center justify-content-end gap-2 ">
                        <div class="d-flex align-items-center w-100">
                            <select class="form-input" ng-model="scholarstatus">
                                <option ng-value="0">Scholar Status</option>
                                <option ng-value="1">Active</option>
                                <option ng-value="2">Expired</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center w-100">
                            <select class="form-input" ng-model="schtype">
                                <option ng-value="0">Scholarship type</option>
                                <option ng-repeat="sl in schtlist" ng-value="sl.typeid">{{sl.typename}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center w-100">
                            <select class="form-input" ng-model="ysfrom">
                                <option value="">From Year</option>
                                <option ng-repeat="ys in yearsfrom" ng-value="ys.years">{{ys.years}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center w-100">
                            <select class="form-input" ng-model="ysto">
                                <option value="">To Year</option>
                                <option ng-repeat="ys in yearsto" ng-value="ys.years">{{ys.years}}</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="scholar_list(scholarstatus, schtype, ysfrom, ysto)"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12 col-md-12">
        <div class="table-css table-responsive">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th width="1%" class="text-center" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                            <input type="checkbox" class="form-check-input" ng-model="all_schid" ng-click="all_sch(filtered, current_page)">
                        </th>
                        <th class="fw-bold" width="1%" nowrap>No.</th>
                        <th class="fw-bold" width="1%" nowrap>Approved Date</th>
                        <th class="fw-bold" width="8%" nowrap>Student Number</th>
                        <th class="fw-bold" nowrap>Student Name</th>
                        <th class="fw-bold" nowrap>Scholarship</th>
                        <th class="fw-bold" nowrap>Scholarship Type</th>
                        <th class="fw-bold" nowrap>Course</th>
                        <th class="fw-bold text-center" nowrap>Year Level</th>
                        <th class="fw-bold text-center" width="10%" nowrap>Added From</th>
                        <th class="fw-bold text-center" width="10%" nowrap>Status</th>
                        <th class="fw-bold text-center" width="1%" nowrap ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="scholar in filtered = (srlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                            <input type="checkbox" class="form-check-input" ng-model="scholar.selected" ng-click="selected_sch(scholar)">
                        </td>
                        <td>{{scholar.ScholarID}}</td>
                        <td>{{scholar.ApprovedDate | date: 'MMM dd, yyyy'}}</td>
                        <td>{{scholar.UserNumber}}</td>
                        <td>{{scholar.FirstName}} {{scholar.MiddleName}} {{scholar.LastName}}</td>
                        <td>{{scholar.ScholarshipName}}</td>
                        <td>{{scholar.TypeName}}</td>
                        <td>{{scholar.CourseName}}</td>
                        <td class="text-center">{{scholar.Years}}</td>
                        <td class="text-center">{{scholar.AddedTypeText}}</td>
                        <td class="text-center">
                            <div ng-hide="schstatus">{{scholar_status(scholar.ScholarStatus)}}</div>
                            <select class="form-input-sm" ng-model="scholar.ScholarStatus" ng-show="schstatus">
                                <option ng-value="1">Active</option>
                                <option ng-value="2">Stopped</option>
                                <option ng-value="3">Graduate</option>
                                <option ng-value="4">Alumni</option>
                                <option ng-value="5">Dropout</option>
                                <option ng-value="6">Transferred</option>
                            </select>
                        </td>
                        <td width="1%" nowrap ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="scholar_update(scholar, 0)" ng-show="schstatus"><i class="fa-regular fa-floppy-disk text-primary"></i></a>
                                <a type="button" ng-click="scholar_edit(scholar.ScholarID)" ng-hide="schstatus"><i class="fa-regular fa-pen-to-square text-success"></i></a>
                                <a type="button" ng-click="scholar_delete(scholar.ScholarID)"><i class="fa-regular fa-trash text-danger"></i></a>
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
<?php include "../print/scholar_print.php" ?>