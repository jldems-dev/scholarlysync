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
            <span class="prtitle">Application List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%">ApplicationID</th>
                        <th class="fw-bold">Scholarship Name</th>
                        <th class="fw-bold">Scholarship Types</th>
                        <th class="fw-bold">Student Name</th>
                        <th class="fw-bold">Application Date</th>
                        <th class="fw-bold text-center">Application Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="ap in aplist">
                        <td>{{ap.aplid}}</td>
                        <td>{{ap.schname}}</td>
                        <td>{{ap.schtype}}</td>
                        <td>{{ap.userName}}</td>
                        <td>{{ap.apldate | date:'MMM dd, yyyy'}}</td>
                        <td>{{aplstatus(ap.aplstatus)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>