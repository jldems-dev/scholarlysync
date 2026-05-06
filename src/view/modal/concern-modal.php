<div class="modal-header">
    <h5 class="modal-title">Send Concern <img src="src/assets/images/email.gif" alt="Loading..." width="32" ng-show="emailoading"></h5>
    <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<!-- Latest compiled and minified CSS -->
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="d-flex align-items-center justify-content-center">
                <span class="fw-semibold me-2">From:</span>
                <input type="text" class="form-input" ng-model="emailinfo.fromemail" ng-disabled="emailinfo.fromemail">
            </div>
        </div>
        <div class="col-lg-12 mb-3">
            <div class="d-flex align-items-center justify-content-center gap-4">
                <span class="fw-semibold" style="padding-right: 4px">To: </span>
                <select id="userSelect" class="form-input custom-select" ng-model="emailinfo.userid" dropdown-select>
                    <option ng-value="0">Select a user</option>
                    <option ng-repeat="user in userslist" ng-value="user.userid">{{user.lname}}, {{user.fname}} {{user.mname}}</option>
                </select>
            </div>
        </div>
        <hr>
        <div class="col-lg-12 mb-3">
            <input type="text" class="form-input" placeholder="Subject" ng-model="emailinfo.subjects">
        </div>
        <hr>
        <div class="col-lg-12 mb-3">
            <textarea class="form-input" ng-model="emailinfo.body" my-tinymce>{{emailinfo.body}}</textarea>
        </div>
        <div class="col-lg-12 mb-3">
            <a type="button" ng-click="show = !show" class="text-dark text-decoration-none"><i class="fas fa-paperclip me-2"></i> Show Attacthment</a>
        </div>
        <div class="col-lg-12">
            <div class="wrapper">
                <div class="browsefile" ng-hide="chkinfo.filesubmitted == 1" ng-if="show">
                    <input type="file" style="display:none" id="pimg" file-change="file_change(emailinfo)">
                    <label class="btn btn-theme-dark py-3 rounded me-2 w-50" for="pimg" style="cursor:pointer">
                        <i class="fas fa-cloud-upload-alt"></i>
                        Browse File to Upload
                    </label>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-5" ng-hide="chkinfo.filesubmitted == 1 || !show">
                    <small class="text-muted">Supported formats: PDF, JPG, JPEG, PNG</small>
                    <small class="text-muted">Maximum size: 25mb</small>
                </div>
                <div class="uploaded-area">
                    <div class="row" ng-repeat="file in filelist">
                        <div class="col-lg-8">
                        <div class="d-flex align-items-center justify-content-start">
                            <i class="fas fa-file-alt"></i>
                            <div class="details">
                            <span class="name d-flex"><a class="text-dark" href="uploads\filesmanager\{{file.FileNames}}">{{file.FileNames}} </a>• Uploaded</span>
                            <span class="size">{{getReadableFileSize(file.FileSize)}}</span>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-4">
                        <div class="d-flex align-items-center justify-content-end " role='button' ng-click="file_delete(file)" ng-hide="chkinfo.filesubmitted == 1">
                            <i class="fa-regular fa-circle-trash text-danger fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
    <button type="button" class="btn btn-sms-save" ng-click="email_update(emailinfo)">Send</button>
</div>