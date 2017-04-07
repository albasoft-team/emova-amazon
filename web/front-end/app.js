'use strict';

var emovaApp = angular.module('emovaApp',['ngResource'])
    .config(['$interpolateProvider', function ($interpolateProvider) {

        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);
