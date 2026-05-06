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
                <div class="d-flex align-items-center justify-content-end ">
                    <button class="btn btn-md btn-sms-save filter-fields" ng-click="course_new('course')">Add Course</button>
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
                    <th class="fw-bold" width="5%" nowrap>CourseID</th>
                    <th class="fw-bold" nowrap>Name</th>
                    <th class="fw-bold" nowrap>Code</th>
                    <th class="fw-bold" nowrap>Description</th>
                    <th class="fw-bold" nowrap>Major</th>
                    <th class="fw-bold" nowrap>Total Student</th>
                    <th class="fw-bold text-center" nowrap>Option</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="crse in filtered = (courselist | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                    <td>{{crse.courseid}}</td>
                    <td>{{crse.coursename}}</td>
                    <td>{{crse.coursecode}}</td>
                    <td>{{crse.desp}}</td>
                    <td>{{crse.major}}</td>
                    <td>{{crse.totalstudent}}</td>
                    <td width="8%">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a type="button" ng-click="course_edit(crse.courseid)"><i class="fa-regular fa-pen-to-square tex-success"></i></a>
                            <a type="button" ng-click="course_delete(crse.courseid)"><i class="fa-regular fa-trash text-danger"></i></a>
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