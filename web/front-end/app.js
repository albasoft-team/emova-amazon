'use strict';

var emovaApp = angular.module('emovaApp',['ngResource','ngTable'])
    .config(['$interpolateProvider', function ($interpolateProvider) {

        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);
