<!DOCTYPE html>
<html lang="en" ng-app="app">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Developer" />
    <meta name="author" content="John Lyric S. Demegillo" />
    <title>Scholarship Management System</title>
    <link rel="stylesheet" href="src/style.css" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome2/css/all.min.css">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/toaster/toast.css">
    <link rel="stylesheet" href="assets/ngTags/ng-tags-input.min.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/dropdown-select/select2.min.css" />

    <script id="uib/template/pagination/pagination.html" type="text/ng-template">
        <li role="menuitem" ng-if="::boundaryLinks" ng-class="{disabled: noPrevious()||ngDisabled}" class="page-item"><a href ng-click="selectPage(1, $event)" ng-disabled="noPrevious()||ngDisabled" uib-tabindex-toggle class="page-link"><i class="fa-solid fa-angle-left me-2"></i>First</a></li>
        <li role="menuitem" ng-if="::directionLinks" ng-class="{disabled: noPrevious()||ngDisabled}" class="page-item"><a href ng-click="selectPage(page - 1, $event)" ng-disabled="noPrevious()||ngDisabled" uib-tabindex-toggle class="page-link"><i class="fa-solid fa-angle-left me-2"></i>Previous</a></li>
        <li role="menuitem" ng-repeat="page in pages track by $index" ng-class="{active: page.active,disabled: ngDisabled&&!page.active}" class="page-item page-num"><a href ng-click="selectPage(page.number, $event)" ng-disabled="ngDisabled&&!page.active" uib-tabindex-toggle class="page-link">{{page.text}}</a></li>
        <li role="menuitem" ng-if="::directionLinks" ng-class="{disabled: noNext()||ngDisabled}" class="page-item"><a href ng-click="selectPage(page + 1, $event)" ng-disabled="noNext()||ngDisabled" uib-tabindex-toggle class="page-link">Next<i class="fa-solid fa-angle-right ms-2"></i></a></li>
        <li role="menuitem" ng-if="::boundaryLinks" ng-class="{disabled: noNext()||ngDisabled}" class="page-item"><a href ng-click="selectPage(totalPages, $event)" ng-disabled="noNext()||ngDisabled" uib-tabindex-toggle class="page-link">Last<i class="fa-solid fa-angle-right ms-2"></i></a></li>
    </script>
</head>

<body><!-- class="sb-nav-fixed" -->
    <div ui-view></div>
</body>

<!-- main components libraries -->
<script src="assets/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.bundle.js"></script>
<script src="assets/sweetalert/sweetalert2.all.min.js"></script>
<script src="assets/toaster/toast.js"></script>
<script src="assets/tinymce/tinymce.min.js"></script>

<!-- angularjs modules and libraries -->
<script src="assets/angular/angular.min.js"></script>
<script src="assets/angular/angular-filter.js"></script>
<script src="assets/angular/angular-idle.min.js"></script>
<script src="assets/angular-sanitize/angular-sanitize.min.js"></script>
<script src="assets/angular-animate/angular-animate.min.js"></script>
<script src="assets/@uirouter/angularjs/release/angular-ui-router.min.js"></script>
<script src="assets/ui-bootstrap4/dist/ui-bootstrap-tpls.js"></script>
<script src="assets/angularjs-sweetalert2/SweetAlert2.min.js"></script>
<script src="assets/printjs/print.min.js"></script>
<script src="assets/ngTags/ng-tags-input.min.js"></script>
<script src="assets/file-saver.js"></script>
<script src="assets/aes.js"></script>
<script src="assets/sidebar-toggle.js"></script>
<script src="assets/dropdown-select/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<!-- application script files -->
<script src="src/app.js"></script>

<!-- directives -->
<script src="src/directives/number-only.js"></script>
<script src="src/directives/date-input.js"></script>
<script src="src/directives/email-validator.js"></script>
<script src="src/directives/ng-file-select.js"></script>
<script src="src/directives/my-tinymce.js"></script>
<script src="src/directives/dropdown-select.js"></script>

<!-- services -->
<script src="src/services/api.route.js"></script>
<script src="src/services/decrypter.js"></script>

<!-- controllers -->
<script src="src/constrollers/ngcontroller.js"></script>
<script src="src/constrollers/home.ctrl.js"></script>
<script src="src/constrollers/student.ctrl.js"></script>
<script src="src/constrollers/employee.ctrl.js"></script>
<script src="src/constrollers/information.ctrl.js"></script>
<script src="src/constrollers/scheme.ctrl.js"></script>
<script src="src/constrollers/login.ctrl.js"></script>
<script src="src/constrollers/users.ctrl.js"></script>
<script src="src/constrollers/application.ctrl.js"></script>
<script src="src/constrollers/form.ctrl.js"></script>
<script src="src/constrollers/scholars.ctrl.js"></script>
<script src="src/constrollers/emailnotif.ctrl.js"></script>
<script src="src/constrollers/reports.ctrl.js"></script>



</html>