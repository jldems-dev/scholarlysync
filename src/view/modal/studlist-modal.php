<div class="modal-header">
    <h6 class="modal-title">Student List</strong></h6>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="d-flex align-items-center justify-content-between w-100 pb-3">
        <div class="d-flex align-items-center justify-content-start">
            <div class="sms-form w-100">
                <i class="fa fa-search"></i>
                <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-end gap-2">
            <div class="d-flex align-items-center">
                <select class="form-input" ng-model="departments">
                    <option ng-value="0">Departments</option>
                    <option ng-repeat="dl in deptlist" ng-value="dl.deptid">{{dl.deptcode}}</option>
                </select>
            </div>
            <div class="d-flex align-items-center">
                <select class="form-input" ng-model="course">
                    <option ng-value="0">Course</option>
                    <option ng-repeat="cl in courselist" ng-value="cl.courseid">{{cl.coursecode}}</option>
                </select>
            </div>
            <div class="d-flex align-items-center">
                <select class="form-input" ng-model="year">
                    <option ng-value="0">Year</option>
                    <option ng-repeat="yl in yslist" ng-value="yl.ysid">{{yl.years}} - {{yl.section}}</option>
                </select>
            </div>
            <button class="btn btn-md btn-sms-primary" ng-click="get_student(departments, course, year)"><i class="fa-solid fa-sliders"></i></button>
        </div>
    </div>
    <div class="table-css">
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th class="fw-bold" width="8%">Student No.</th>
                    <th class="fw-bold">Student Name</th>
                    <th class="fw-bold">Course/Year</th>
                    <th class="fw-bold">Department</th>
                    <th class="fw-bold text-center">Option</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="stud in filtered = (studlist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                    <td>{{stud.usernum}}</td>
                    <td>{{stud.fname}} {{stud.mname}}. {{stud.lname}}</td>
                    <td>{{stud.course}}: {{stud.yas}}</td>
                    <td>{{stud.dept}}</td>
                    <td width="8%">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a type="button" ng-click="studform_add(stud, formdropdown)"><i class="far fa-send-backward text-success"></i></a>
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
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Close</button>
</div>