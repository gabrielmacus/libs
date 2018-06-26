app.controller('login', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD,$location,Authorization) {



    $scope.sendLogin=function () {

        Authorization.login($scope.login,function () {

            $location.path('/');
        });

    }

});


