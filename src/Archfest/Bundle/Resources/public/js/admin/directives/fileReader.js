app.directive("fileread", [function () {
    return {
        scope: {
            imgId: '@',
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = scope.fileread || {};
                        scope.fileread.src = loadEvent.target.result;
                        scope.fileread.file = changeEvent.target.files[0];
                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);