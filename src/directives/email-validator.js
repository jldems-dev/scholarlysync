app.directive("emailValidator", function () {
  // More robust email validation regex
  var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

  return {
    require: "ngModel",
    link: function (scope, element, attrs, ngModel) {
      scope.$watch(
        () => ngModel.$modelValue,
        function (value) {
          if (value) {
            var isValid = emailRegex.test(value);
            scope.isInvalidEmail = !isValid;
            ngModel.$setValidity("email", isValid); // Update validity state
          } else {
            scope.isInvalidEmail = false; // No error for empty input
            ngModel.$setValidity("email", true); // Treat empty as valid (if required)
          }
        }
      );
    },
  };
});
