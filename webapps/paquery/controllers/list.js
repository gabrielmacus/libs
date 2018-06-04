app.controller('list', function ($scope,$http,$routeParams,$controller,$rootScope) {


    $scope.itemsToDelete = [];
    $scope.delete=function () {
        asyncForEach($scope.itemsToDelete,function () {

        },function (item,index,next) {


            $http({
                method : "DELETE",
                url : '/libs/api/'+$routeParams.module+"/"+item.id
            }).then(function(response) {

                $scope.$$childHead.read();
                $scope.closeDeleteLightbox();
                next();

            }, function (response) {

                $scope.closeDeleteLightbox();

                $rootScope.errorHandler(response);

            });

        })

    }

    $scope.openDeleteLightbox = function (item) {

        if(item)
        {
            $scope.itemsToDelete.push(item);
        }
        else
        {
            var selectedRows = $scope.$$childHead.getSelectedRows();
            $scope.itemsToDelete = $scope.itemsToDelete.concat(selectedRows);
        }

        $scope.deleteLightbox = true;

    }
    $scope.closeDeleteLightbox = function () {
        $scope.itemsToDelete = [];
        $scope.deleteLightbox = false;
    }


    $scope.actions = [

        {title:'Demo title',icon:'fas fa-trash',action:$scope.openDeleteLightbox},
        {title:'Demo title',icon:'fas fa-edit'}
        ];
    $controller($routeParams.module+'-list', {$scope: $scope,$routeParams:$routeParams});



});