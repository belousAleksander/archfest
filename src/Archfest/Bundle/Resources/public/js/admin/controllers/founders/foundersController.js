app.controller('founders', function ($scope) {
    'use strict';
    $scope.$parent.action.saveImg = saveConstructionImg;

    if(removeImageAction) {
        $scope.$parent.action.removeImg = removeImageAction;
    }

    $scope.$parent.model.images = objectImages || [];

});