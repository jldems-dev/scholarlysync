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
            <span class="prtitle">Scholars List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="1%" nowrap>No.</th>
                        <th class="fw-bold" width="1%" nowrap>Approved Date</th>
                        <th class="fw-bold" width="8%" nowrap>Student Number</th>
                        <th class="fw-bold">Student Name</th>
                        <th class="fw-bold">Scholarship</th>
                        <th class="fw-bold">Scholarship Type</th>
                        <th class="fw-bold">Course</th>
                        <th class="fw-bold text-center">Year Level</th>
                        <th class="fw-bold text-center" width="10%">Added From</th>
                        <th class="fw-bold text-center" width="10%">Schp Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="sr in srlist">
                        <td>{{sr.ScholarID}}</td>
                        <td>{{sr.ApprovedDate | date: 'MMM dd, yyyy'}}</td>
                        <td>{{sr.UserNumber}}</td>
                        <td>{{sr.FirstName}} {{sr.MiddleName}} {{sr.LastName}}</td>
                        <td>{{sr.ScholarshipName}}</td>
                        <td>{{sr.TypeName}}</td>
                        <td>{{sr.CourseCode}}</td>
                        <td class="text-center">{{sr.Years}}</td>
                        <td class="text-center">{{sr.AddedTypeText}}</td>
                        <td class="text-center">{{scholar_status(sr.ScholarStatus) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>