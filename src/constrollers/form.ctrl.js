app.controller(
  "form",
  function ($scope, $state, $rest, $decrypt, $http, $uibModal, $location) {
    document.title = "SCHOLARSHIP FORMS | SCHOLARLYSYNC";
    $scope.currentPath = $location.path();
    $scope.items_per_page = 50;
    $scope.current_page = 1;
    $scope.sfid = localStorage.getItem("contractform");
    $scope.formdropdown = parseInt(localStorage.getItem("formselect"))
      ? parseInt(localStorage.getItem("formselect"))
      : -1;

    $scope.formid = localStorage.getItem("formid");
    $scope.departments = 0;
    $scope.course = 0;
    $scope.year = 0;
    $scope.emailoading = false;

    let sms_user = localStorage.getItem("sms_user");
    let descrypted_o = sms_user ? $decrypt.decrypted(sms_user) : "";
    $scope.userid = sms_user ? JSON.parse(descrypted_o) : "";

    $scope.filter = {
      date: -1,
      appstatus: -1,
      startDate: "",
      endDate: "",
    };
    $scope.contract = {
      userid: 0,
      fname: "",
      mname: "",
      lname: "",
      usernum: "",
      formtype: 0,
      sfstatus: 0,
      sfdate: "",
      dob: "",
      email: "",
    };
    $scope.form = {
      id: 0,
      schid: 0,
      title: "",
      body: "",
    };
    //forms
    $scope.form_list = function () {
      $rest.get(`form_list`).then(
        function success(res) {
          $scope.formlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.form_list();
    $scope.form_add = function (form) {
      if (
        form.schid?.trim() !== 0 &&
        form.title?.trim() !== "" &&
        form.body?.trim() !== ""
      ) {
        let schids = form.schid.replace(/\D+/g, "");
        let form_obj = {
          schid: schids,
          title: form.title,
          body: form.body,
        };
        $rest.post("form_add", form_obj).then(
          function success(res) {
            alert("Form Add Successfully!");
            localStorage.setItem("formid", res.data.form_id);
            $state.reload();
            $scope.generatePDF(res.data.form_id);
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
    $scope.form_edit = function (formid) {
      if (formid > 0) {
        $rest.get(`form_edit?formid=${formid}`).then(
          function success(res) {
            $scope.form = {
              id: res.data.FormID,
              schid: res.data.ScholarshipID,
              title: res.data.Title,
              body: res.data.Body,
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.form = {
          id: 0,
          schid: 0,
          title: "",
          body: "",
        };
      }
    };
    $scope.form_edit($scope.formid);
    $scope.form_update = function (form) {
      let schids = 0;
      if (typeof form.schid === "string") {
        schids = form.schid.replace(/\D+/g, "");
      } else {
        schids = form.schid;
      }
      if (schids > 0 && form.title?.trim() !== "" && form.body?.trim() !== "") {
        let form_obj = {
          id: form.id,
          schid: schids,
          title: form.title,
          body: form.body,
        };
        $rest.post("form_update", form_obj).then(
          function success(res) {
            alert("Form Update Successfully!");
            $scope.form_edit(form.id);
            $scope.generatePDF(form.id);
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
    $scope.form_delete = function (formid) {
      let form_obj = {
        id: formid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("form_delete", form_obj).then(
          function success(res) {
            alert("Delete Form Successful!");
            localStorage.removeItem("formid");
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
    $scope.save_form = function (form) {
      if (form.id > 0) {
        $scope.form_update(form);
      } else {
        $scope.form_add(form);
      }
    };
    $scope.edit_form = function (formid) {
      localStorage.setItem("formid", formid);
      $state.reload();
    };
    $scope.new_form = function () {
      localStorage.removeItem("formid");
      $state.reload();
    };
    $scope.select_form = function (formdropdown) {
      localStorage.setItem("formselect", formdropdown);
      $state.reload();
    };
    $scope.generatePDF = function (formid) {
      let element = document.getElementById("form_print");
      let customStyles = `
        .underlined-forms {
            display: flex;
            width: 90%;
            border-bottom: 1px solid #000;
            height: 1.2em;
            line-height: 1.2em;
            margin-bottom: 10px;
        }
                .my-3 {
            display: flex;
            width: 85%;
            border-bottom: 1px solid #000;
            height: 1.2em;
            line-height: 1.2em;
            margin-bottom: 10px;
        }
        .headerprint {
            display: block !important;
            text-align: center !important;
        }

        .headerprint .headertext {
            padding-top: 20px;
        }

        .sec1 {
            text-align: center;
            padding-top: 30px;
        }

        .bodytext {
            padding-top: 20px;
            width: 100%;
        }

          /* Section Margins */
        .col-lg-12 {
            margin-bottom: 20px;
            padding-left: 20px;
        }

        /* Row Styles */
        .row {
            margin-bottom: 20px;
        }

        /* List Styles */
        ul,
        ol {
            margin: 10px 0;
            padding-left: 20px;
        }

        ul li,
        ol li {
            margin-bottom: 8px;
        }

        /* Signature and Date Fields */
        .col-lg-12.d-flex {
            justify-content: space-between;
            margin-top: 20px;
        }

        .col-lg-12.d-flex .my-2 {
            width: 50%;
        }
        `;

      let cpath =
        $scope.currentPath == "/forms/contract"
          ? "scholarlysyncContract.pdf"
          : "scholarlysyncRAR.pdf";

      let styleElement = document.createElement("style");
      styleElement.innerHTML = customStyles;
      element.appendChild(styleElement);

      let options = {
        margin: 1,
        filename: `FORM${formid}.pdf`,
        html2canvas: { scale: 2 },
        jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
      };

      html2pdf()
        .set(options)
        .from(element)
        .toPdf()
        .get("pdf")
        .then(function (pdf) {
          const pdfBlob = pdf.output("blob");
          const file = new File([pdfBlob], options.filename, {
            type: "application/pdf",
          });

          var formData = new FormData();
          formData.append("file", file);

          $http
            .post("api/upload_pdf", formData, {
              transformRequest: angular.identity,
              headers: { "Content-Type": undefined },
            })
            .then(
              function (response) {
                console.log("Upload response:", response.data);
                $state.reload();
              },
              function (error) {
                console.error("Upload failed:", error);
              }
            );
        });
    };
    //forms end
    $scope.studform_list = function () {
      $rest.get(`studform_list`).then(
        function success(res) {
          $scope.sflist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.studform_list();
    $scope.studform_sent = function () {
      $rest.get(`studform_sent`).then(
        function success(res) {
          $scope.sfsent = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.studform_sent();
    $scope.studform_edit = function (sfid) {
      if (sfid > 0) {
        $rest.get(`studform_edit&id=${sfid}`).then(
          function success(res) {
            $scope.contract = {
              userid: res.data.userid,
              fname: res.data.fname,
              mname: res.data.mname,
              lname: res.data.lname,
              usernum: res.data.usernum,
              formtype: res.data.formtype,
              sfstatus: res.data.sfstatus,
              sfid: res.data.sfid,
              sfdate: res.data.sfdate,
              dob: res.data.dob,
              email: res.data.email,
            };
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.contract = {
          userid: 0,
          fname: "",
          mname: "",
          lname: "",
          usernum: "",
          formtype: 0,
          sfstatus: 0,
          sfid: 0,
          sfdate: "",
          dob: "",
          email: "",
        };
      }
    };
    $scope.studform_edit($scope.sfid);
    $scope.studform_add = function (sfinfo, formid) {
      let exists = false;
      let exists1 = false;
      if (Array.isArray($scope.sflist) && $scope.sflist.length > 0) {
        exists = $scope.sflist.some(
          (item) => item.formid === formid && item.userid === sfinfo.userid
        );
      }

      if (Array.isArray($scope.sfsent) && $scope.sfsent.length > 0) {
        exists1 = $scope.sfsent.some(
          (item) => item.formid === formid && item.userid === sfinfo.userid
        );
      }
      if (exists) {
        alert("Student Already Selected!");
      } else if (exists1) {
        alert("Student Already Sent this form!");
      } else {
        let sf_obj = {
          formid: formid,
          usernum: sfinfo.usernum,
          userid: sfinfo.userid,
          sfstatus: 0,
        };
        $rest.post("studform_add", sf_obj).then(
          function success(res) {
            alert("Adding Student to sent a form successful!");
            $scope.studform_list();
          },
          function error(err) {
            console.error(err);
          }
        );
      }
    };
    $scope.studform_delete = function (sfid) {
      let sf_obj = {
        id: sfid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("studform_delete", sf_obj).then(
          function success(res) {
            alert("Delete Student Successful!");
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

    $scope.send_forms = function () {
      if ($scope.sflist.length > 0) {
        if (confirm("Are you sure you want to send the email?")) {
          $scope.emailoading = true;
          $scope.sflist.forEach((sfinfo) => {
            $scope.sent_email(sfinfo);
          });
        } else {
          $state.reload();
        }
      } else {
        alert("Please Select Student Before Sending a Form");
      }
    };

    $scope.save_sf = function (sfinfo, formid) {
      console.log(sfinfo, formid);
      if (formid > 0) {
        $scope.studform_update(sfinfo, formid);
      } else {
        $scope.studform_add(sfinfo);
      }
    };
    $scope.sent_email = function (sfinfo) {
      console.log(sfinfo);
      let email_obj = {
        addaddress: sfinfo.email,
        subject: sfinfo.subjects,
        body: `<div style='color: black;'>
        <p>Dear ${sfinfo.lname},</p>

        <p>I hope this message finds you well.</p>

        <p><strong>Submitted Information:</strong></p>
        
        <p>I am pleased to inform you that we have finalized the details of your sports scholarship. 
        Attached to this email, you will find the scholarship form. Please take the time to read through the document carefully.</p>
        
        <p>If you have any questions or require further clarification regarding any terms, feel free to reach out to me. 
        Once you have reviewed the contract, kindly sign and return it at your earliest convenience to secure your scholarship.</p>
      
        <p>Thank you, and congratulations once again on your achievement!</p>

        <p>Best regards,</p>
        Colegio De Sta Ana De Victorias<br>
        399-3286</p>
    </div>`,
        pdfPath: `../uploads/formpdf/FORM${sfinfo.formid}.pdf`,
      };
      $rest.post("sent_email", email_obj).then(
        function success(res) {
          let sf_obj = {
            id: sfinfo.sfid,
            sfstatus: 1,
          };
          $rest.post("studform_update", sf_obj).then(
            function success(res) {
              $scope.emailnotif(email_obj, sfinfo);
            },
            function error(err) {
              console.error(err);
            }
          );
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.sent_rar = function (sfinfo) {
      let email_obj = {
        addaddress: sfinfo.email,
        subject: "Sports Scholarship Rules and Regulations Agreement Attached",
        body: `<div style='color: black;'>
        <p>Dear ${sfinfo.fname + " " + sfinfo.mname + ". " + sfinfo.lname},</p>

        <p>I hope this message finds you well.</p>

        <p><strong>Submitted Information:</strong></p>
        
        <p>I am pleased to inform you that we have finalized the details of your sports scholarship. 
        Attached to this email, you will find the scholarship rules and regulations agreement for your review. Please take the time to read through the document carefully.</p>
        
        <p>If you have any questions or require further clarification regarding any terms, feel free to reach out to me. 
        Once you have reviewed the rules and regulations agreement, kindly sign and return it at your earliest convenience to secure your scholarship.</p>
      
        <p>Thank you, and congratulations once again on your achievement!</p>

        <p>Best regards,</p>
        Colegio De Sta Ana De Victorias<br>
        399-3286</p>
    </div>`,
        pdfPath: "../uploads/formpdf/scholarlysyncRAR.pdf",
      };
      $rest.post("sent_email", email_obj).then(
        function success(res) {
          let sf_obj = {
            id: $scope.sfid,
            usernum: sfinfo.usernum,
            userid: sfinfo.userid,
            sfstatus: 1,
          };
          $rest.post("studform_update", sf_obj).then(
            function success(res) {
              $scope.emailnotif(email_obj);
              localStorage.removeItem("studformid");
            },
            function error(err) {
              console.error(err);
            }
          );
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.emailnotif = function (obj, sfinfo) {
      let enobj = {
        userid: sfinfo.userid,
        email: sfinfo.email,
        subject: sfinfo.subjects,
        body: obj.body,
        attachment: obj.pdfPath == null ? "None" : obj.pdfPath.split("/").pop(),
        fromuserid: 0,
        fromemail: "scholarlysync320@gmail.com",
        emailstatus: 2,
        emailtype: 1,
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

    $scope.get_student = function (dept, course, year) {
      $rest.get(`student_list?dept=${dept}&course=${course}&year=${year}`).then(
        function success(res) {
          $scope.studlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_student($scope.departments, $scope.course, $scope.year);
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
    $scope.get_sch = function () {
      $rest.get(`scheme_list?schtype=${0}&schstatus=${0}`).then(
        function success(res) {
          $scope.schlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_sch();

    $scope.sfstatus = function (sfsid) {
      var status = "";
      if (sfsid == 0) {
        status = "Pending";
      } else if (sfsid == 1) {
        status = "Sent";
      }
      return status;
    };

    $scope.state_go = function (url, studid) {
      if (studid > 0) {
        localStorage.setItem("studformid", studid);
      } else {
        localStorage.setItem("studformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };

    $scope.show_modal = function (val, formid) {
      $scope.formid = formid;

      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/${val}-modal.php`,
        backdrop: "static",
        scope: $scope,
        size: "lg",
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
