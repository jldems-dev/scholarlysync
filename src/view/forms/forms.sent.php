<h1 class="mt-4">Sent Form <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Manage Sent Form</li>
</ol>
<div class="row">
    <div class="col-lg-6">
         <div class="col-lg-12">
            <div class="filter">
                <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                    <div class="d-flex align-items-center justify-content-start gap-2">
                        <div class="d-flex align-items-center">
                             <select ng-model="formdropdown" class="form-input w-100">
                                <option ng-value="-1">Select Forms</option>
                                <option ng-repeat="fl in formlist" ng-value="fl.formid">{{fl.title}}</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sms-save" ng-click="select_form(formdropdown)">Save</button>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <div class="d-flex align-items-center">
                             <button type="button" class="btn btn-sms-save" ng-click="show_modal('studlist' , 1)">Select Student</button>
                        </div>
                        <div class="d-flex align-items-center">
                             <button type="button" class="btn btn-sms-second " ng-click="send_forms()" >Sent Email</button>
                        </div>
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
                            <th class="fw-bold" width="15%">Form Name</th>
                            <th class="fw-bold">Student Number</th>
                            <th class="fw-bold">Student Name</th>
                            <th class="fw-bold">Status</th>
                            <th class="fw-bold text-center">Cancel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="st in filtered = (sflist)" ng-if="sflist.length > 0">
                            <td>{{st.sfid}} </td>
                            <td>{{st.sfdate | date:'MMM dd, yyyy'}}</td>
                            <td>{{st.subjects}}</td>
                            <td>{{st.usernum}}</td>
                            <td>{{st.fname}} {{st.mname? st.mname + '.' : ''}} {{st.lname}}</td>
                            <td>{{sfstatus(st.sfstatus)}}</td>
                            <td width="8%">
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a type="button" ng-click="studform_delete(st.sfid)"><i class="fa-solid fa-ban text-danger"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr ng-if="sflist.length  <= 0">
                            <td colspan="7" class="text-center">Select Student to Sent Form</td>
                        </tr>
                    </tbody>
                </table>
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
                            <th class="fw-bold" width="1%" nowrap>#</th>
                            <th class="fw-bold" width="15%">Date</th>
                            <th class="fw-bold">Student Number</th>
                            <th class="fw-bold">Student Name</th>
                            <th class="fw-bold">Status</th>
                            <th class="fw-bold text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="sf in filtered = (sfsent | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
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