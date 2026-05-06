<div class="modal-header">
    <h5 class="modal-title">Email</h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 px-4">
            <div>
                <p><strong>Email No: </strong>{{emailinfo.id}}</p>
                <p><strong>From: </strong>{{emailinfo.fromemail}}</p>
                <p><strong>To: </strong>{{emailinfo.email}}</p>
                <p><strong>Date: </strong>{{emailinfo.sentdate | date:'MMM dd, yyyy'}}</p>
            </div>
        </div>
        <hr>
        <div class="col-lg-12 px-4">
            <div class="mb-3">
                <div ng-bind-html="emailinfo.subjects"></div>
            </div>
        </div>
        <hr>
        <div class="col-lg-12 px-4">
            <div class="mb-3">
                <div ng-bind-html="emailinfo.body"></div>
            </div>
        </div>
       <hr ng-show="emailinfo.attchment == 'scholarlysyncContract.pdf' || emailinfo.attchment == 'scholarlysyncRAR.pdf'">
        <div class="col-lg-12 px-4" ng-show="emailinfo.attchment == 'scholarlysyncContract.pdf' || emailinfo.attchment == 'scholarlysyncRAR.pdf'">
            <strong>Attachement File</strong>
            <div class="files-container mt-2" type="button">
                <i class="fa-solid fa-file-pdf"></i>
                <div class="mx-3">
                    <div class="filename"><strong>{{emailinfo.attchment}}</strong></div>
                    <div class="filecomp">Size: 671kb
                        | Type: PDF</div>
                </div>
                <div class="mx-3">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <a ng-click="viewfile('uploads/formpdf/' +emailinfo.attchment)"><i class="far fa-file-code text-dark"></i></a>
                        <a ng-click="downloadFile('uploads/formpdf/' +emailinfo.attchment)"><i class="far fa-file-download text-success"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <hr ng-show="filelist.length > 0">
        <div class="col-lg-12 px-4" ng-show="filelist.length > 0">
            <strong>Attachment File</strong>
            <div class="files-container mt-2" type="button" ng-repeat="fl in filelist">
                <i class="fa-solid" ng-class="fl.FileType == 'application/pdf'? 'fa-file-pdf': 'fa-image' "></i>
                <div class="mx-3">
                    <div class="filename"><strong>{{fl.FileNames}}</strong></div>
                    <div class="filecomp">Size: {{getReadableFileSize(fl.FileSize)}}
                        | Date: {{fl.FileDate | date:'MMM dd, yyyy'}}</div>
                </div>
                <div class="mx-3">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <a ng-click="viewfile('uploads/filesmanager/' + fl.FileNames)"><i class="far fa-file-code text-dark"></i></a>
                        <a ng-click="downloadFile('uploads/filesmanager/' + fl.FileNames)"><i class="far fa-file-download text-success"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Close</button>
</div>