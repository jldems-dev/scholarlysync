<div class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-start ">
                            <img src="src/assets/images/LogoCSAV.png" alt="" class="sms_icon" width="30px">
                            <div class="ms-2">
                                <h5 class="mb-0 text-dark">SCHOLARLYSYNC</h5>
                                <small class="sub text-dark" style="font-size: 8px;">School Information Tracking & Email Notifications</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form ng-submit="login_user(user)">
                            <div class="form-floating mb-3">
                                <span class="text-label">Username</span>
                                <input class="form-input" type="text" placeholder="Enter Username" ng-model="user.username" />
                            </div>
                            <div class="form-floating mb-3">
                                <span class="text-label">Password</span>
                                <input class="form-input" type="password" placeholder="Enter Password" ng-model="user.password" />
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button class="btn btn-sms-primary" type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>