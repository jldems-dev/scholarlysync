app.controller(
  "reports",
  function (
    $scope,
    $state,
    $filter,
    $rest,
    $decrypt,
    $http,
    $uibModal,
    $location
  ) {
    document.title = "SCHOLARSHIP FORMS | SCHOLARLYSYNC";
    $scope.now = new Date();
    $scope.scholarstatus = 0;
    $scope.appstatus = -1;
    $scope.schtype = 0;
    $scope.fromdate = "";
    $scope.ysfrom = "";
    $scope.ysto = "";
    $scope.todate = "";
    $scope.asofdate = -1;

    $scope.yearsfrom = [
      { years: 2020 },
      { years: 2021 },
      { years: 2022 },
      { years: 2023 },
      { years: 2024 },
    ];
    $scope.yearsto = [
      { years: 2020 },
      { years: 2021 },
      { years: 2022 },
      { years: 2023 },
      { years: 2024 },
    ];
    $scope.appstatuslist = [
      { value: 0, name: "Submitted" },
      { value: 1, name: "Under Review" },
      { value: 2, name: "Checking Documents" },
      { value: 3, name: "Approved" },
      { value: 4, name: "Rejected" },
      { value: 5, name: "Hold" },
      { value: 6, name: "Open Application" },
    ];

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.report_scholars = function (schlarsts, schtype, ysfrom, ysto) {
      $rest
        .get(
          `scholar_list?schlarsts=${schlarsts}&schtype=${schtype}&ysfrom=${ysfrom}&ysto=${ysto}`
        )
        .then(
          function success(res) {
            $scope.srlist = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.report_scholars(
      $scope.scholarstatus,
      $scope.schtype,
      $scope.ysfrom,
      $scope.ysto
    );
    $scope.report_application = function (status, type, fromdate, todate) {
      let ffromdate = $filter("date")(fromdate, "yyyy-MM-dd");
      let ftodate = $filter("date")(todate, "yyyy-MM-dd");
      $rest
        .get(
          `report_application?status=${status}&type=${type}&fromdate=${ffromdate}&todate=${ftodate}`
        )
        .then(
          function success(res) {
            $scope.ra_list = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.report_application(
      $scope.appstatus,
      $scope.schtype,
      $scope.fromdate,
      $scope.todate
    );
    $scope.report_emailnotif = function (fromdate, todate) {
      let ffromdate = $filter("date")(fromdate, "yyyy-MM-dd");
      let ftodate = $filter("date")(todate, "yyyy-MM-dd");
      $rest
        .get(`report_emailnotif?fromdate=${ffromdate}&todate=${ftodate}`)
        .then(
          function success(res) {
            $scope.re_list = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.report_emailnotif($scope.fromdate, $scope.todate);

    $scope.aplstatus = function (aplid) {
      var status = "";
      if (aplid == 0) {
        status = "Submitted";
      } else if (aplid == 1) {
        status = "Under Review";
      } else if (aplid == 2) {
        status = "Checking Documents";
      } else if (aplid == 3) {
        status = "Approved";
      } else if (aplid == 4) {
        status = "Rejected";
      } else if (aplid == 5) {
        status = "Hold";
      } else if (aplid == 6) {
        status = "Open Application";
      }
      return status;
    };

    //filter data
    $scope.get_scht = function () {
      $rest.get("sch_list").then(
        function success(res) {
          $scope.schtlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_scht();

    $scope.scholar_status = function (status) {
      let val = "";

      if (status == 1) {
        val = "Active";
      } else if (status == 2) {
        val = "Stopped";
      } else if (status == 3) {
        val = "Graduate";
      } else if (status == 4) {
        val = "Alumni";
      } else if (status == 5) {
        val = "Dropout";
      } else if (status == 6) {
        val = "Transferred";
      }

      return val;
    };

    // print
    $scope.print = function (divId, name) {
      $(".titlename").html(name + " LIST");
      printJS({
        printable: divId,
        type: "html",
        documentTitle: "Print Example",
        css: [
          "src/view/print/print.css",
          "assets/bootstrap/css/bootstrap.min.css",
        ],
        scanStyles: false,
      });
    };
  }
);
