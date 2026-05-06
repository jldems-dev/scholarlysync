app.controller(
  "student",
  function ($scope, $state, $filter, $rest, $decrypt, $http) {
    document.title = "STUDENT | SCHOLARLYSYNC";
    $scope.now = new Date();
    let datePart = $scope.now.toISOString().slice(0, 10).replace(/-/g, "");
    let timePart = $scope.now.toTimeString().slice(0, 8).replace(/:/g, "");
    $scope.studnum = datePart + timePart;
    $scope.items_per_page = 50;
    $scope.current_page = 1;
    $scope.studentid = localStorage.getItem("studformid");

    $scope.departments = 0;
    $scope.course = 0;
    $scope.year = 0;
    $scope.fromDate = "";
    $scope.toDate = "";

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.studinfo = {
      id: 0,
      studnum: $scope.studnum,
      fname: "",
      mname: "",
      lname: "",
      dob: "",
      gender: 0,
      address: "",
      city: 0,
      brgy: "",
      pnumber: "",
      course: 0,
      yas: 0,
      department: 0,
      studstatus: 0,
      benefid: 0,
      schid: 0,
      photo: "src/assets/images/no-image.png",
    };

    $scope.student_list = function (dept, course, year) {
      $rest.get(`student_list?dept=${dept}&course=${course}&year=${year}`).then(
        function success(res) {
          $scope.studlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.student_list($scope.departments, $scope.course, $scope.year);
    $scope.student_edit = function (studid) {
      if (studid > 0) {
        $rest.get(`student_edit&id=${studid}`).then(
          function success(res) {
            $scope.studinfo = {
              id: res.data.studid,
              studnum: res.data.studnum,
              fname: res.data.fname,
              mname: res.data.mname,
              lname: res.data.lname,
              dob: res.data.dob,
              gender: res.data.gender,
              email: res.data.email,
              address: res.data.studaddress,
              city: res.data.city,
              brgy: res.data.brgy,
              pnumber: res.data.pnumber,
              course: res.data.studcourse,
              yas: res.data.yas,
              department: res.data.department,
              studstatus: res.data.studstatus,
              benefid: res.data.benefid,
              schid: res.data.schid,
              photo: res.data.studphoto
                ? "uploads/" + res.data.studphoto
                : "src/assets/images/no-image.png",
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.studinfo = {
          id: 0,
          studnum: $scope.studnum,
          fname: "",
          mname: "",
          lname: "",
          dob: "",
          gender: 0,
          address: "",
          city: 0,
          brgy: "",
          pnumber: "",
          course: 0,
          yas: 0,
          department: 0,
          studstatus: 0,
          benefid: 0,
          schid: 0,
          photo: "src/assets/images/no-image.png",
        };
      }
    };
    $scope.student_edit($scope.studentid);
    $scope.student_add = function (studinfo) {
      if (
        studinfo.studnum?.trim() !== "" &&
        studinfo.fname?.trim() !== "" &&
        studinfo.lname?.trim() !== "" &&
        studinfo.mname?.trim() !== "" &&
        studinfo.address?.trim() !== "" &&
        studinfo.email?.trim() !== ""
      ) {
        if ($scope.isStudentInList(studinfo)) {
          alert("Student is already in the list.");
        } else {
          let stud_obj = {
            studnum: studinfo.studnum,
            fname: studinfo.fname,
            mname: studinfo.mname,
            lname: studinfo.lname,
            email: studinfo.email,
            pnumber: studinfo.pnumber,
            dob: $filter("date")(studinfo.dob, "yyyy-MM-dd"),
            gender: studinfo.gender,
            address: studinfo.address,
            city: studinfo.city,
            brgy: studinfo.brgy,
            course: studinfo.course,
            yas: studinfo.yas,
            department: studinfo.department,
            studstatus: studinfo.studstatus,
            benefid: studinfo.benefid,
          };
          $scope.emailoading = true;
          $rest.post("student_add", stud_obj).then(
            function success(res) {
              localStorage.setItem("studformid", res.data.user_id);
              $state.go("student.edit", {}, { reload: true });
              let sentemail = [
                $scope.upload_file($scope.fileSelected, res.data.user_id),
                $scope.sent_email(stud_obj, res.data.user_id, 2),
              ];
              Promise.all(sentemail)
                .then(() => {
                  alert("Student Form Submission Successful!");
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
        $scope.studentid = 0;
      }
    };
    $scope.student_update = function (studinfo) {
      let stud_obj = {
        studid: studinfo.id,
        studnum: studinfo.studnum,
        fname: studinfo.fname,
        mname: studinfo.mname,
        lname: studinfo.lname,
        email: studinfo.email,
        pnumber: studinfo.pnumber,
        dob: studinfo.dob,
        gender: studinfo.gender,
        address: studinfo.address,
        city: studinfo.city,
        brgy: studinfo.brgy,
        course: studinfo.course,
        yas: studinfo.yas,
        department: studinfo.department,
        studstatus: studinfo.studstatus,
        benefid: studinfo.benefid,
        schid: studinfo.schid,
      };
      $scope.emailoading = true;
      $rest.post("student_update", stud_obj).then(
        function success(res) {
          alert("Student Form Update Successful!");
          $scope.upload_file($scope.fileSelected, studinfo.id);
          if (res.data.scholars == 0) {
            $scope.sent_email(
              studinfo,
              studinfo.id,
              res.data.scholars,
              res.data.schlist
            );
          } else {
            $scope.emailoading = false;
          }
        },
        function error(err) {
          console.error(err);
        }
      );
    };

    $scope.student_delete = function (studid) {
      let stud_obj = {
        id: studid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("student_delete", stud_obj).then(
          function success(res) {
            alert("Delete Student Successful!");
            localStorage.removeItem("studformid");
            $state.go("student.list", {}, { reload: true });
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };
    $scope.student_new = function () {
      localStorage.removeItem("studformid");
      $state.reload();
    };

    $scope.sent_email = function (studinfo, userid, schchk, schlist) {
      let email_obj = {};
      if (schchk == 0) {
        email_obj = {
          addaddress: studinfo.email,
          subject:
            "Congratulations! You've Qualified for the Dependent Scholarship",
          body: `<div style='color: black;'>
            <p>Dear ${studinfo.lname},</p>
            <p>We are thrilled to inform you that you have automatically qualified for the Dependent Scholarship 
            based on your parent’s association with ScholarlySync. As a result, we’ve created a 
            scholarship for you to support your education.</p>
            <ul>
                <li>
                    <strong>Scholarship Details:</strong>
                    <ul>
                        <li><strong>Scholarship: </strong> ${schlist.ScholarshipName}</li>
                        <li><strong>Scholarship Type:</strong> ${schlist.TypeName}</li>
                    </ul>
                </li>
            </ul>

            <p>If you have any questions or need assistance, please don’t hesitate to reach out to us at Scholarship Admin Office</p>
            <p>Congratulations again, and we look forward to supporting your academic journey!!</p>

            <p>Best regards,<br>
            Colegio De Sta Ana De Victorias<br>
            399-3286</p>
          </div>`,
        };
      } else if (schchk == 2) {
        email_obj = {
          addaddress: studinfo.email,
          subject: "Welcome to Scholarlysync",
          body: `<div style='color: black;'>
        <p>Dear ${studinfo.lname},</p>
        <p>We’re excited to inform you that your account has been successfully created on Scholarlysync by our administrator. You can now begin exploring available scholarships and opportunities. </p>
        <p>Here’s how to get started: </p>
        <ul>
            <li>
                <strong>Log In to Your Account:</strong>
                <p>Use the credentials below to access your account:</p>
                <ul>
                    <li><strong>Username: </strong> ${studinfo.lname}</li>
                    <li><strong>Password:</strong> ${studinfo.studnum}</li>
                </ul>
            </li>
            <li style='margin-top: 10px;'>
                <strong>Explore Scholarships:</strong>
                <p>Browse through our extensive list of scholarships and find the ones that match your profile.</p>
            </li>
        </ul>

        <p>Welcome aboard, and best of luck on your scholarship journey!</p>

        <p>Best regards,<br>
        Colegio De Sta Ana De Victorias<br>
        399-3286</p>
      </div>`,
        };
      }
      $rest.post("sent_email", email_obj).then(
        function success(res) {
          $scope.emailnotif(email_obj, studinfo, userid);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.emailnotif = function (obj, studinfo, userid) {
      let enobj = {
        userid: studinfo.userid ? studinfo.userid : userid,
        email: studinfo.email,
        subject: obj.subject,
        body: obj.body,
        attachment: "",
        fromuserid: 0,
        fromemail: "scholarlysync320@gmail.com",
        emailstatus: 2,
        emailtype: 2,
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
    $scope.scheme_list = function () {
      $rest.get(`scheme_list?schtype=${0}&schstatus=${0}`).then(
        function success(res) {
          $scope.schlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.scheme_list();

    $scope.student_compliance = function (studid, fromdate, todate) {
      let fromDate = $filter("date")(fromdate, "yyyy-MM-dd");
      let toDate = $filter("date")(todate, "yyyy-MM-dd");

      $rest
        .get(
          `student_compliance?id=${studid}&fromdate=${fromDate}&todate=${toDate}`
        )
        .then(
          function success(res) {
            $scope.file_obj = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.student_compliance($scope.studentid, $scope.fromDate, $scope.toDate);
    $scope.getReadableFileSize = function (bytes) {
      if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return "-";
      var units = ["bytes", "KB", "MB", "GB", "TB", "PB"];
      var number = Math.floor(Math.log(bytes) / Math.log(1024));
      var value = (bytes / Math.pow(1024, Math.floor(number))).toFixed(2);
      return value + " " + units[number];
    };
    $scope.student_status = function (status) {
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
              /* $state.reload(); */
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
      var reader = new FileReader();
      reader.onload = function (event) {
        $scope.$apply(function () {
          $scope.studinfo.photo = event.target.result;
          $scope.fileSelected = file;
        });
      };
      reader.readAsDataURL(file);
    };
    $scope.remove_img = function (photo) {
      $scope.studinfo.photo = photo;
      let img_obj = {
        photo_id: $scope.studentid,
      };
      $rest.post("remove_img", img_obj).then(
        function success(res) {
          alert("Image file remove Successful!");
          $scope.student_edit($scope.studentid);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.state_go = function (url, studid) {
      if (studid > 0) {
        localStorage.setItem("studformid", studid);
      } else {
        localStorage.setItem("studformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };

    $scope.isStudentInList = function (student) {
      if (Array.isArray($scope.studlist)) {
        return $scope.studlist.some(
          (s) =>
            s.fname.trim().toLowerCase() ===
              student.fname.trim().toLowerCase() &&
            s.mname.trim().toLowerCase() ===
              student.mname.trim().toLowerCase() &&
            s.lname.trim().toLowerCase() ===
              student.lname.trim().toLowerCase() &&
            s.email.trim().toLowerCase() === student.email.trim().toLowerCase()
        );
      } else {
        return false;
      }
    };

    $scope.downloadFile = function (fileLocation) {
      const link = document.createElement("a");
      link.href = "uploads/filesmanager/" + fileLocation;
      link.download = fileLocation.split("/").pop();
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    };
    $scope.viewfile = function (fileName) {
      var fileUrl = "uploads/filesmanager/" + fileName;
      // Open the file in a new tab
      window.open(fileUrl, "_blank");
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
