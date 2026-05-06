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
            <span class="prtitle">Employee List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%">No.</th>
                        <th class="fw-bold">Full Name</th>
                        <th class="fw-bold">Department</th>
                        <th class="fw-bold">Employee Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="e in emplist">
                        <td>{{e.usernum}}</td>
                        <td>{{e.fname}} {{e.mname}} {{e.lname}}</td>
                        <td>{{e.dept}}</td>
                        <td>{{usertype(e.usertype)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>