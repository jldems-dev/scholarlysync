<h1 class="mt-4">Add Form <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Add Form</li>
</ol>
<div class="row">
    <div class="col-lg-6">
        <div class="col-lg-12">
            <div class="filter">
                <div class="d-flex align-items-center justify-content-end w-100 gap-2">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <button type="button" class="btn btn-sms-primary " ng-click="new_form()" >New Form</button>
                        <button type="button" class="btn btn-sms-save " ng-click="save_form(form)" >Save Form</button>
                        <button type="button" class="btn btn-sms-dark" ng-click="print('form_print');">Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="forms-container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="my-3">
                            <span class="text-label">Scholarship <small class="text-danger">*</small></span>
                            <select id="userSelect" class="form-input custom-select" ng-model="form.schid" dropdown-select>
                                <option ng-value="0">Select a scholarship</option>
                                <option ng-repeat="sch in schlist" ng-value="sch.schid">{{sch.schname}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="my-3">
                            <span class="text-label">Description <small class="text-danger">*</small></span>
                            <input type="text" class="form-input" placeholder="Description" ng-model="form.title">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <textarea class="form-input drEditor" height="500" ng-model="form.body" my-tinymce>{{form.body}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="col-lg-12">
            <div class="filter">
                <div class="d-flex align-items-center justify-content-start">
                    <div class="sms-form w-50">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-css">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th class="fw-bold" width="1%" nowrap>FormID</th>
                            <th class="fw-bold" width="20%">Scholarship</th>
                            <th class="fw-bold">Description</th>
                            <th class="fw-bold text-center">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="fl in filteredi = (formlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                            <td>{{fl.formid}}</td>
                            <td>{{fl.schname}}</td>
                            <td>{{fl.title}}</td>
                            <td width="8%">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a type="button" ng-click="edit_form(fl.formid)"><i class="fa-light fa-pen-to-square text-success"></i></a>
                                    <a type="button" ng-click="form_delete(fl.formid)"><i class="fa-regular fa-trash text-danger"></i></a>
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
</div>
<?php include "../print/form.php" ?>