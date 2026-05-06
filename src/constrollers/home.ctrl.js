app.controller("home", function ($scope, $state, $filter, $rest, $decrypt) {
  document.title = "HOME | SCHOLARLYSYNC";

  let sms_user = localStorage.getItem("sms_user");
  let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
  $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

  $scope.todayDate = $filter("date")(new Date(), "MMM dd, yyyy");
  var date = new Date();
  date.setDate(date.getDate() - 7);
  $scope.currentYear = date.getFullYear();
  $scope.previousYear = $scope.currentYear - 1;
  $scope.startDate = date;
  $scope.endDate = new Date();

  $scope.check_loggedIn = function () {
    let sms_user = localStorage.getItem("sms_user");
    if (sms_user) {
      $state.go("home");
    } else {
      $state.go("login");
    }
  };
  $scope.check_loggedIn();

  $scope.get_total = function (startDate, endDate) {
    $scope.sch = {
      qty: 0,
      percent: 0,
    };
    $scope.em = {
      qty: 0,
      percent: 0,
    };
    $scope.app = {
      qty: 0,
      percent: 0,
    };
    $scope.sc = {
      qty: 0,
      percent: 0,
    };
    let fromDate = $filter("date")(startDate, "yyyy-MM-dd");
    let toDate = $filter("date")(endDate, "yyyy-MM-dd");
    $rest
      .get(`get_total_scholars&startDate=${fromDate}&endDate=${toDate}`)
      .then(
        function success(res) {
          $scope.sc = {
            qty: !res.data.ttqty ? 0 : res.data.ttqty,
            percent: (res.data.ttqty / 100) * 100,
          };
        },
        function error(err) {
          console.error(err);
        }
      );
    $rest.get(`get_total_sch&startDate=${fromDate}&endDate=${toDate}`).then(
      function success(res) {
        $scope.sch = {
          qty: !res.data.ttqty ? 0 : res.data.ttqty,
          percent: (res.data.ttqty / 100) * 100,
        };
      },
      function error(err) {
        console.error(err);
      }
    );
    $rest.get(`get_total_emp&startDate=${fromDate}&endDate=${toDate}`).then(
      function success(res) {
        $scope.em = {
          qty: !res.data.ttqty ? 0 : res.data.ttqty,
          percent: (res.data.ttqty / 100) * 100,
        };
      },
      function error(err) {
        console.error(err);
      }
    );
    $rest.get(`get_total_app&startDate=${fromDate}&endDate=${toDate}`).then(
      function success(res) {
        $scope.app = {
          qty: !res.data.ttqty ? 0 : res.data.ttqty,
          percent: (res.data.ttqty / 100) * 100,
        };
      },
      function error(err) {
        console.error(err);
      }
    );
  };
  $scope.get_total($scope.startDate, $scope.endDate);

  $scope.home_scheme = function () {
    $rest.get("home_scheme").then(
      function success(res) {
        $scope.schlist = res.data;
      },
      function error(err) {
        console.error(err);
      }
    );
  };
  $scope.home_scheme();

  $scope.home_scholars = function () {
    $rest.get("home_scholars").then(
      function success(res) {
        $scope.srlist = res.data;
      },
      function error(err) {
        console.error(err);
      }
    );
  };
  $scope.home_scholars();

  $scope.home_scholars_graduate = function () {
    $rest.get("home_scholars_graduate").then(
      function success(res) {
        $scope.srglist = res.data;
      },
      function error(err) {
        console.error(err);
      }
    );
  };
  $scope.home_scholars_graduate();

  $scope.state_go = function (url, schid) {
    if (schid > 0) {
      localStorage.setItem("schformid", schid);
    } else {
      localStorage.setItem("schformid", 0);
    }
    $state.go(url, {}, { reload: true });
  };
});
