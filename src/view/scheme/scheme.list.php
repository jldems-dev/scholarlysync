<h1 class="mt-4">Scholarship List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Scholarship List</li>
</ol>
<div class="row">
    <div class="col-lg-12 col-sm-12" ng-if="userid.UserTypeRID != 1">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-primary" ng-click="state_go('scheme.add', 0)" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Add Scholarship</button>
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'Scholarship List');">Print</button>
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
                <div class="col-lg-7 col-sm-12 col-mg-12">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="schtype">
                                <option ng-value="0">Scholarship type</option>
                                <option ng-repeat="sl in schtlist" ng-value="sl.typeid">{{sl.typename}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center filter-fields">
                            <select class="form-input" ng-model="schstatus">
                                <option ng-value="0">Status</option>
                                <option ng-value="1">Active</option>
                                <option ng-value="2">Closed</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="scheme_list(schtype, schstatus)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="8%" nowrap>SCH No.</th>
                        <th class="fw-bold" nowrap>Name</th>
                        <th class="fw-bold" nowrap>Type</th>
                        <th class="fw-bold" nowrap width="20%">Assistance</th>
                        <th class="fw-bold" nowrap>Deadline</th>
                        <th class="fw-bold" nowrap>Created By</th>
                        <th class="fw-bold text-center" width="1%" nowrap>Status</th>
                        <th class="fw-bold text-center"  nowrap>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="sch in filteredi = (schlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td>{{sch.schid}}</td>
                        <td>{{sch.schname}}</td>
                        <td>{{sch.typename}}</td>
                        <td class="text-break">{{sch.awardamnt}}</td>
                        <td>{{sch.awardate | date:'MMM dd, yyyy'}}</td>
                        <td>{{sch.shortName}}</td>
                        <td class="text-center">
                            <div ng-bind-html="sch.schstatus == 1 ? '<span class=\'badge bg-success\'>Active</span>' : '<span class=\'badge bg-dark\'>Closed</span>'"></div>
                        <td width="8%">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="state_go('scheme.view', sch.schid)" ng-if="[1, 2, 3, 4].includes(userid.UserTypeRID)"><i class="fa-solid fa-eye tex-success"></i></a>
                                <a type="button" ng-click="state_go('scheme.add', sch.schid)" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID ) || userid.UserTypeRID == 0"><i class="fa-light fa-pen-to-square text-success"></i></a>
                                <a type="button" ng-click="scheme_delete(sch.schid)" ng-if="![1, 2, 3, 4].includes(userid.UserTypeRID) || userid.UserTypeRID == 0"><i class="fa-regular fa-trash text-danger"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex align-items-center justify-content-end pt-3">
            <ul style="margin-bottom: 0 !important;" uib-pagination total-items="filteredi.length" num-pages="numPages" items-per-page="items_per_page" ng-model="current_page" max-size="5" boundary-link-numbers="true" ng-change="pageChanged()"></ul>
        </div>
    </div>
</div>
<?php include "../print/scheme_print.php" ?>