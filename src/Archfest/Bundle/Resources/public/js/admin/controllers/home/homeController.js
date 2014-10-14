app.controller('home', function ($scope, $http) {
    'use strict';
    $scope.model = model;
    $scope.send = function () {
        $("html, body").animate({ scrollTop: 0 }, 200);
        $("#alert").html('<img src="/Resources/images/ajax-loader.gif">');
        var dataForSend = $scope.model;
        $http({
            method: 'POST',
                url: '/ajax.php?package=admin-dashboard&controller=home',
                data: decodeURIComponent($.param(dataForSend)),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .success(function (response) {
                var responseClass = 'alert-error';
                if(response.success) {
                    responseClass = 'alert-success';
                }
                $("#alert").html(response.message).addClass(responseClass).show('slow').delay( 2000 ).hide("slow");

        });
    }
});