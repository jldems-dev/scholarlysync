<h1 class="mt-4">Student Compliance</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Compliance Documents of {{studinfo.fname}} {{studinfo.mname}}. {{studinfo.lname}}</li>
</ol>
<div class="row">
    <div class="col-lg-12">
        <div class="filter">
            <div class="d-flex align-items-center justify-content-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <button type="button" class="btn btn-sms-primary" ng-click="state_go('student')">Student List</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="filter">
            <div class="row">
                <div class="col-lg-5">
                    <div class="sms-form w-50">
                        <i class="fa fa-search"></i>
                        <input type="text" class="form-input form-input-search" placeholder="Search" ng-model="search">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="filter-body">
                        <div class="d-flex align-items-center">
                            <input type="date" class="form-input"  ng-model="fromdate" date-input><div class="px-2">:</div>
                            <input type="date" class="form-input"  ng-model="todate" date-input>
                        </div>
                        <button class="btn btn-md btn-sms-primary" ng-click="student_compliance(studentid, fromdate, todate)"><i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="table-css">
            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th class="fw-bold" width="10%">File Name</th>
                        <th class="fw-bold" width="10%">Date</th>
                        <th class="fw-bold" width="20%">Scholarship</th>
                        <th class="fw-bold" width="20%">Scholarship Type</th>
                        <th class="fw-bold" width="1%" nowrap>Application No.</th>
                        <th class="fw-bold text-center" nowrap>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="fl in filtered = (file_obj | filter: search) | limitTo:items_per_page:items_per_page*(current_page-1)">
                        <td nowrap>
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="fa-solid p-3 border rounded me-3" ng-class="fl.filetype == 'application/pdf'? 'fa-file-pdf': 'fa-image' "></i>
                                <div class="me-3">
                                    <strong>{{fl.filenames}}</strong><br>
                                    <small>Type: {{fl.filetype}} | Size: {{getReadableFileSize(fl.filesize)}}</small>
                                </div>
                            </div>
                            
                        </td>
                        <td>{{fl.filedate | date:'MMM dd, yyyy'}}</td>
                        <td>{{fl.schname}}</td>
                        <td>{{fl.schname}}</td>
                        <td>{{fl.aplid}}</td>
                        <td width="8%" >
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a type="button" ng-click="viewfile(fl.filenames)"><i class="far fa-file-code text-dark"></i></a>
                                <a type="button" ng-click="downloadFile(fl.filenames)"><i class="far fa-file-download text-success"></i></a>
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
</div>