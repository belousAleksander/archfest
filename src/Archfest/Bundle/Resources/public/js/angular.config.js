document.getElementsByTagName('html')[0].setAttribute("ng-app","archfest");

var app = angular.module('archfest', [], function ($interpolateProvider, $httpProvider) {
    'use strict';
    $interpolateProvider.startSymbol('{[');
    $interpolateProvider.endSymbol(']}');
    $httpProvider.defaults.headers['common']['X-Requested-With'] = 'XMLHttpRequest';

    // Используем x-www-form-urlencoded Content-Type
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    $httpProvider.defaults.timeout = 30000;
});
