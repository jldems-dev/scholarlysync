app.controller("login", function ($scope, $state, $filter, $rest, $decrypt) {
  document.title = "LOGIN | SCHOLARLYSYNC";

  $scope.user = {
    username: "",
    password: "",
  };

  $scope.login_user = function (user) {
    if (user.username == "" || user.password == "") {
      $scope.msg = "Invalid username or password!";
    } else {
      let userObj = {
        username: user.username,
        password: user.password,
      };
      $rest.post("login_user", userObj).then(
        function success(res) {
          if (res.data.UserLoginStatus == 0) {
            $state.go("home", {}, { reload: true });
            var encryptedUser = CryptoJS.AES.encrypt(
              JSON.stringify(res.data),
              "Passphrase"
            );
            localStorage.setItem("sms_user", encryptedUser);
          } else {
            alert("Your account is currently inactive.");
          }
        },
        function error(err) {
          alert(err.data.msg);
          console.error(err);
        }
      );
    }
  };

  $scope.check_loggedIn = function () {
    let sms_user = localStorage.getItem("sms_user");
    if (sms_user) {
      $state.go("home");
    } else {
      $state.go("login");
    }
  };
  $scope.check_loggedIn();
});
