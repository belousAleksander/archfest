app.directive('modal', function () {
    'use strict';
    return {
        restrict: "E",
        transclude: true,
        scope: {
            modalId: "@"
        },
        template:
            "<div class='modal fade' id='{[modalId]}' tabindex='-1' role='dialog' aria-hidden='true'>" +
                "<div class='modal-dialog'>" +
                    "<div class='modal-content'>" +
                        "<div ng-transclude></div>" +
                    "</div><!-- /.modal-content -->" +
                "</div><!-- /.modal-dialog -->" +
            "</div><!-- /.modal -->"
    };
});