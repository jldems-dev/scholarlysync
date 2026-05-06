app.controller(
  "employee",
  function ($scope, $state, $filter, $rest, $decrypt, $http) {
    document.title = "EMPLOYEE | SCHOLARLYSYNC";
    $scope.now = new Date();
    let datePart = $scope.now.toISOString().slice(0, 10).replace(/-/g, "");
    let timePart = $scope.now.toTimeString().slice(0, 8).replace(/:/g, "");
    $scope.studnum = datePart + timePart;
    $scope.items_per_page = 50;
    $scope.current_page = 1;
    $scope.employeeid = localStorage.getItem("userformid");
    $scope.msg = "";
    $scope.filter = {
      usertypes: -1,
      department: -1,
    };

    $scope.empinfo = {
      id: 0,
      usernum: $scope.studnum,
      fname: "",
      mname: "",
      lname: "",
      dob: "",
      gender: 0,
      address: "",
      city: 0,
      brgy: "",
      pnumber: "",
      department: 0,
      jobtitle: 0,
      userstatus: -1,
      usertype: -1,
      hrdate: "",
      userdate: "",
      jobtitle: "",
      photo: "src/assets/images/no-image.png",
    };

    $scope.employee_list = function (department, usertypes) {
      $rest
        .get(`employee_list?department=${department}&usertype=${usertypes}`)
        .then(
          function success(res) {
            $scope.emplist = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.employee_list($scope.filter.department, $scope.filter.usertypes);
    $scope.employee_edit = function (userid) {
      if (userid > 0) {
        $rest.get(`employee_edit&id=${userid}`).then(
          function success(res) {
            $scope.empinfo = {
              id: res.data.userid,
              usernum: res.data.usernum,
              fname: res.data.fname,
              mname: res.data.mname,
              lname: res.data.lname,
              dob: res.data.dob,
              gender: res.data.gender,
              email: res.data.email,
              address: res.data.useraddress,
              city: res.data.city,
              brgy: res.data.brgy,
              pnumber: res.data.pnumber,
              department: res.data.department,
              userstatus: res.data.userstatus,
              usertype: res.data.usertype,
              hrdate: res.data.userdate,
              jobtitle: res.data.jobtitle,
              photo: res.data.photo
                ? "uploads/" + res.data.photo
                : "src/assets/images/no-image.png",
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.empinfo = {
          id: 0,
          usernum: $scope.studnum,
          fname: "",
          mname: "",
          lname: "",
          dob: "",
          gender: 0,
          address: "",
          city: 0,
          brgy: "",
          pnumber: "",
          department: 0,
          userstatus: -1,
          usertype: -1,
          hrdate: "",
          jobtitle: "",
          photo: "src/assets/images/no-image.png",
        };
      }
    };
    $scope.employee_edit($scope.employeeid);
    $scope.employee_add = function (empinfo) {
      if (
        empinfo.usernum?.trim() !== "" &&
        empinfo.fname?.trim() !== "" &&
        empinfo.lname?.trim() !== "" &&
        empinfo.mname?.trim() !== "" &&
        empinfo.address?.trim() !== "" &&
        empinfo.email?.trim() !== ""
      ) {
        if ($scope.isEmployeeInList(empinfo)) {
          alert("Employee is already in the list.");
        } else {
          let emp_obj = {
            usernum: empinfo.usernum,
            fname: empinfo.fname,
            mname: empinfo.mname,
            lname: empinfo.lname,
            email: empinfo.email,
            pnumber: empinfo.pnumber,
            dob: $filter("date")(empinfo.dob, "yyyy-MM-dd"),
            gender: empinfo.gender,
            address: empinfo.address,
            city: empinfo.city,
            brgy: empinfo.brgy,
            hrdate: $filter("date")(empinfo.hrdate, "yyyy-MM-dd"),
            jobtitle: empinfo.jobtitle,
            department: empinfo.department,
            userstatus: empinfo.userstatus,
            usertype: empinfo.usertype,
          };
          $rest.post("employee_add", emp_obj).then(
            function success(res) {
              localStorage.setItem("userformid", res.data.user_id);
              $state.go("employee.edit", {}, { reload: true });
              let sentemail = [
                $scope.upload_file($scope.fileSelected, res.data.user_id),
                $scope.sent_email(emp_obj, res.data.user_id),
              ];
              Promise.all(sentemail)
                .then(() => {
                  alert("Employee form submission successful!");
                })
                .catch((err) => console.error("Error updating data:", err));
            },
            function error(err) {
              console.error(err);
            }
          );
        }
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
        $scope.employeeid = 0;
      }
    };
    $scope.employee_update = function (empinfo) {
      if (empinfo.usernum || empinfo.email || empinfo.address) {
        let emp_obj = {
          userid: empinfo.id,
          usernum: empinfo.usernum,
          fname: empinfo.fname,
          mname: empinfo.mname,
          lname: empinfo.lname,
          email: empinfo.email,
          pnumber: empinfo.pnumber,
          dob: empinfo.dob,
          gender: empinfo.gender,
          address: empinfo.address,
          city: empinfo.city,
          brgy: empinfo.brgy,
          hrdata: empinfo.hrdate,
          jobtitle: empinfo.jobtitle,
          department: empinfo.department,
          userstatus: empinfo.userstatus,
          usertype: empinfo.usertype,
        };
        $rest.post("employee_update", emp_obj).then(
          function success(res) {
            alert("employee Form Update Successful!");
            $scope.upload_file($scope.fileSelected, empinfo.id);
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
        $scope.employeeid = 0;
      }
    };

    $scope.employee_delete = function (userid) {
      let emp_obj = {
        id: userid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("employee_delete", emp_obj).then(
          function success(res) {
            alert("Delete employee Successful!");
            localStorage.removeItem("studformid");
            $state.go("employee.list", {}, { reload: true });
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };
    $scope.employee_new = function () {
      localStorage.removeItem("userformid");
      $state.reload();
    };

    $scope.sent_email = function (empinfo, userid) {
      let email_obj = {
        addaddress: empinfo.email,
        subject: "Welcome to Scholarlysync",
        body: `<div style='color: black;'>
        <p>Dear ${empinfo.lname},</p>
        <p>We’re excited to inform you that your account has been successfully created on Scholarlysync by our administrator.</p>
        <p>Here’s how to get started: </p>
        <ul>
            <li>
                <strong>Log In to Your Account:</strong>
                <p>Use the credentials below to access your account:</p>
                <ul>
                    <li><strong>Username: </strong> ${empinfo.lname}</li>
                    <li><strong>Password:</strong> ${empinfo.usernum}</li>
                </ul>
            </li>
        </ul>

        <p>Best regards,<br>
        Colegio De Sta Ana De Victorias<br>
        399-3286</p>
      </div>`,
      };
      $rest.post("sent_email", email_obj).then(
        function success(res) {
          $scope.emailnotif(email_obj, empinfo, userid);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.emailnotif = function (obj, empinfo, userid) {
      let enobj = {
        userid: empinfo.userid ? empinfo.userid : userid,
        email: empinfo.email,
        subject: obj.subject,
        body: obj.body,
        attachment: "",
      };
      $rest.post("emailnotif", enobj).then(
        function success(res) {
          $scope.emailoading = false;
          $state.reload();
        },
        function error(err) {
          console.error(err);
        }
      );
    };

    $scope.employee_filter = function (filter) {
      $scope.employee_list(filter.department, filter.usertypes);
    };

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

    $scope.usertype = function (usertypeid) {
      var status = "";
      if (usertypeid == 0) {
        status = "Administrator";
      } else if (usertypeid == 1) {
        status = "Student";
      } else if (usertypeid == 2) {
        status = "Coaches";
      } else if (usertypeid == 3) {
        status = "Department Heads";
      } else if (usertypeid == 4) {
        status = "Teachers";
      }
      return status;
    };

    // uploading file
    $scope.upload_file = function (file, photo_id) {
      var formData = new FormData();
      formData.append("imageFile", file);
      formData.append("photo_id", photo_id);
      if (file) {
        $http
          .post("api/upload_file", formData, {
            transformRequest: angular.identity,
            headers: { "Content-Type": undefined },
          })
          .then(
            function (response) {
              console.log(response.data);
            },
            function (error) {
              console.error(error);
            }
          );
      } else {
        console.log("Insert File to Upload");
      }
    };
    $scope.file_change = function () {
      var fileInput = document.getElementById("pimg");
      var files = fileInput.files;

      var file = files[0];
      console.log(file);
      var reader = new FileReader();
      reader.onload = function (event) {
        $scope.$apply(function () {
          $scope.empinfo.photo = event.target.result;
          $scope.fileSelected = file;
        });
      };
      reader.readAsDataURL(file);
    };
    $scope.remove_img = function (photo) {
      $scope.empinfo.photo = photo;
      let img_obj = {
        photo_id: $scope.employeeid,
      };
      $rest.post("remove_img", img_obj).then(
        function success(res) {
          alert("Image file remove Successful!");
          $scope.employee_edit($scope.employeeid);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.state_go = function (url, userid) {
      if (userid > 0) {
        localStorage.setItem("userformid", userid);
      } else {
        localStorage.setItem("userformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };

    $scope.isEmployeeInList = function (emp) {
      if (Array.isArray($scope.emplist)) {
        return $scope.emplist.some(
          (s) =>
            s.fname.trim().toLowerCase() === emp.fname.trim().toLowerCase() &&
            s.mname.trim().toLowerCase() === emp.mname.trim().toLowerCase() &&
            s.lname.trim().toLowerCase() === emp.lname.trim().toLowerCase() &&
            s.email.trim().toLowerCase() === emp.email.trim().toLowerCase()
        );
      } else {
        return false;
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
