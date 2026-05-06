app.controller(
  "application",
  function ($scope, $state, $filter, $rest, $decrypt, $http, $uibModal, $sce) {
    document.title = "APPLICATION LIST | SCHOLARLYSYNC";
    $scope.now = new Date();
    $scope.items_per_page = 50;
    $scope.current_page = 1;
    $scope.appid = localStorage.getItem("appformid");
    $scope.schid = localStorage.getItem("schformid");
    $scope.studentid = localStorage.getItem("studformid");
    $scope.sch_list = [];
    $scope.msg = "";

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.emailoading = false;

    $scope.filter = {
      date: -1,
      appstatus: -1,
      startDate: "",
      endDate: "",
      schtype: -1,
    };
    $scope.appinfo = {
      aplid: 0,
      schid: 0,
      userid: 0,
      hincome: 0,
      dyhes: 0,
      aplschid: 0,
      whyapply: "",
      msgcnrn: "",
      aplstatus: 0,
      flsubmit: 0,
      major: "",
      gpa: "",
      paia: "",
      ndh: "",
      rfn: "",
      crfa: 0,
      apldate: "",
    };
    $scope.schinfo = {
      id: 0,
      schname: "",
      schtype: 0,
      schdate: "",
      schstatus: "",
      amount: "",
      criteria: "",
      docrequired: "",
      fundsource: "",
    };
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
      photo: "src/assets/images/no-image.png",
    };

    $scope.application_list = function (
      userid,
      schtype,
      status,
      startDate,
      endDate
    ) {
      $rest
        .get(
          `application_list?usertype=${userid.UserTypeRID}&userid=${userid.UserID}&schtype=${schtype}&status=${status}&startDate=${startDate}&endDate=${endDate}`
        )
        .then(
          function success(res) {
            $scope.aplist = res.data;
          },
          function error(err) {
            console.error(err);
          }
        );
    };
    $scope.application_list(
      $scope.userid,
      $scope.filter.schtype,
      $scope.filter.appstatus,
      $scope.filter.startDate,
      $scope.filter.endDate
    );
    $scope.application_edit = function (aplid) {
      if (aplid > 0) {
        $rest.get(`application_edit&id=${aplid}`).then(
          function success(res) {
            $scope.appinfo = {
              aplid: res.data.aplid,
              schid: res.data.schid,
              userid: res.data.userid,
              hincome: res.data.hincome,
              dyhes: res.data.dyhes,
              aplschid: res.data.aplschid,
              whyapply: res.data.whyapply,
              msgcnrn: res.data.msgcnrn,
              aplstatus: res.data.aplstatus,
              flsubmit: res.data.flsubmit,
              major: res.data.major,
              gpa: res.data.gpa,
              paia: res.data.paia,
              ndh: res.data.ndh,
              rfn: res.data.rfn,
              crfa: res.data.crfa,
              apldate: res.data.apldate,
              remarks: res.data.remarks,
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.appinfo = {
          aplid: 0,
          schid: 0,
          userid: 0,
          hincome: 0,
          dyhes: 0,
          aplschid: 0,
          whyapply: "",
          msgcnrn: "",
          aplstatus: 0,
          flsubmit: 0,
          major: "",
          gpa: "",
          paia: "",
          ndh: "",
          rfn: "",
          crfa: 0,
          apldate: "",
        };
      }
    };
    $scope.application_edit($scope.appid);

    $scope.application_update = function (appinfo, appstatus) {
      if (appinfo.gpa != "" || appinfo.major != "") {
        let appobj = {
          schid: $scope.schid,
          userid: $scope.userid.UserID,
          appid: $scope.appid,
          major: appinfo.major,
          gpa: appinfo.gpa,
          paia: appinfo.paia,
          hincome: appinfo.hincome,
          ndh: appinfo.ndh,
          rfn: appinfo.rfn,
          crfa: appinfo.crfa,
          dyhes: appinfo.dyhes,
          aplschid: appinfo.aplschid,
          msgcnrn: appinfo.msgcnrn,
          wayapp: appinfo.whyapply,
          aplstatus: 0,
        };
        $scope.emailoading = true;
        $rest.post("application_update", appobj).then(
          function success(res) {
            let sentemail = [
              $scope.sent_email(appinfo, appstatus),
              $scope.sent_respemail(appstatus),
            ];
            Promise.all(sentemail)
              .then(() => {
                alert("application Form Submission Successful!");
              })
              .catch((err) => console.error("Error updating data:", err));
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
        $scope.msg = "This field is required!";
        $scope.applicationid = 0;
      }
    };
    $scope.application_status = function (appinfo) {
      if (appinfo.aplstatus || appinfo.remarks) {
        let app_obj = {
          aplid: appinfo.aplid,
          schid: appinfo.schid,
          userid: appinfo.userid,
          aplstatus: appinfo.aplstatus,
          remarks: appinfo.remarks,
          reviewer: $scope.userid.UserID,
        };
        $scope.emailoading = true;
        $rest.post("application_status", app_obj).then(
          function success(res) {
            $scope.sent_email(appinfo, appinfo.aplstatus);
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        alert(
          "Incomplete Form Submission\nFields marked with a red asterisk (*) are re quired. Please fill them in to continue."
        );
      }
    };
    $scope.application_delete = function (aplid) {
      let app_obj = {
        id: aplid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("application_delete", app_obj).then(
          function success(res) {
            alert("Delete Application Successful!");
            localStorage.removeItem("appformid");
            $state.go("application.list", {}, { reload: true });
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $state.reload();
      }
    };

    $scope.application_filter = function (filter) {
      if (filter.date > -1 || filter.appstatus > -1 || filter.schtype > -1) {
        let startDate, endDate;
        const today = new Date();

        switch ($scope.filter.date) {
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
        $scope.application_list(
          $scope.studentid,
          filter.schtype,
          filter.appstatus,
          fromDate ? fromDate : "",
          toDate ? toDate : ""
        );
      } else {
        alert("Please select a filter!");
      }
    };

    $scope.application_view = function (app) {
      $scope.show_modal("appstud");
      $scope.get_scholarship(app.schid);
      $scope.get_student(app.userid);
      $scope.application_edit(app.aplid);
    };
    $scope.getReadableFileSize = function (bytes) {
      if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return "-";
      var units = ["bytes", "KB", "MB", "GB", "TB", "PB"];
      var number = Math.floor(Math.log(bytes) / Math.log(1024));
      var value = (bytes / Math.pow(1024, Math.floor(number))).toFixed(2);
      return value + " " + units[number];
    };
    $scope.files_edit = function (aplid) {
      $scope.show_modal("uploadfiles");
    };

    $scope.sent_email = function (appinfo, appstatus) {
      let email_obj = {
        addaddress: $scope.studinfo.email,
        subject:
          appstatus === 0
            ? "Your Scholarship Application Has Been Submitted"
            : "Submission Status Update",
        body: `<div style='color: black; font-family: Arial, sans-serif;'>
          <p>Hello ${$scope.studinfo.lname},</p>

          <p>We hope you're doing well!</p>

          <p>Scholarship Applied ${$scope.schinfo.schname} <p>
          
          ${
            appstatus === 0
              ? "<p>Your application has been successfully <strong>submitted</strong>.</p>"
              : appstatus === 1
              ? "<p>We wanted to let you know that your submission has been successfully received and is now <strong>under review</strong>.</p><p>We appreciate your patience and will notify you as soon as there are further updates.</p>"
              : appstatus === 2
              ? "<p>Your document has been successfully received and is now <strong>reviewing your documents</strong>.</p>"
              : appstatus === 3
              ? "<p>Congratulations! Your application has been <strong>approved</strong>.</p>"
              : appstatus === 4
              ? "<p>We regret to inform you that your application has been <strong>rejected</strong>.</p>"
              : appstatus === 5
              ? "<p>Your application status is currently <strong>on hold</strong>. Please contact us for further details.</p>"
              : appstatus === 6
              ? "<p>The <strong>application period is open</strong>. Feel free to submit your application.</p>"
              : "<p>Your application is currently in an <strong>unknown state</strong>. Please contact us for clarification.</p>"
          }

          ${
            appstatus !== 0 && appinfo?.remarks
              ? `<p><strong>Remarks:</strong> ${
                  appinfo.remarks || "No Remarks"
                }</p>`
              : ""
          }

          <p>If you have any questions, feel free to reach out to us at <strong>+399-3286</strong>.</p>

          <p>Best regards,<br>
          <strong>Colegio De Sta Ana De Victorias</strong><br>
          +399-3286</p>
      </div>`,
        pdfPath: null,
      };
      $rest.post("sent_email", email_obj).then(
        function success(res) {
          $scope.emailnotif(email_obj);
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.sent_respemail = function (appstatus) {
      if (appstatus == 0) {
        if (!$scope.schinfo.respemail) {
        } else {
          let email_obj = {
            addaddress: $scope.schinfo.respemail,
            subject: "New Scholarship Application Submitted",
            body: `<div style='color: black;'>
        <p>Dear ${$scope.schinfo.resplname},</p>

        <p>A new application has been submitted by ${$scope.studinfo.fname} ${$scope.studinfo.lname} for the ${$scope.schinfo.schname}.</p>

        <p>Best regards,<br>
        ScholarlySync</p>
        </div>`,
            pdfPath: null,
          };
          $rest.post("sent_email", email_obj).then(
            function success(res) {
              $scope.emailnotif(email_obj);
            },
            function error(err) {
              console.error(err);
            }
          );
        }
      }
    };
    $scope.emailnotif = function (obj) {
      let enobj = {
        userid: $scope.studinfo.id,
        email: $scope.studinfo.email,
        subject: obj.subject,
        body: obj.body,
        attachment: obj.pdfPath == null ? "None" : obj.pdfPath,
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
    $scope.get_schtype = function () {
      $rest.get("sch_list").then(
        function success(res) {
          $scope.schlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_schtype();
    $scope.get_scholarship = function (schid) {
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
              schtypename: res.data.schtypename,
              respsch: res.data.respsch,
              respemail: res.data.email,
              resplname: res.data.lname,
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
          amount: "",
          criteria: "",
          docrequired: "",
          fundsource: "",
        };
      }
    };
    $scope.get_scholarship($scope.schid);
    $scope.get_student = function (studid) {
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
              course: res.data.csname,
              yas: res.data.ysname,
              department: res.data.dpname,
              studstatus: res.data.studstatus,
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
          photo: "src/assets/images/no-image.png",
        };
      }
    };
    $scope.get_student($scope.studentid);

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

    $scope.state_go = function (url, app) {
      if (app) {
        localStorage.setItem("appformid", app.aplid);
        localStorage.setItem("schformid", app.schid);
        localStorage.setItem("studformid", app.userid);
      }
      $state.go(url, {}, { reload: true });
    };

    $scope.show_modal = function (val) {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/${val}-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: val == "appfiles" ? "md" : "md",
        // windowClass: "cls",
      });
      $scope.closeModal = function () {
        $uibModalInstance.close();
      };
    };

    //upload files
    $scope.file_list = function (appid, userid) {
      $rest.get(`file_list?appid=${appid}&userid=${userid}&enid=${0}`).then(
        function success(res) {
          $scope.filelist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.file_list($scope.appid, $scope.studentid);
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
    $scope.file_upload = function (file, userid, schid, aplid) {
      var formData = new FormData();

      formData.append("imageFile", file);
      formData.append("user_id", userid);
      formData.append("sch_id", schid);
      formData.append("apl_id", aplid);
      formData.append("en_id", 0);

      if (file) {
        $http
          .post("api/file_upload", formData, {
            transformRequest: angular.identity,
            headers: { "Content-Type": undefined },
          })
          .then(
            function (res) {
              $scope.file_list($scope.appid, $scope.studentid);
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
          $scope.file_upload(
            file,
            $scope.studentid,
            $scope.schid,
            $scope.appid
          );
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
