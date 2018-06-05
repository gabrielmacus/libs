app.controller('create', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD,$location) {


    CRUD.url = '/libs/api/'+$routeParams.module+'/';

    $scope.save=function () {

        CRUD.create($scope.item,function (response) {

            console.log(response);

            $location.path('/'+$routeParams.module);

        },$rootScope.errorHandler);

    }


    $scope.goToList=function () {

        $location.path('/'+$routeParams.module);

    }




    $controller($routeParams.module+'-create', {$scope: $scope,$routeParams:$routeParams});



});
app.controller('update', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD) {


    $controller('create', {$scope: $scope,$routeParams:$routeParams});

    CRUD.url = '/libs/api/'+$routeParams.module+'/'+$routeParams.id;

    $controller($routeParams.module+'-update', {$scope: $scope,$routeParams:$routeParams});

    CRUD.read({},function (response) {

        $scope.item = response.data;

    },$rootScope.errorHandler)


});