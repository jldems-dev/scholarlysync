<div class="modal-header">
  <h5 class="modal-title"><i class="fa-solid fa-file-arrow-up"></i> Upload Documents</h5>
  <button type="button" class="btn-close" ng-click="closeModal()" aria-label="Close"></button>
</div>
<div class="modal-body">
  <div class="wrapper">
    <div class="browsefile" ng-hide="chkinfo.filesubmitted == 1">
      <input type="file" style="display:none" id="pimg" file-change="file_change()">
      <label class="btn btn-theme-dark py-3 rounded me-2 w-50" for="pimg" style="cursor:pointer">
        <i class="fas fa-cloud-upload-alt"></i>
        Browse File to Upload
      </label>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-5" ng-hide="chkinfo.filesubmitted == 1">
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
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" ng-click="closeModal()">Cancel</button>
  <!-- <button type="button" class="btn btn-sms-save" ng-click="file_submit(schid, userid.UserID)" ng-disabled="checkstudapp == 1 || chkinfo.filesubmitted == 1">Upload</button> -->
</div>