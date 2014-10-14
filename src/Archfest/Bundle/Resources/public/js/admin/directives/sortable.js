/**
 * Подгружаем таблицу
 */
app.directive('sortable', function () {
    'use strict';
    return {
        restrict: "A",
        scope:{
            disabled: "@"
        },
        link: function ($scope, $elem, $attr) {
            $scope.$watch('disabled', function () {
                console.log('dddd');
                if($scope.disabled == 'true') {
                    $elem.sortable( "enable" );
                } else {
                    $elem.sortable( "disable" );
                }
            });

            $(document).on('click', '.changePosition', function (e) {
                var button = $(this),
                    href = button.attr('href');
                $.ajax({
                    method: 'POST',
                    url: '' + href,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (response) {
                    window.location.reload();
                });
                return false;
            });

            $elem.sortable({
                    opacity:0.8,
                    cancel: '',
                    forceHelperSize:true,
                    placeholder: 'draggable-placeholder',
                    forcePlaceholderSize:true,
                    tolerance:'pointer',
                    stop: function( event, ui ) {//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
                        $(ui.item).css('z-index', 'auto');
                        var dataForSend = {
                            id: ui.item.attr('data-object-id'),
                            value: ui.item.index()
                        };
                        $elem.addClass('processing');
                        $.ajax({
                            method: 'POST',
                            url: '' + $attr.action,
                            data: dataForSend,
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        }).success(function (response) {
                            $elem.removeClass('processing');
                        });
                    }
                }
            ).disableSelection().sortable( "disable" );;
        }
    };
});