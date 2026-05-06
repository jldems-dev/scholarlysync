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
            <span class="prtitle">Scholars Overview Report</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="1%" nowrap>ScholarID</th>
                        <th class="fw-bold">Name</th>
                        <th class="fw-bold">Course</th>
                        <th class="fw-bold text-center">Year Level</th>
                        <th class="fw-bold">Scholarship Name</th>
                        <th class="fw-bold">Scholarship Type</th>
                        <th class="fw-bold text-center">Scholarship Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="scholar in srlist">
                        <td>{{scholar.UserNumber}}</td>
                        <td>{{scholar.FirstName}} {{scholar.MiddleName}} {{scholar.LastName}}</td>
                        <td>{{scholar.CourseName}}</td>
                        <td class="text-center">{{scholar.Years}}</td>
                        <td>{{scholar.ScholarshipName}}</td>
                        <td>{{scholar.TypeName}}</td>
                        <td class="text-center">{{scholar.ScholarStatus == 1? "Active" : scholar.ScholarStatus == 2? "Expired" : "Maxed Out"}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>