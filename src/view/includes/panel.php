<div id="layoutSidenav_nav" ng-controller="ngcontroller">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" ui-sref="home">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#scholarhsip" aria-expanded="false" aria-controls="scholarhsip">
                    <div class="sb-nav-link-icon"> <i class="fa-solid fa-graduation-cap"></i></div>
                    Scholarship
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="scholarhsip" data-bs-parent="#menu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" ui-sref="scheme">Scholarship List</a>
                        <a class="nav-link" ui-sref="scheme.add" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Add Scholarship</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#student" aria-expanded="false" aria-controls="student" ng-hide="userid.UserTypeRID == 1">
                    <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                    Students
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="student" data-bs-parent="#menu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" ui-sref="student">Student List</a>
                        <a class="nav-link" ui-sref="student.add" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Add Student</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#employee" aria-expanded="false" aria-controls="employee" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Employees
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="employee" data-bs-parent="#menu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" ui-sref="employee">Employee List</a>
                        <a class="nav-link" ui-sref="employee.add">Add Employee</a>
                    </nav>
                </div>
                <a class="nav-link" ui-sref="application">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file"></i></div>
                    Application
                </a>
                <a class="nav-link" ui-sref="scholars">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                    Scholars
                </a>
                <div class="sb-sidenav-menu-heading" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Other</div>
                <a class="nav-link" ui-sref="information" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                    Academic
                </a>
                <a type="button" class="nav-link collapsed" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)" data-bs-toggle="collapse" data-bs-target="#forms" aria-expanded="false" aria-controls="student" ng-hide="userid.UserTypeRID == 1">
                    <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                    Scholarship Forms
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="forms" data-bs-parent="#menu">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" ui-sref="forms.add">Add Forms</a>
                        <a class="nav-link" ui-sref="forms.sent">Sent Forms</a>
                    </nav>
                </div>
                <a class="nav-link" ui-sref="emailnotif" >
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-envelope"></i></div>
                    Email Notifications
                </a>
                <div class="sb-sidenav-menu-heading" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Admin Panel</div>
                <a class="nav-link" ui-sref="users" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-user-gear"></i></div>
                    User
                </a>
                <div class="sb-sidenav-menu-heading" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">Addons</div>
                <a class="nav-link" ui-sref="reports" ng-hide="[1, 2, 3, 4].includes(userid.UserTypeRID)">
                    <div class="sb-nav-link-icon"><i class="fa-sharp fa-solid fa-file-chart-column"></i></div>
                    Reports
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="sb-footer-text">Logged in as: {{info.usertype}} </div>
           {{info.fullname}}
        </div>
    </nav>
</div>