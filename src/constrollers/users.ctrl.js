app.controller(
  "users",
  function ($scope, $state, $filter, $rest, $decrypt, $http, $uibModal) {
    document.title = "USER | SCHOLARLYSYNC";
    $scope.now = new Date();
    $scope.items_per_page = 50;
    $scope.current_page = 1;
    $scope.schid = localStorage.getItem("schformid");
    $scope.usertypeval = -1;

    $scope.users_list = function (usertype) {
      $rest.get(`user_list&usertype=${usertype}`).then(
        function success(res) {
          $scope.userlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.users_list($scope.usertypeval);

    $scope.user_filter = function (usertype) {
      $scope.users_list(usertype);
    };

    $scope.users_edit = function (ud) {
      $scope.show_modal("users");
      $scope.userobj = {
        id: ud.LoginID,
        username: ud.UserName,
        passwd: ud.PassWD,
        passwdt: ud.PassWDText,
        cpassword: "",
        npassword: "",
      };
    };
    $scope.users_add = function (obj) {
      if (confirm("Are you sure you want to add new user?")) {
        let userobj = {
          userid: obj.udid,
          username: obj.lname,
          passwd: obj.usernum ? obj.usernum : obj.lname,
          usertypetext: $scope.usertype(obj.usertype),
          usertypeid: obj.usertype,
        };
        $rest.post("users_add", userobj).then(
          function success(res) {
            alert("Add new user successfull");
            $state.reload();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.realod();
      }
    };
    $scope.users_update = function (obj) {
      if (obj.passwdt == obj.cpassword) {
        let userobj = {
          id: obj.LoginID,
          username: obj.username,
          passwd: obj.passwd,
          passwdtext: obj.passwdt,
          cpassword: obj.cpassword,
          npassword: obj.npassword,
        };
        $rest.post("users_update", userobj).then(
          function success(res) {
            alert("Update login details successfull");
            $state.reload();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert("Confirm password and old passowrd does not match");
      }
    };
    $scope.users_switch = function (obj) {
      var action = obj.UserLoginStatus == 1 ? "Active" : "Inactive";
      if (confirm("Are you sure you want to " + action + " this user?")) {
        let userobj = {
          id: obj.LoginID,
          status: obj.UserLoginStatus == 0 ? 1 : 0,
        };
        $rest.post("users_switch", userobj).then(
          function success(res) {
            $state.reload();
          },
          function error(err) {
            console.error(err);
          }
        );
      }
    };
    $scope.users_delete = function (id) {
      if (confirm("Are you sure you want to delete this user?")) {
        let userobj = {
          id: id,
        };
        $rest.post("users_delete", userobj).then(
          function success(res) {
            if (res.data == "success") {
              alert("Delete user successfull");
              $state.reload();
            } else {
              alert("Error deleting the user");
            }
          },
          function error(err) {
            console.error(err);
          }
        );
      }
    };

    $scope.get_studemp = function () {
      $rest.get("get_studemp").then(
        function success(res) {
          $scope.usersobj = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_studemp();

    $scope.userstatus = function (usid) {
      var status = "";
      if (usid == 0) {
        status = "Active";
      } else if (usid == 1) {
        status = "Inactive";
      } else if (usid == 2) {
        status = "Graduated";
      } else if (usid == 3) {
        status = "Transferred";
      } else if (usid == 4) {
        status = "Full Time";
      } else if (usid == 5) {
        status = "Part-Time";
      } else if (usid == 6) {
        status = "Consultant";
      } else if (usid == 7) {
        status = "Probationary";
      }
      return status;
    };
    $scope.usertype = function (utid) {
      var type = "";
      if (utid == 0) {
        type = "Administrator";
      } else if (utid == 1) {
        type = "Student";
      } else if (utid == 2) {
        type = "Coaches";
      } else if (utid == 3) {
        type = "Department Heads";
      } else if (utid == 4) {
        type = "Teachers";
      }
      return type;
    };

    // modal only
    $scope.show_modal = function (val) {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/${val}-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: val == "users" ? "md" : "lg",
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
