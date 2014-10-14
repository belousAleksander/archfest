app.controller('aboutUsController', function ($scope, $http) {
    'use strict';
    $scope.showBiography = function ($event, action) {
        var modal = $('#biography');
        $("*").css('cursor', 'wait');
        $http({method: 'GET', url: action}).
            success(function(data) {
                var contentDiv = modal.find('div.modal-content');
                contentDiv.html(data);
                modal.modal('show');
                $("*").css('cursor', '');

            }).
            error(function() {
                $("*").css('cursor', '');
            });
    }
});