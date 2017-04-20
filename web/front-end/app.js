'use strict';

var emovaApp = angular.module('emovaApp',['ngResource','ngTable', 'ng-fusioncharts'])
    .config(['$interpolateProvider', function ($interpolateProvider) {

        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    }]);
