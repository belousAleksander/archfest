app.controller('projects', function ($scope, $http) {
    'use strict';
    $scope.$parent.action.saveImg = saveProjectImg;
    $scope.$parent.action.loadFlash = loadFlash;
    $scope.$parent.action.removeFlash = removeFlash;

    if(removeImageAction) {
        $scope.$parent.action.removeImg = removeImageAction;
    }
    $scope.$parent.model.logo = projectsInfo.projectLogo;
    $scope.$parent.model.images = objectImages || [];
    $scope.$parent.model.flashInfo = flashInfo || [];
    console.log(flashInfo);
});