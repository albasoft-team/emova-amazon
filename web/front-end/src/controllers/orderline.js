'use strict';
/**
 *
 */
emovaApp.controller('orderlineController', ['$scope','$http','$rootScope','$window','NgTableParams', function ($scope, $http, $rootScope, $window, ngTableParams) {
    $scope.statusCmd = '';
    $rootScope.setTheStutus = function () {
        $rootScope.status = '';
    };
    $scope.setStatus = function (id, statut) {
        var result ; var idpass ;
        if (typeof id == "object") {
                result =   id.reduce(function(result, item) {
                var key = Object.keys(item)[0];
                result[key] = item[key];
                return result;
            }, {});
            idpass = result[0];
        }
        else {
            idpass = id;
        }
        var data = {id:idpass};
        $http.post('/orderline/updateStatus', data, {headers : {'Content-Type': 'application/json'}})
            .then(function successCallback(response) {
               $scope.getAll();
                 $('.close').click();

                if (typeof id !== "object") {
                    $window.location.reload();
                }
        }, function errorCallback(response) {
                alert("impossible de recupérer les donnée");
        })
    };
    $scope.getAll = function (statut) {
        $http.get('/orderline/allCmdr').then(function successCallback(response) {
            constructCmds(response.data);
            if (statut=='arrive')
            pagination($scope.orderLines);
            if (statut=='preparee')
                pagination($scope.orderLinesPre);
            if (statut=='envoyee')
                pagination($scope.orderLinesEnv);
            if (statut=='livree')
                pagination($scope.orderLinesLiv);
        }, function errorCallback(response) {

        });
    };
    var constructCmds = function (allCmd) {
        $scope.orderLines = [];
        $scope.orderLinesPre =[];
        $scope.orderLinesEnv = [];
        $scope.orderLinesLiv = [];
        angular.forEach(allCmd, function (value, key) {
            if (value.statut === 'arrive') {
                $scope.orderLines.push(value);
            }
            if (value.statut === 'preparee') {
                $scope.orderLinesPre.push(value);
            }
            if (value.statut === 'envoyee') {
                $scope.orderLinesEnv.push(value);
            }
            if (value.statut === 'livree') {
                $scope.orderLinesLiv.push(value);
            }
        });

    };
    $scope.orderLinesAll = ''; $scope.data ='';
    $scope.getCommandeArrive = function (statut) {
        $http.get('/orderline/allCmd/'+statut)
            .then(function successCallback(response) {
                $scope.orderLines = response.data;
                pagination($scope.orderLines);
            }, function errorCallback(response) {

            });
    };
    $scope.getCommandePre = function (statut) {
            $http.get('/orderline/allCmd/'+statut)
                .then(function successCallback(response) {
                    $scope.orderLinesPre = response.data;
                    pagination($scope.orderLinesPre);
                }, function errorCallback(response) {

                });
        };

    $scope.getCommandeEnv = function (statut) {
            $http.get('/orderline/allCmd/'+statut)
                .then(function successCallback(response) {
                    $scope.orderLinesEnv = response.data;
                    pagination($scope.orderLinesEnv);
                }, function errorCallback(response) {

                });
        };

     $scope.getCommandeLiv = function (statut) {
            $http.get('/orderline/allCmd/'+statut)
                .then(function successCallback(response) {
                    $scope.orderLinesLiv = response.data;
                    pagination($scope.orderLinesLiv);
                }, function errorCallback(response) {

                });
        };
    
    var pagination = function (orderlines) {
        $scope.orderLinesAll = new ngTableParams({
            page: 1,
            count: 5
        }, {
            getData: function (params) {
                params.total(orderlines.length);
               return orderlines.slice((params.page() - 1) * params.count(), params.page() * params.count());
            }
        });
    };
    $scope.result = [];
    $scope.getNumberComandes = function () {
        $http.get('/orderline/getNumberCmd')
            .then(function successCallback(response) {
                var results = response.data;
                angular.forEach(results, function (value,key) {
                    var statut = {}
                    statut.label = value.statut;
                    statut.value = value[1]
                    $scope.result.push(statut);
                });
            }, function errorCallback(response) {

            });
    };
    $scope.orderlineDataSource = {
        chart : {
            "yaxisname": "Nombre",
            "xaxisname": "Commandes",
            "bgcolor": "FFFFFF",
            "useroundedges": "1",
            // "theme": "ocean",
        },
        data : $scope.result
    };
    $scope.orderlineDataSourcePie = {
        chart : {
            "startingangle": "120",
            "showlabels": "0",
            "showlegend": "1",
            "enablemultislicing": "0",
            "slicingdistance": "15",
            "showpercentvalues": "1",
            "showpercentintooltip": "0",
            // "theme": "zune",
        },
        data : $scope.result
    };

}]);
