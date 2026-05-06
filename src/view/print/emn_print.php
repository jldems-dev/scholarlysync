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
            <span class="prtitle">Email Notification List</span><br>
            <small>{{now | date: 'MMM dd, yyyy'}}</small>
        </div>
        <div class="col-lg-12 sec1">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="8%" nowrap>Email No </th>
                        <th class="fw-bold" nowrap>From</th>
                        <th class="fw-bold" nowrap>To</th>
                        <th class="fw-bold" nowrap>Subject</th>
                        <th class="fw-bold" nowrap>Date</th>
                        <th class="fw-bold text-center" nowrap>Email Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="eml in emaillist">
                        <td>{{eml.enid}}</td>
                        <td>{{eml.fromemail}}</td>
                        <td>{{eml.email}}</td>
                        <td>{{eml.subjects}}</td>
                        <td>{{eml.sentdate | date: 'MMM dd yyyy'}}</td>
                        <td class="text-center">{{eml.emailstatus == 0? 'Draft' : eml.emailstatus == 1 ? 'Sent' : 'Received'}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>