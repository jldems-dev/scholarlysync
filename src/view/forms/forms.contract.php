<h1 class="mt-4">Scholarship Contract Form <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Scholarship Contract Form</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-warning" role="alert">
        Ensure you have selected a student before sending the email. You can verify this by checking the status in the list to confirm if the email has been sent.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="col-lg-12">
            <div class="filter">
                <div class="d-flex align-items-center justify-content-end w-100 gap-2">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <button type="button" class="btn btn-sms-save " ng-click="forms_update(0, forms)" >Save</button>
                        <button type="button" class="btn btn-sms-dark" ng-click="print('form_print');">Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="forms-container">
                <div class="row">
                    <div class="col-lg-12">
                        <textarea class="form-input drEditor" height="500" ng-model="forms.formsbody" my-tinymce>{{forms.formsbody}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="col-lg-12">
            <div class="filter">
                <div class="d-flex align-items-center justify-content-end w-100 gap-2">
                    <div class="d-flex align-items-center justify-content-start">
                        <button type="button" class="btn btn-sms-save" ng-click="show_modal('studlist' , 1)">Select Student</button>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <button type="button" class="btn btn-sms-second " ng-click="send_forms(contract)" >Sent Email</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-css">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th class="fw-bold" width="1%" nowrap>#</th>
                            <th class="fw-bold" width="15%">Date</th>
                            <th class="fw-bold">Student Number</th>
                            <th class="fw-bold">Student Name</th>
                            <th class="fw-bold">Status</th>
                            <th class="fw-bold text-center">Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-if="contract.userid != 0">
                            <td>{{contract.sfid}} </td>
                            <td>{{contract.sfdate | date:'MMM dd, yyyy'}}</td>
                            <td>{{contract.usernum}}</td>
                            <td>{{contract.fname}} {{contract.mname? contract.mname + '.' : ''}} {{contract.lname}}</td>
                            <td>{{sfstatus(contract.sfstatus)}}</td>
                            <td width="8%">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a type="button" ng-click="studform_delete(contract.sfid)"><i class="fa-solid fa-ban text-danger"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr ng-if="contract.userid == 0">
                            <td colspan="6" class="text-center">Select Student to Sent Contract</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
                            <th class="fw-bold" width="1%" nowrap>#</th>
                            <th class="fw-bold" width="15%">Date</th>
                            <th class="fw-bold">Student Number</th>
                            <th class="fw-bold">Student Name</th>
                            <th class="fw-bold">Status</th>
                            <th class="fw-bold text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="sf in filtered = (sflist | filter: {formtype: 1} | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                            <td>{{sf.sfid}}</td>
                            <td>{{sf.sfdate | date:'MMM dd, yyyy'}}</td>
                            <td>{{sf.usernum}}</td>
                            <td>{{sf.fname}} {{sf.mname}} {{sf.lname}}</td>
                            <td>{{sfstatus(sf.sfstatus)}}</td>
                            <td width="8%">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a type="button" ng-click="studform_delete(sf.sfid)"><i class="fa-regular fa-trash text-danger"></i></a>
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
</div>
<?php include "../print/contract_print.php" ?>