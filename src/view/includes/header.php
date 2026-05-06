<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" ng-controller="ngcontroller">
    <div class="container-fluid header-text" style="width: 18%;">
        <div class="d-flex align-items-center justify-content-start ml-0">
            <img src="src/assets/images/favicon.png" alt="" class="sms_icon me-2" width="35px">
            <div class="d-flex flex-column " >
                <h5 class="mb-0 text-white sms-h5">SCHOLARLYSYNC</h5>
                <small class="sub text-white sms-small text-nowrap">School Based Web
                    Access Scholars Information <br>Tracking System with Email Notifications</small>
            </div>
        </div>
    </div>
    <!-- Sidebar Toggle-->
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-start sms-menu-container">
            <button class="btn btn-link btn-sm text-white sms-menu" ng-click="toggleMenu()"><i class="fas fa-bars text-white"></i></button>
        </div>
        <div class="d-flex align-items-center justify-content-end">
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw text-white"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" ng-click="logout_user()"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>