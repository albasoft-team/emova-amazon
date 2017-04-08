'use strict';
/**
 *
 */
emovaApp.controller('orderlineController', ['$scope','$http','$rootScope','$window', function ($scope, $http, $rootScope, $window) {
    $scope.orderLines = {};
    $rootScope.setTheStutus = function () {
        $rootScope.status = '';
    };
    $scope.setStatus = function (id) {
        var data = {id:id};
        $http.post('/orderline/updateStatus',data, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }})
            .then(function successCallback(response) {
           $scope.orderLines = response.data;
           $('#closepop').click();
                $window.location.reload();
        }, function errorCallback(response) {
                alert("impossible de recupérer les donnée");
        })
    };
    $scope.getAll = function () {
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
