app.directive('flashRender', function () {
    'use strict';
    return {
        restrict: "E",
        replace: true,
        scope: {
            src: "="
        },
        template: '<div></div>',
        link: function ($scope, $elem, $attr){
            $scope.$watch('src', function (){
                if($scope.src) {
                    $elem.html('<object data='+ $attr.asset + $scope.src +'></object>');
                }
            });

        }
    };
});