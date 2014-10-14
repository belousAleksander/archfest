app.controller('baseController', function ($scope, $http) {
    'use strict';
    $scope.action = {
        saveImg: '',
        removeImg: ''
    };
    $scope.model = {
        images: []
    };

    try{
        $scope.model.newImgInfo = newImgInfo;
    }catch (e) {}

    $scope.addNewImg = function () {
        $scope.imgInfo = angular.copy($scope.model.newImgInfo);
    };



    $scope.saveImg = function ($event, index) {
        var $form = $($event.target).closest("form"),
            file = $($form.find('input[type=file]')[0]);
        if(file.val().length === 0 && index === undefined) {
            $.gritter.add({
                title: 'Ошибка',
                text: 'Вы не выбрали изображения для загрузки.',
                class_name: 'gritter-error',
                time: 5000
            });
            return;
        }
        $scope.load = true;
        $form.attr('action', $scope.action.saveImg);

        $form.ajaxSubmit({
            type: 'POST',
            uploadProgress: function(event, position, total, percentComplete) {

                $scope.$apply(function() {
                    // upload the progress bar during the upload
                    $scope.loader.width = percentComplete + '%';
                });

            },
            error: function(event, statusText, responseText, form) {
                // remove the action attribute from the form
                $form.removeAttr('action');
            },
            success: function(response) {
                if(response.success) {

                    if(index === undefined) {
                        $scope.imgInfo.id = response.imgId;
                        $scope.model.images.push($scope.imgInfo);
                    } else {
                        if($scope.imgInfo.main === true) {
                          angular.forEach($scope.model.images, function (currentElement) {
                              currentElement.main = false;
                          })
                        }
                        $scope.model.images[index] = $scope.imgInfo;
                    }
                    file.val('');
                    $('#imgInfo').modal('hide');
                } else {
                    $.gritter.add({
                        title: 'This is a save error',
                        text: response.message,
                        class_name: 'gritter-error',
                        time: 5000
                    });
                }
                $scope.load = false;
                $scope.newImg = null;
                if(!$scope.$$phase) {
                    $scope.$apply();
                }
                // remove the action attribute from the form
                $form.removeAttr('action');

            }
        });
    };

    $scope.getEditImg = function (currentElement) {
        var editingElement = angular.copy(currentElement);
        editingElement.index = $scope.model.images.indexOf(currentElement);
        $scope.imgInfo = editingElement;
    };

    $scope.removeImg = function (index, objectId) {
        var images = $scope.model.images,
            img = images[index];
        /**
         * Уточняем удалять картинку или нет
         */
        if (confirm('Вы действительно хотите удалить это изображения?')) {
            $http({method: 'POST',
                url: $scope.action.removeImg,
                data: decodeURIComponent($.param({imgId: img.id, objectId: objectId}))}).
                success(function(response) {
                    if(response.success) {
                        images.splice(index, 1);
                    } else {
                        $.gritter.add({
                            title: 'This is a save error',
                            text: response.message,
                            class_name: 'gritter-error',
                            time: 5000
                        });
                    }
                }).
                error(function(data, status, headers, config) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }
    };

    $scope.loadFlash = function (e, flash) {
        $scope.editingFlash = flash || null;
        $('#infoFlash').modal('show');
    };


    $scope.addFlash = function (e, flash) {
    // Create a formdata object and add the files
        var data = new FormData(),
            button = $(e.target),
            input = button.closest('div.modal-footer')
                .prev('div.modal-body').find('input[type="file"]');
            if(!input[0].files[0]) {
                alert('Не выбран файл для загрузки');
                return;
            }
            $('*').css('cursor', 'wait');
        if(flash){
            data.append('flashId', flash.id);
        }

        data.append('flash', input[0].files[0]);
        $.ajax({
            url: $scope.action.loadFlash,
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(response)
            {
                if(response.success) {
                    $scope.$apply(function () {
                        if(!flash) {
                            $scope.model.flashInfo.push(response.flashInfo);
                        }
                        input.val('');
                    });
                $('#infoFlash').modal('hide');
                } else {
                    $.gritter.add({
                        title: 'This is a save error',
                        text: response.message,
                        class_name: 'gritter-error',
                        time: 5000
                    });
                }
                $('*').css('cursor', '');

            },
            error: function(jqXHR, textStatus)
            {
                $.gritter.add({
                    title: 'This is a save error',
                    class_name: 'gritter-error',
                    time: 5000
                });
                $('*').css('cursor', '');
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });

    };

    $scope.removeFlash = function (flash) {
        /**
         * Уточняем удалять flash
         */
        if (confirm('Вы действительно хотите удалить?')) {
            $('*').css('cursor', 'wait');
            $.ajax({
                url: $scope.action.removeFlash,
                type: 'POST',
                data: $.param({flashId: flash.id}),
                success: function(response)
                {
                    if(response.success) {
                        $scope.$apply(function () {
                            var index = $scope.model.flashInfo.indexOf(flash);
                            $scope.model.flashInfo.splice(index, 1);
                        });
                    } else {
                        $.gritter.add({
                            title: 'This is a save error',
                            text: response.message,
                            class_name: 'gritter-error',
                            time: 5000
                        });
                    }
                    $('*').css('cursor', '');
                },
                error: function()
                {
                    $('*').css('cursor', '');
                    $.gritter.add({
                        title: 'This is a save error',
                        class_name: 'gritter-error',
                        time: 5000
                    });
                }
            });
        }
    };

});