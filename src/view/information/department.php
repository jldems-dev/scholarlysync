<div class="col-lg-12">
    <div class="filter">
        <div class="row">
            <div class="col-lg-5 col-sm-12 col-md-12">
                <div class="sms-form">
                    <i class="fa fa-search"></i>
                    <input type="text" class="form-input form-input-search" placeholder="Search">
                </div>
            </div>
            <div class="col-lg-7 col-sm-12 col-md-12">
                <div class="d-flex align-items-center justify-content-end">
                    <button class="btn btn-md btn-sms-save filter-fields" ng-click="dept_new('dept')">Add Department</button>
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
                    <th class="fw-bold" width="8%" nowrap>DepartmentID</th>
                    <th class="fw-bold" nowrap>Name</th>
                    <th class="fw-bold" nowrap>Code</th>
                    <th class="fw-bold" nowrap>Description</th>
                    <th class="fw-bold" nowrap>Total Student</th>
                    <th class="fw-bold text-center" nowrap>Option</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="dept in filteredi = (deptlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                    <td>{{dept.deptid}}</td>
                    <td>{{dept.deptname}}</td>
                    <td>{{dept.deptcode}}</td>
                    <td>{{dept.desciptions}}</td>
                    <td>{{dept.totalstudent}}</td>
                    <td width="8%">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a type="button" ng-click="dept_edit(dept.deptid)"><i class="fa-regular fa-pen-to-square tex-success"></i></a>
                            <a type="button" ng-click="dept_delete(dept.deptid)"><i class="fa-regular fa-trash text-danger"></i></a>
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