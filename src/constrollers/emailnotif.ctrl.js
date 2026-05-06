app.controller(
  "emailnotif",
  function (
    $scope,
    $state,
    $filter,
    $rest,
    $decrypt,
    $http,
    $uibModal,
    $timeout
  ) {
    document.title = "EMAIL NOTIFICATION | SCHOLARLYSYNC";
    $scope.now = new Date();
    $scope.items_per_page = 15;
    $scope.current_page = 1;
    $scope.date = -1;
    $scope.fromDate = "";
    $scope.toDate = "";

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.emailinfo = {
      id: 0,
      email: "",
      body: "",
      attchment: "",
      names: "",
      method: "",
      notifstatus: 0,
      sentdate: "",
      subjects: "",
    };

    $scope.email_list = function (user, fromDate, toDate) {
      $rest
        .get(
          `email_list?userid=${user.UserID}&usertype=${user.UserTypeRID}&fromDate=${fromDate}&toDate=${toDate}`
        )
        .then(
          function success(res) {
            $scope.emaillist = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.email_list($scope.userid, $scope.fromDate, $scope.toDate);

    $scope.email_edit = function (enid, val) {
      if (enid > 0) {
        $rest.get(`email_edit&id=${enid}`).then(
          function success(res) {
            $scope.emailinfo = {
              id: res.data.enid,
              email: res.data.email,
              fromemail: res.data.fromemail,
              fromuserid: res.data.fromuserid,
              userid: res.data.userid,
              body: res.data.body,
              attchment: res.data.attchment,
              names: res.data.fname + " " + res.data.lname,
              method: res.data.method,
              notifstatus: res.data.notifstatus,
              sentdate: res.data.sentdate,
              subjects: res.data.subjects,
            };
            if (val == 0) {
              $scope.concern_modal();
            } else {
              $scope.email_modal();
            }
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.emailinfo = {
          id: 0,
          email: "",
          body: "",
          attchment: "",
          names: "",
          method: "",
          notifstatus: 0,
          sentdate: "",
          subjects: "",
        };
      }
    };
    $scope.email_add = function () {
      let concernobj = {
        fromuserid: $scope.userid.UserID,
        fromemail: $scope.userid.EmailAddress,
      };
      $rest.post("email_add", concernobj).then(
        function success(res) {
          $scope.email_edit(res.data.en_id, 0);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.email_update = function (emailinfo) {
      let extractedNumber = emailinfo.userid.replace(/\D+/g, "");
      let user = $scope.userslist.find(
        (student) => student.userid === Number(extractedNumber)
      );
      let en_obj = {
        enid: emailinfo.id,
        toemail: user.email,
        touserid: user.userid,
        subjects: emailinfo.subjects,
        body: emailinfo.body,
        emailstatus: 1,
      };
      $scope.emailoading = true;
      $rest.post("email_update", en_obj).then(
        function success(res) {
          $scope.sent_email(en_obj);
        },
        function error(err) {
          console.error(err);
        }
      );
    };

    $scope.email_delete = function (enid) {
      let en_obj = {
        id: enid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("email_delete", en_obj).then(
          function success(res) {
            alert("Delete Email Successful!");
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
    $scope.email_filter = function (date) {
      if (date > -1) {
        let startDate, endDate;
        const today = new Date();

        switch (date) {
          case 0: // Today
            startDate = new Date(
              today.getFullYear(),
              today.getMonth(),
              today.getDate()
            ); // Start of today
            endDate = new Date(
              today.getFullYear(),
              today.getMonth(),
              today.getDate() + 1
            ); // Start of tomorrow (exclusive)
            break;
          case 1: // This Week (ISO week starts on Monday)
            const firstDayOfWeek = new Date(today);
            firstDayOfWeek.setDate(today.getDate() - today.getDay() + 1); // Monday
            startDate = new Date(
              firstDayOfWeek.getFullYear(),
              firstDayOfWeek.getMonth(),
              firstDayOfWeek.getDate()
            ); // Start of the week
            endDate = new Date(
              firstDayOfWeek.getFullYear(),
              firstDayOfWeek.getMonth(),
              firstDayOfWeek.getDate() + 7
            ); // Start of next week (exclusive)
            break;
          case 2: // This Month
            startDate = new Date(today.getFullYear(), today.getMonth(), 1); // Start of the month
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 1); // Start of next month (exclusive)
            break;
          case 3: // This Year
            startDate = new Date(today.getFullYear(), 0, 1); // Start of the year
            endDate = new Date(today.getFullYear() + 1, 0, 1); // Start of next year (exclusive)
            break;
          default:
            startDate = null;
            endDate = null;
            break;
        }
        let fromDate = $filter("date")(startDate, "yyyy-MM-dd");
        let toDate = $filter("date")(endDate, "yyyy-MM-dd");
        $scope.email_list(fromDate, toDate);
      } else {
        alert("Please select a filter!");
      }
    };

    $scope.sent_email = function (emailinfo) {
      if (confirm("Are you sure you want to send concern?")) {
        let today = new Date();
        let todayStart = new Date(today.setHours(0, 0, 0, 0));
        let todayEnd = new Date(today.setHours(23, 59, 59, 999));
        let sentTodayEmails = 0;

        if ($scope.userid.UserTypeRID == 1) {
          if ($scope.emaillist) {
            sentTodayEmails = $scope.emaillist.filter((email) => {
              const sentDate = new Date(email.sentdate);
              return (
                sentDate >= todayStart &&
                sentDate <= todayEnd &&
                email.notifstatus == 2
              );
            });
          }
        } else {
          sentTodayEmails = [];
        }
        if (sentTodayEmails.length >= 2) {
          alert("You can only send two emails per day.");
          return;
        } else {
          let email_obj;
          if ($scope.userid.UserTypeRID == 1) {
            email_obj = {
              addaddress: emailinfo.toemail,
              subject: emailinfo.subjects,
              body: `<div style='color: black;'>
              <p><strong>Student: </strong>${$scope.userid.userName}</p>
              <p><strong>Email: </strong>${$scope.userid.EmailAddress}</p>
              <p><strong>Student Number: </strong>${$scope.userid.UserNumber}</p>
              ${emailinfo.body}
              </div>`,
              pdfPath: null,
            };
          } else {
            email_obj = {
              addaddress: emailinfo.toemail,
              subject: emailinfo.subjects,
              body: `<div style='color: black;'>
              <p><strong>Employee: </strong>${$scope.userid.userName}</p>
              <p><strong>Email: </strong>${$scope.userid.EmailAddress}</p>
              <p><strong>${$scope.userid.UserTypeText}</strong></p>
              ${emailinfo.body}
              </div>`,
              pdfPath: null,
            };
          }

          $rest.post("sent_email", email_obj).then(
            function success(res) {
              $scope.emailoading = false;
              $state.reload();
            },
            function error(err) {
              console.error(err);
            }
          );
        }
      } else {
        $state.reload();
      }
    };

    $scope.get_users = function (user) {
      $rest
        .get(`get_users?userid=${user.UserID}&usertype=${user.UserTypeRID}`)
        .then(
          function success(res) {
            $scope.userslist = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.get_users($scope.userid);

    $scope.state_go = function (enid, val) {
      $scope.email_edit(enid, val);
      $scope.file_list(enid, $scope.userid.UserID);
    };
    $scope.email_modal = function (enid) {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/email-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: "md",
      });
      $scope.closeModal = function () {
        $uibModalInstance.close();
      };
    };
    $scope.concern_modal = function () {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/concern-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: "md",
        // windowClass: "cls",
      });
      $scope.closeModal = function () {
        $uibModalInstance.close();
      };
    };

    //upload files  
    $scope.file_list = function (enid, userid) {
      $rest.get(`file_list?appid=${0}&userid=${userid}&enid=${enid}`).then(
        function success(res) {
          $scope.filelist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.file_edit = function (fileid) {
      $rest.get(`file_edit?id=${fileid}`).then(
        function success(res) {
          $scope.file = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.file_upload = function (file, userid, enid) {
      var formData = new FormData();

      formData.append("imageFile", file);
      formData.append("user_id", userid);
      formData.append("sch_id", 0);
      formData.append("apl_id", 0);
      formData.append("en_id", enid);

      if (file) {
        $http
          .post("api/file_upload", formData, {
            transformRequest: angular.identity,
            headers: { "Content-Type": undefined },
          })
          .then(
            function (res) {
              $scope.file_list($scope.enid, $scope.userid.UserID);
            },
            function (error) {
              console.error(error);
            }
          );
      } else {
        console.log("Insert File to Upload");
      }
    };
    $scope.file_change = function (emailinfo) {
      var fileInput = document.getElementById("pimg");
      var files = fileInput.files;

      var file = files[0];
      if (file) {
        let allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png)$/i;
        let maxSizeInBytes = 25 * 1024 * 1024;
        if (!allowedExtensions.exec(file.name)) {
          alert(
            "Invalid file type. Please upload PDF, JPG, JPEG, or PNG files."
          );
          fileInput.value = ""; // Reset the input field
        } else if (file.size > maxSizeInBytes) {
          alert(
            "File size exceeds the 25MB limit. Please upload a smaller file."
          );
          fileInput.value = ""; // Reset the input field
        } else {
          $scope.fileName = file.name;
          if ($scope.fileName.length >= 12) {
            var splitName = $scope.fileName.split(".");
            $scope.fileName =
              splitName[0].substring(0, 13) + "... ." + splitName[1];
          }
          $scope.file_upload(file, $scope.userid.UserID, emailinfo.id);
        }
      }
    };
    $scope.file_delete = function (file) {
      let file_obj = {
        fileid: file.FileID,
        flocation: file.FileLocation,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("file_delete", file_obj).then(
          function success(res) {
            $scope.file_list($scope.schid, $scope.studentid, $scope.dnow);
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };
    $scope.file_submit = function (schid, userid) {
      let file_obj = {
        schid: schid,
        userid: userid,
      };
      $rest.post("file_submit", file_obj).then(
        function success(res) {
          alert("File submitted successfully!");
          let email_obj = {
            addaddress: $scope.studinfo.email,
            subject: "Scholarship Requirements Submitted Successfully",
            body: `  <div style='color: black;'>
                        <p>Dear ${$scope.studinfo.lname},</p>
        
                        <p>We are pleased to inform you that your submission for the ${$scope.schinfo.schname} has been received. Your application will now move forward for review by the selection committee.</p>

                        <p>We will notify all applicants of the results by email. Should you need to update any part of your application or have any questions, please don't hesitate to contact us at +00-000-000-0000.</p>

                        <p>Thank you for your submission!</p>
        
                        <p>Best regards,<br>
                        Colegio De Sta Ana De Victorias<br>
                        399-3286</p>
                    </div>`,
          };
          $scope.sent_email(email_obj);
          $state.realod();
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.downloadFile = function (fileLocation) {
      const link = document.createElement("a");
      link.href = "uploads/formpdf/" + fileLocation;
      link.download = fileLocation.split("/").pop();
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    };
    $scope.viewfile = function (fileName) {
      var fileUrl = fileName;
      window.open(fileUrl, "_blank");
    };
    $scope.getReadableFileSize = function (bytes) {
      if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return "-";
      var units = ["bytes", "KB", "MB", "GB", "TB", "PB"];
      var number = Math.floor(Math.log(bytes) / Math.log(1024));
      var value = (bytes / Math.pow(1024, Math.floor(number))).toFixed(2);
      return value + " " + units[number];
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
