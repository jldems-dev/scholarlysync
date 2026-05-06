<div id="dontshow">
    <div class="row" id="form_print">
        <div class="col-lg-12">
            <div class="headerprint">
                <img src="src/assets/images/LogoCSAV.png" alt="logo" class="img-25" width="100px">
                <div class="headertext">
                    <h6 class=" fw-bold mb-0">SCHOLARLYSYNC</h6>
                    <div class="text-uppercase xsd">School Based Web
                        Access Scholars Information <br>Tracking System with Email Notifications
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <span class="prtitle">Student List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%">No.</th>
                        <th class="fw-bold">Full Name</th>
                        <th class="fw-bold">Course</th>
                        <th class="fw-bold">Year</th>
                        <th class="fw-bold">Department</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="s in studlist">
                        <td>{{s.usernum}}</td>
                        <td>{{s.fname}} {{s.mname}} {{s.lname}}</td>
                        <td>{{s.course}}</td>
                        <td>{{s.yas}}</td>
                        <td>{{s.dept}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>