'use strict';
/**
 *
 */
emovaApp.controller('orderlineController', ['$scope','$http', function ($scope,$http) {
    $scope.test = "test";

    $scope.getAll = function () {
        console.log('hello');
        $http({
            method: 'GET',
            url: '/orderline/allCmd'
        }).then(function successCallback(response) {
            console.log(response.data);
            $scope.orderLines = response.data;
        }, function errorCallback(response) {

        });
    }
}]);
