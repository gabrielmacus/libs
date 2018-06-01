app.controller('list', function ($scope,$http,$routeParams,$controller) {

    $scope.delete=function (item) {

        console.log($scope);
        if(confirm("Confirmar accion"))
        {
            $http({
                method : "DELETE",
                url : '/orm/api/'+$routeParams.module+"/"+item.id
            }).then(function(response) {

                $scope.$$childHead.read();
                console.log(response);

            }, function (response) {

                console.log(response);

            });
        }
    }

    $scope.actions = [

        {title:'Demo title',icon:'fas fa-trash',action:$scope.delete},
        {title:'Demo title',icon:'fas fa-edit'}
        ];
    $controller($routeParams.module+'-list', {$scope: $scope,$routeParams:$routeParams});



});