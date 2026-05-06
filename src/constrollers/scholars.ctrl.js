app.controller(
  "scholars",
  function ($scope, $state, $rest, $uibModal, $decrypt) {
    document.title = "SCHOLARS | SCHOLARLYSYNC";
    $scope.now = new Date();
    $scope.items_per_page = 50;
    $scope.current_page = 1;

    $scope.scholarstatus = 0;
    $scope.schtype = 0;
    $scope.ysfrom = "";
    $scope.ysto = "";
    $scope.upschpstatus = 0;
    $scope.schstatus = false;
    $scope.selectedSch = [];
    $scope.alertShown = false;
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

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.scholar_list = function (schlarsts, schtype, ysfrom, ysto) {
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
    $scope.scholar_list(
      $scope.scholarstatus,
      $scope.schtype,
      $scope.ysfrom,
      $scope.ysto
    );
    $scope.scholar_edit = function (scholarid) {
      if (scholarid > 0) {
        $scope.schstatus = true;
      }
    };
    $scope.scholar_update = function (scholar) {
      let sch_obj = {
        scholarid: scholar.ScholarID,
        schstatus: scholar.ScholarStatus,
        userid: scholar.UserID,
      };
      $scope.alertShown = false;
      $rest.post("scholar_update", sch_obj).then(
        function success(res) {
          if (!$scope.alertShown) {
            alert("Scholar status update successful!");
            $scope.alertShown = true;
          }
          $scope.schstatus = false;
          $state.reload();
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.scholar_delete = function (srid) {
      let scholar_obj = {
        id: srid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("scholar_delete", scholar_obj).then(
          function success(res) {
            alert("Delete Scholar Successful!");
            $state.reload();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };
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
    $scope.scholar_selected = function (scholar, upschpstatus) {
      scholar.map((scholars) => {
        let scholar = {
          ScholarID: scholars.ScholarID,
          ScholarStatus: upschpstatus,
          UserID: scholars.UserID,
        };
        $scope.scholar_update(scholar);
      });
    };

    //scholarship type
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

    $scope.student_compliance = function (studid) {
      $rest.get(`student_compliance&id=${studid}`).then(
        function success(res) {
          $scope.file_obj = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.student_compliance($scope.studentid);

    $scope.state_go = function (url, studid) {
      if (studid > 0) {
        localStorage.setItem("studformid", studid);
      } else {
        localStorage.setItem("studformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };

    $scope.all_sch = function (obj, page) {
      $scope.all_schid = !$scope.all_schid;

      let pages = page ? page : 1;

      let startIndex = (pages - 1) * $scope.items_per_page;
      let endIndex = Math.min(startIndex + $scope.items_per_page, obj.length);

      let OnCurrentPage = obj.slice(startIndex, endIndex);

      angular.forEach(OnCurrentPage, function (obj) {
        obj.selected = $scope.all_schid;
        $scope.selected_sch(obj);
      });
    };
    $scope.selected_sch = function (obj) {
      var index = $scope.selectedSch.indexOf(obj);
      if (index > -1) {
        $scope.selectedSch.splice(index, 1);
      } else {
        $scope.selectedSch.push(obj);
      }
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
