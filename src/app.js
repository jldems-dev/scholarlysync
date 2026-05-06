var app = angular.module("app", [
  "ui.router",
  "ngAnimate",
  "ngSanitize",
  "ngIdle",
  "angular.filter",
  "recepuncu.ngSweetAlert2",
  "ui.bootstrap",
]);
app.config(function (
  $stateProvider,
  $urlRouterProvider,
  IdleProvider,
  KeepaliveProvider,
  $locationProvider,
  $compileProvider
) {
  $locationProvider.hashPrefix("");
  IdleProvider.idle(5000);
  IdleProvider.timeout(20);
  KeepaliveProvider.interval(5000);
  $urlRouterProvider.otherwise("/login");
  $compileProvider.debugInfoEnabled(false);

  // authentication
  $stateProvider.state("login", {
    url: "/login",
    templateUrl: "src/view/login/login.php",
    controller: "login",
  });
  $stateProvider.state("home", {
    url: "/home",
    templateUrl: "src/view/home.php",
    controller: "home",
  });

  $stateProvider.state("student", {
    url: "/student",
    redirectTo: "student.list",
    templateUrl: "src/view/main.app.php",
    controller: "student",
  });
  $stateProvider.state("student.list", {
    url: "/list",
    templateUrl: "src/view/student/student.list.php",
  });
  $stateProvider.state("student.add", {
    url: "/add",
    templateUrl: "src/view/student/student.add.php",
  });
  $stateProvider.state("student.files", {
    url: "/files",
    templateUrl: "src/view/student/student.files.php",
  });

  $stateProvider.state("scholars", {
    url: "/scholars",
    redirectTo: "scholars.list",
    templateUrl: "src/view/main.app.php",
    controller: "scholars",
  });
  $stateProvider.state("scholars.list", {
    url: "/list",
    templateUrl: "src/view/scholars/scholars.list.php",
  });
  $stateProvider.state("scholars.add", {
    url: "/add",
    templateUrl: "src/view/scholars/scholars.add.php",
  });
  $stateProvider.state("scholars.files", {
    url: "/files",
    templateUrl: "src/view/scholars/scholars.files.php",
  });

  $stateProvider.state("employee", {
    url: "/employee",
    redirectTo: "employee.list",
    templateUrl: "src/view/main.app.php",
    controller: "employee",
  });
  $stateProvider.state("employee.list", {
    url: "/list",
    templateUrl: "src/view/employee/employee.list.php",
  });
  $stateProvider.state("employee.add", {
    url: "/add",
    templateUrl: "src/view/employee/employee.add.php",
  });

  $stateProvider.state("information", {
    url: "/information",
    templateUrl: "src/view/information/main.app.php",
    controller: "information",
  });

  $stateProvider.state("scheme", {
    url: "/scheme",
    redirectTo: "scheme.list",
    templateUrl: "src/view/main.app.php",
    controller: "scheme",
  });
  $stateProvider.state("scheme.list", {
    url: "/list",
    templateUrl: "src/view/scheme/scheme.list.php",
  });
  $stateProvider.state("scheme.add", {
    url: "/add",
    templateUrl: "src/view/scheme/scheme.add.php",
  });
  $stateProvider.state("scheme.view", {
    url: "/view",
    templateUrl: "src/view/scheme/scheme.view.php",
  });

  $stateProvider.state("users", {
    url: "/users",
    redirectTo: "users.list",
    templateUrl: "src/view/main.app.php",
    controller: "users",
  });
  $stateProvider.state("users.list", {
    url: "/list",
    templateUrl: "src/view/users/users.list.php",
  });

  $stateProvider.state("application", {
    url: "/application",
    redirectTo: "application.list",
    templateUrl: "src/view/main.app.php",
    controller: "application",
  });
  $stateProvider.state("application.list", {
    url: "/list",
    templateUrl: "src/view/application/application.list.php",
  });
  $stateProvider.state("application.form", {
    url: "/form",
    templateUrl: "src/view/application/application.form.php",
  });

  $stateProvider.state("forms", {
    url: "/forms",
    redirectTo: "forms.list",
    templateUrl: "src/view/main.app.php",
    controller: "form",
  });
  $stateProvider.state("forms.add", {
    url: "/add",
    templateUrl: "src/view/forms/forms.add.php",
  });
  $stateProvider.state("forms.sent", {
    url: "/sent",
    templateUrl: "src/view/forms/forms.sent.php",
  });

  $stateProvider.state("emailnotif", {
    url: "/emailnotif",
    redirectTo: "emailnotif.list",
    templateUrl: "src/view/main.app.php",
    controller: "emailnotif",
  });
  $stateProvider.state("emailnotif.list", {
    url: "/list",
    templateUrl: "src/view/emailnotif/emailnotif.list.php",
  });
  $stateProvider.state("emailnotif.add", {
    url: "/add",
    templateUrl: "src/view/emailnotif/emailnotif.add.php",
  });

  $stateProvider.state("reports", {
    url: "/reports",
    redirectTo: "reports.list",
    templateUrl: "src/view/main.app.php",
    controller: "reports",
  });
  $stateProvider.state("reports.list", {
    url: "/list",
    templateUrl: "src/view/reports/reports.list.php",
  });
  $stateProvider.state("reports.sor", {
    url: "/scholars",
    templateUrl: "src/view/reports/reports.scholars.php",
  });
  $stateProvider.state("reports.sapsr", {
    url: "/scholars-application-status",
    templateUrl: "src/view/reports/reports.sapsr.php",
  });
  $stateProvider.state("reports.enhr", {
    url: "/email-notification-history",
    templateUrl: "src/view/reports/reports.enhr.php",
  });
});
