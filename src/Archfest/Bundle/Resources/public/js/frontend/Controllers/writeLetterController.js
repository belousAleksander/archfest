app.controller('writeLetterController', function ($scope, $http) {
    'use strict';
    $scope.letter = {};

    $scope.sendLetter = function ($event, index) {
        var $form = $($event.target).closest("form"),
            file = $($form.find('input[type=file]')[0]);
        $scope.letterLoad = true;

        $form.ajaxSubmit({
            type: 'POST',
            uploadProgress: function(event, position, total, percentComplete) {

            },
            error: function(event, statusText, responseText, form) {
                $scope.letterLoad = false;
            },
            success: function(response) {
                if(!response.success) {
                    $scope.letterError = response.message;
                    $('.errorLetter').show("slow");

                    setTimeout(function(){
                        $('.errorLetter').hide("slow");
                    }, 2000);
                }
                $scope.letterLoad = false;

                $scope.letter = {};

                $('#letterFile').val('');

                if(!$scope.$$phase) {
                    $scope.$apply();
                }

            }
        });
    };
});