app.directive("myTinymce", function () {
  return {
    restrict: "A",
    require: "ngModel",
    link: function (scope, element, attrs, ngModel) {
      // Initialize TinyMCE
      tinymce.init({
        target: element[0],
        plugins: "link code lists fontsize",
        height: attrs.height || 300,
        menubar: false,
        toolbar:
          "undo redo | accordion accordionremove | \
      importword exportword exportpdf | math | \
      blocks fontfamily fontsize | bold italic underline strikethrough | \
      align numlist bullist | link image | table media | \
      lineheight outdent indent | forecolor backcolor removeformat | \
      charmap emoticons | code fullscreen preview | save print | \
      pagebreak anchor codesample | ltr rtl",

        setup: function (editor) {
          // Load existing content when editor is initialized
          editor.on("init", function () {
            editor.setContent(ngModel.$viewValue || ""); // Avoid undefined issues
          });

          // Sync model when TinyMCE content changes
          editor.on("keyup change", function () {
            scope.$applyAsync(function () {
              ngModel.$setViewValue(editor.getContent());
            });
          });

          // Update editor when model changes from outside
          ngModel.$render = function () {
            if (editor && editor.getContent() !== ngModel.$viewValue) {
              editor.setContent(ngModel.$viewValue || "");
            }
          };
        },

        init_instance_callback: function (editor) {
          // Ensure content is restored properly after reload
          editor.setContent(ngModel.$viewValue || "");
        },
      });

      // Proper cleanup to avoid multiple instances
      scope.$on("$destroy", function () {
        if (tinymce.get(element[0].id)) {
          tinymce.get(element[0].id).remove();
        }
      });
    },
  };
});
