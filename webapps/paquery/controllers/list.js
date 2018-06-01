app.controller('list', function ($scope,$http,$routeParams,$controller) {

    $scope.delete=function () {
        var item = $scope.itemToDelete;
        $http({
            method : "DELETE",
            url : '/libs/api/'+$routeParams.module+"/"+item.id
        }).then(function(response) {

            $scope.$$childHead.read();
            $scope.closeDeleteLightbox();

        }, function (response) {

            console.log(response);
            $scope.closeDeleteLightbox();

        });

    }

    $scope.openDeleteLightbox = function (item) {

        $scope.itemToDelete = item;
        $scope.singleDeleteLightbox = true;

    }
    $scope.closeDeleteLightbox = function () {
        delete $scope.itemToDelete;
        $scope.singleDeleteLightbox = false;
    }


    $scope.actions = [

        {title:'Demo title',icon:'fas fa-trash',action:$scope.openDeleteLightbox},
        {title:'Demo title',icon:'fas fa-edit'}
        ];
    $controller($routeParams.module+'-list', {$scope: $scope,$routeParams:$routeParams});



});