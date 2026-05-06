app.controller(
  "scheme",
  function ($scope, $state, $filter, $rest, $decrypt, $uibModal, $http) {
    document.title = "SCHEME | SCHOLARLYSYNC";
    $scope.dnow = new Date();
    $scope.items_per_page = 15;
    $scope.current_page = 1;
    $scope.schid = localStorage.getItem("schformid");
    $scope.progressVisible = false;
    $scope.fileLoaded = 0;
    $scope.uploadedFiles = [];

    $scope.schtype = 0;
    $scope.schstatus = 0;

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";
    $scope.schinfo = {
      id: 0,
      schname: "",
      schtype: 0,
      schdate: "",
      schstatus: 0,
      respsch: 0,
      amount: "",
      criteria: "",
      docrequired: "",
      fundsource: "",
    };

    $scope.scheme_list = function (schtype, schstatus) {
      $rest.get(`scheme_list?schtype=${schtype}&schstatus=${schstatus}`).then(
        function success(res) {
          $scope.schlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.scheme_list($scope.schtype, $scope.schstatus);

    $scope.scheme_edit = function (schid) {
      if (schid > 0) {
        $rest.get(`scheme_edit&id=${schid}`).then(
          function success(res) {
            $scope.schinfo = {
              id: res.data.schid,
              schname: res.data.schname,
              schtype: res.data.schtype,
              schdate: res.data.awardate,
              schstatus: res.data.schstatus,
              category: res.data.category,
              amount: res.data.awardamnt,
              criteria: res.data.criteria,
              docrequired: res.data.docrequired,
              fundsource: res.data.fundsource,
              respsch: res.data.respsch,
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.schinfo = {
          id: 0,
          schname: "",
          schtype: 0,
          schdate: "",
          category: 0,
          schstatus: 0,
          respsch: 0,
          amount: "",
          criteria: "",
          docrequired: "",
          fundsource: "",
        };
      }
    };
    $scope.scheme_edit($scope.schid);

    $scope.scheme_add = function (schinfo) {
      if (schinfo.schname || schinfo.criteria || schinfo.docrequired) {
        let sch_obj = {
          schname: schinfo.schname,
          schtype: schinfo.schtype,
          schdate: schinfo.schdate,
          schstatus: schinfo.schstatus,
          amount: schinfo.amount,
          criteria: schinfo.criteria,
          docrequired: schinfo.docrequired,
          fundsource: schinfo.fundsource,
          respsch: schinfo.respsch,
          createdby: $scope.userid.UserID,
        };
        $rest.post("scheme_add", sch_obj).then(
          function success(res) {
            localStorage.setItem("schformid", res.data.sch_id);
            alert("Scheme Form Submission Successful!");
            $state.reload();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
        $scope.studentid = 0;
      }
    };
    $scope.scheme_update = function (schinfo) {
      if (schinfo.schname || schinfo.criteria || schinfo.docrequired) {
        let sch_obj = {
          schid: schinfo.id,
          schname: schinfo.schname,
          schtype: schinfo.schtype,
          schdate: schinfo.schdate,
          schstatus: schinfo.schstatus,
          category: schinfo.category,
          amount: schinfo.amount,
          criteria: schinfo.criteria,
          docrequired: schinfo.docrequired,
          fundsource: schinfo.fundsource,
          respsch: schinfo.respsch,
        };
        $rest.post("scheme_update", sch_obj).then(
          function success(res) {
            alert("Scheme Form Update Successful!");
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
        $scope.schid = 0;
      }
    };

    $scope.scheme_delete = function (schid) {
      let sch_obj = {
        id: schid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("scheme_delete", sch_obj).then(
          function success(res) {
            alert("Delete Scheme Successful!");
            localStorage.removeItem("schformid");
            $state.go("scheme.list", {}, { reload: true });
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };
    $scope.scheme_new = function () {
      localStorage.removeItem("schformid");
      $state.reload();
    };

    //adding application for apply button
    $scope.application_add = function () {
      let appobj = {
        schid: $scope.schid,
        userid: $scope.userid.UserID,
        major: "",
        gpa: 0,
        paia: "",
        hincome: "",
        ndh: 0,
        rfn: "",
        crfa: 0,
        dyhes: 0,
        aplschid: 0,
        msgcnrn: "",
        wayapp: "",
        aplstatus: 6,
      };
      $rest.post("application_add", appobj).then(
        function success(res) {
          if (res.data.status == "applied") {
            alert(
              "Our records indicate that you have already applied for this scholarship."
            );
          } else {
            localStorage.setItem("schformid", $scope.schid);
            localStorage.setItem("studformid", $scope.userid.UserID);
            localStorage.setItem("appformid", res.data.apl_id);
            $state.go("application.form", {}, { reload: true });
          }
        },
        function error(err) {
          console.error(err);
        }
      );
    };

    //course
    $scope.get_course = function () {
      $rest.get("course_list").then(
        function success(res) {
          $scope.courselist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_course();
    //year & section
    $scope.get_ys = function () {
      $rest.get("yearsec_list").then(
        function success(res) {
          $scope.yslist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_ys();
    //department
    $scope.get_dept = function () {
      $rest.get("dept_list").then(
        function success(res) {
          $scope.deptlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_dept();
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
    $scope.get_scholars = function () {
      $rest
        .get(`scholar_list?schlarsts=${0}&schtype=${0}&ysfrom=${""}&ysto=${""}`)
        .then(
          function success(res) {
            if (res.data != "") {
              let matchingData = res.data.filter(
                (item) => item.UserID === $scope.userid.UserID
              );
              $scope.chkinfo = {
                approved: matchingData.length,
              };
            }
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.get_scholars();
    $scope.employee_list = function () {
      $rest.get(`employee_list?department=${-1}&usertype=${-1}`).then(
        function success(res) {
          $scope.emplist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.employee_list();

    $scope.getReadableFileSize = function (bytes) {
      if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return "-";
      var units = ["bytes", "KB", "MB", "GB", "TB", "PB"];
      var number = Math.floor(Math.log(bytes) / Math.log(1024));
      var value = (bytes / Math.pow(1024, Math.floor(number))).toFixed(2);
      return value + " " + units[number];
    };
    $scope.schmestatus = function (status) {
      let val = "";

      if (status == -1) {
        val = "Apply Now";
      } else if (status == 0) {
        val = "Submitted";
      } else if (status == 1) {
        val = "Under Review";
      } else if (status == 2) {
        val = "Upload Documents";
      } else if (status == 3) {
        val = "Approved";
      } else if (status == 4) {
        val = "Rejected";
      } else if (status == 5) {
        val = "Hold";
      }

      return val;
    };

    $scope.state_go = function (url, schid) {
      if (schid > 0) {
        localStorage.setItem("schformid", schid);
      } else {
        localStorage.setItem("schformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };
    // modal only
    $scope.show_modal = function (val) {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/${val}-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: val == "uploadfiles" ? "md" : "lg",
        // windowClass: "cls",
      });
      $scope.closeModal = function () {
        $uibModalInstance.close();
      };
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
