<div class="modal-header">
    <h5 class="modal-title">Student & Employee List</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="d-flex mb-3">
        <div class="sms-form">
            <i class="fa fa-search"></i>
            <input type="text" class="form-input form-input-search w-50" placeholder="Search" ng-model="search">
        </div>
    </div>
    <div class="table-css table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="fw-bold" width="8%" nowrap>No.</th>
                    <th class="fw-bold" nowrap>Full Name</th>
                    <th class="fw-bold text-center" nowrap>User Type</th>
                    <th class="fw-bold text-center" nowrap>Status</th>
                    <th class="fw-bold text-center" nowrap>Option</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="uo in filtered = (usersobj | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                    <td>{{uo.usernum}}</td>
                    <td nowrap>{{uo.fname}} {{uo.mname}}. {{uo.lname}}</td>
                    <td class="text-center">{{usertype(uo.usertype)}}</td>
                    <td class="text-center" nowrap>{{userstatus(uo.userstatus)}}</td>
                    <td width="8%">
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a type="button" ng-click="users_add(uo)">Add</a>
                        </div>
                    </td>
                </tr>
                <tr ng-show="filtered.length == 0">
                    <td colspan="5" class="text-center">No users available to add</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center justify-content-end pt-3">
        <ul style="margin-bottom: 0 !important;" uib-pagination total-items="filtered.length" num-pages="numPages" items-per-page="items_per_page" ng-model="current_page" max-size="5" boundary-link-numbers="true" ng-change="pageChanged()"></ul>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
</div>