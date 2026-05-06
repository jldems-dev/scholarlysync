<div id="dontshow">
    <div class="row" id="form_print">
        <div class="col-lg-12">
            <div class="headerprint">
                <img src="src/assets/images/favicon.png" alt="logo" class="img-25" width="100px">
                <div class="headertext">
                    <h6 class=" fw-bold mb-0">SCHOLARLYSYNC</h6>
                    <div class="text-uppercase xsd">School Based Web
                        Access Scholars Information <br>Tracking System with Email Notifications
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <span class="prtitle">User List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%">UserNo.</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">UserName</th>
                        <th class="fw-bold">PassWord</th>
                        <th class="fw-bold">Type</th>
                        <th class="fw-bold">Created</th>
                        <th class="fw-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="ur in userlist">
                         <td>{{ur.UserNumber}}</td>
                        <td>{{ur.FirstName}} {{ur.MiddleName}}. {{ur.LastName}}</td>
                        <td>{{ur.UserName}}</td>
                        <td>{{ur.PassWD}}</td>
                        <td>{{ur.UserTypeText}}</td>
                        <td>{{ur.createdAt}}</td>
                        <td>{{ur.UserLoginStatus == 0? 'Active' : 'Inactive'}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>