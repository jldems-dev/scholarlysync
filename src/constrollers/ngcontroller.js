app.controller(
  "ngcontroller",
  function (
    $scope,
    Idle,
    Keepalive,
    $uibModal,
    SweetAlert2,
    $decrypt,
    $state,
    $rest
  ) {
    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";
    $scope.logout_user = function () {
      SweetAlert2.fire({
        title: "Continue to logout?",
        text: "Your about to logout from the system!",
        icon: "question",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonColor: "#142b71",
        cancelButtonColor: "#E4E4E4",
        cancelButtonClass: "text-dark",
        confirmButtonText: "Continue",
        position: "center",
      }).then((result) => {
        if (result.value) {
          localStorage.clear();
          // location.reload(true);
          $state.go("login");
        }
      });
    };
    $scope.user_info = function () {
      let descrypted_o = $decrypt.decrypted(localStorage.getItem("sms_user"));
      let sms_user = JSON.parse(descrypted_o);
      $scope.info = {
        fullname: sms_user.userName,
        shortname: sms_user.shortName,
        usertype: sms_user.UserTypeText,
        photo: sms_user.Photo,
      };
    };
    $scope.user_info();

    $scope.toggleMenu = function () {
      document.body.classList.toggle("sb-sidenav-toggled");
      localStorage.setItem(
        "sb|sidebar-toggle",
        document.body.classList.contains("sb-sidenav-toggled")
      );
    };
    $scope.goto = function (url) {
      $state.go(url, {}, { reload: true });
    };
  }
);
