app.controller(
  "information",
  function ($scope, $state, $filter, $rest, $decrypt, $uibModal) {
    document.title = "INFORMATION | SCHOLARLYSYNC";

    $scope.items_per_page = 50;
    $scope.current_page = 1;

    //course
    $scope.course_list = function () {
      $rest.get("course_list").then(
        function success(res) {
          $scope.courselist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.course_list();
    $scope.course_edit = function (courseid) {
      if (courseid > 0) {
        $rest.get(`course_edit&id=${courseid}`).then(
          function success(res) {
            $scope.course = {
              courseid: res.data.courseid,
              name: res.data.coursename,
              code: res.data.coursecode,
              description: res.data.desp,
              major: res.data.major,
              credit: res.data.credit,
            };
            $scope.show_modal("course");
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.course = {
          courseid: 0,
          name: "",
          code: "",
          description: "",
          major: "",
          credit: "",
        };
      }
    };
    $scope.course_add = function (course) {
      let course_obj = {
        name: course.name,
        code: course.code,
        description: course.description,
        major: course.major,
        credits: course.credit,
      };
      $rest.post("course_add", course_obj).then(
        function success(res) {
          $scope.course_list();
          $scope.closeModal();
          alert("Course has been successfully added!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.course_update = function (course) {
      let course_obj = {
        courseid: course.courseid,
        name: course.name,
        code: course.code,
        description: course.description,
        major: course.major,
        credits: course.credit,
      };
      $rest.post("course_update", course_obj).then(
        function success(res) {
          $scope.course_list();
          $scope.closeModal();
          alert("Course has been successfully update!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.course_delete = function (courseid) {
      let course_obj = {
        id: courseid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("course_delete", course_obj).then(
          function success(res) {
            alert("Delete Course Successful!");
            $scope.course_list();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.course_list();
      }
    };
    $scope.course_new = function (tpl) {
      $scope.show_modal(tpl);
      $scope.course = {
        courseid: 0,
        name: "",
        code: "",
        description: "",
        major: "",
        credit: "",
      };
    };
    $scope.save_course = function (course) {
      if (course.courseid == 0) {
        $scope.course_add(course);
      } else if (course.courseid > 0) {
        $scope.course_update(course);
      }
    };

    //Year Section
    $scope.yearsec_list = function () {
      $rest.get("yearsec_list").then(
        function success(res) {
          $scope.yslist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.yearsec_list();
    $scope.yearsec_edit = function (ysid) {
      if (ysid > 0) {
        $rest.get(`yearsec_edit&id=${ysid}`).then(
          function success(res) {
            $scope.ys = {
              ysid: res.data.ysid,
              year: res.data.years,
              sec: res.data.section,
              name: res.data.sname,
              code: res.data.scode,
            };
            $scope.show_modal("ys");
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.ys = {
          ysid: 0,
          year: "",
          sec: "",
          name: "",
          code: "",
        };
      }
    };
    $scope.yearsec_add = function (ys) {
      let ys_obj = {
        year: ys.year,
        sec: ys.sec,
        name: ys.name,
        code: ys.code,
      };
      $rest.post("yearsec_add", ys_obj).then(
        function success(res) {
          $scope.yearsec_list();
          $scope.closeModal();
          alert("Year & Section has been successfully added!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.yearsec_update = function (ys) {
      let ys_obj = {
        ysid: ys.ysid,
        years: ys.year,
        sec: ys.sec,
        name: ys.name,
        code: ys.code,
      };
      $rest.post("yearsec_update", ys_obj).then(
        function success(res) {
          $scope.yearsec_list();
          $scope.closeModal();
          alert("Year & Section has been successfully update!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.yearsec_delete = function (ysid) {
      let ys_obj = {
        id: ysid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("yearsec_delete", ys_obj).then(
          function success(res) {
            alert("Delete Year & Section Successful!");
            $scope.yearsec_list();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.yearsec_list();
      }
    };
    $scope.yearsec_new = function (tpl) {
      $scope.show_modal(tpl);
      $scope.ys = {
        ysid: 0,
        year: "",
        sec: "",
        name: "",
        code: "",
      };
    };
    $scope.save_yearsec = function (ys) {
      if (ys.ysid == 0) {
        $scope.yearsec_add(ys);
      } else if (ys.ysid > 0) {
        $scope.yearsec_update(ys);
      }
    };

    //department
    $scope.dept_list = function () {
      $rest.get("dept_list").then(
        function success(res) {
          $scope.deptlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.dept_list();
    $scope.dept_edit = function (ysid) {
      if (ysid > 0) {
        $rest.get(`dept_edit&id=${ysid}`).then(
          function success(res) {
            $scope.dept = {
              deptid: res.data.deptid,
              name: res.data.deptname,
              code: res.data.deptcode,
              desciptions: res.data.descriptions,
              depthead: res.data.depthead,
            };
            $scope.show_modal("dept");
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.dept = {
          deptid: 0,
          deptcode: "",
          meaning: "",
        };
      }
    };
    $scope.dept_add = function (dept) {
      let dept_obj = {
        name: dept.name,
        code: dept.code,
        desciptions: dept.desciptions,
        depthead: dept.depthead,
      };
      $rest.post("dept_add", dept_obj).then(
        function success(res) {
          $scope.dept_list();
          $scope.closeModal();
          alert("Department has been successfully added!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.dept_update = function (dept) {
      let dept_obj = {
        deptid: dept.deptid,
        name: dept.name,
        code: dept.code,
        desciptions: dept.desciptions,
        depthead: dept.depthead,
      };
      console.log(dept_obj);
      $rest.post("dept_update", dept_obj).then(
        function success(res) {
          $scope.dept_list();
          $scope.closeModal();
          alert("Department has been successfully update!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.dept_delete = function (ysid) {
      let dept_obj = {
        id: ysid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("dept_delete", dept_obj).then(
          function success(res) {
            alert("Delete Department Successful!");
            $scope.dept_list();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.yearsec_list();
      }
    };
    $scope.dept_new = function (tpl) {
      $scope.show_modal(tpl);
      $scope.dept = {
        deptid: 0,
        name: "",
        code: "",
        desciptions: "",
        depthead: 0,
      };
    };
    $scope.save_dept = function (dept) {
      if (dept.deptid == 0) {
        $scope.dept_add(dept);
      } else if (dept.deptid > 0) {
        $scope.dept_update(dept);
      }
    };

    //scholarship
    $scope.sch_list = function () {
      $rest.get("sch_list").then(
        function success(res) {
          $scope.schlist = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.sch_list();
    $scope.sch_edit = function (typeid) {
      if (typeid > 0) {
        $rest.get(`sch_edit&id=${typeid}`).then(
          function success(res) {
            $scope.sch = {
              typeid: res.data.typeid,
              typename: res.data.typename,
              description: res.data.descp,
            };
            $scope.show_modal("sch");
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.sch = {
          deptid: 0,
          deptcode: "",
          meaning: "",
        };
      }
    };
    $scope.sch_add = function (sch) {
      let sch_obj = {
        typename: sch.typename,
        description: sch.description,
      };
      $rest.post("sch_add", sch_obj).then(
        function success(res) {
          $scope.sch_list();
          $scope.closeModal();
          alert("Scholarship type has been successfully added!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.sch_update = function (sch) {
      let sch_obj = {
        typeid: sch.typeid,
        typename: sch.typename,
        description: sch.description,
      };
      console.log(sch_obj);
      $rest.post("sch_update", sch_obj).then(
        function success(res) {
          $scope.sch_list();
          $scope.closeModal();
          alert("Scholarship Type has been successfully update!");
        },
        function error(err) {
          console.log(err);
        }
      );
    };
    $scope.sch_delete = function (typeid) {
      let sch_obj = {
        id: typeid,
      };
      if (confirm("Are you sure you want to delete?")) {
        $rest.post("sch_delete", sch_obj).then(
          function success(res) {
            alert("Delete scholarship type successful!");
            $scope.sch_list();
          },
          function error(err) {
            console.error(err);
          }
        );
      } else {
        $scope.sch_list();
      }
    };
    $scope.sch_new = function (tpl) {
      $scope.show_modal(tpl);
      $scope.sch = {
        typeid: 0,
        typename: "",
        description: "",
      };
    };
    $scope.save_schtype = function (sch) {
      if (sch.typeid == 0) {
        $scope.sch_add(sch);
      } else if (sch.typeid > 0) {
        $scope.sch_update(sch);
      }
    };

    //head department
    $scope.get_employee = function () {
      $rest.get(`employee_list?department=${-1}&usertype=${3}`).then(
        function success(res) {
          $scope.emp_list = res.data;
        },
        function error(err) {
          console.error(err);
        }
      );
    };
    $scope.get_employee();

    $scope.state_go = function (url, studid) {
      if (studid > 0) {
        localStorage.setItem("studformid", studid);
      } else {
        localStorage.setItem("studformid", 0);
      }
      $state.go(url, {}, { reload: true });
    };
    // modal only
    $scope.show_modal = function (tpl) {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        templateUrl: `src/view/modal/${tpl}-modal.php`,
        backdrop: "static",
        scope: $scope,
        // windowClass: "cls",
      });
      $scope.closeModal = function () {
        $uibModalInstance.close();
      };
    };
  }
);
