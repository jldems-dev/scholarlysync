<h1 class="mt-4">User List</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage User List</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <button type="button" class="btn btn-sms-primary" ng-click="show_modal('userlist')">Add Login Account</button>
                <button type="button" class="btn btn-sms-dark" ng-click="print('form_print', 'User List');">Print</button>
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
                            <select ng-model="usertypeval" class="form-input">
                                <option ng-value="-1">All User</option>
                                <option ng-value="0">Admin</option>
                                <option ng-value="1">Student</option>
                                <option ng-value="2">Coaches</option>
                                <option ng-value="3">Dept Heads</option>
                            </select>
                        </div>
                        <button class="btn btn-md btn-sms-primary " ng-click="user_filter(usertypeval)"><i class="fa-solid fa-sliders"></i></button>
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
                        <th class="fw-bold" width="8%" nowrap>No</th>
                        <th class="fw-bold" nowrap>Name</th>
                        <th class="fw-bold" nowrap>UserName</th>
                        <th class="fw-bold" nowrap>PassWord</th>
                        <th class="fw-bold" nowrap>Type</th>
                        <th class="fw-bold" nowrap>Created</th>
                        <th class="fw-bold text-center" nowrap>Status</th>
                        <th class="fw-bold text-center" nowrap>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="ur in filtered = (userlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td>{{ur.UserNumber}}</td>
                        <td>{{ur.FirstName}} {{ur.MiddleName}}. {{ur.LastName}}</td>
                        <td>{{ur.UserName}}</td>
                        <td>{{ur.PassWDText}}</td>
                        <td>{{ur.UserTypeText}}</td>
                        <td>{{ur.createdAt}}</td>
                        <td class="d-flex align-items-center justify-content-center">
                            <div class="onoffswitch" ng-click="users_switch(ur)">
                                <input type="checkbox"
                                    name="onoffswitch"
                                    class="onoffswitch-checkbox"
                                    id="myonoffswitch-{{ur.LoginID}}"
                                    ng-checked="ur.UserLoginStatus == 0">
                                <label class="onoffswitch-label" for="myonoffswitch-{{ur.LoginID}">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </td>
                        <td width="8%">
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="users_edit(ur)"><i class="fa-solid fa-pen-to-square tex-success"></i></a>
                                <a type="button" ng-click="users_delete(ur.LoginID)"><i class="fa-solid fa-trash text-danger"></i></a>
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
<?php include "../print/user_print.php" ?>