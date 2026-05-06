app.directive("fileChange", function () {
  return {
    restrict: "A",
    link: function (scope, element, attrs) {
      element.bind("change", function () {
        scope.$apply(function () {
          scope.$eval(attrs.fileChange);
        });
      });
    },
  };
});