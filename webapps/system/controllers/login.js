app.controller('login', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD,$cookies,$location,Authorization) {




    $scope.sendLogin=function () {

        Authorization.login($scope.login,function () {

            $cookies.put("_token",Authorization.token,{'path':'/'});
            $location.path('/');
        });

    }

});


