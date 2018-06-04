app.controller('list', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD) {

    $scope.emptyText =$translate('No results available');

    $scope.actions = [

        {title:'Demo title',icon:'fas fa-trash',action:$scope.openDeleteLightbox},
        {title:'Demo title',icon:'fas fa-edit'}
    ];

    $scope.delete= function () {
        asyncForEach($scope.itemsToDelete,function () {



        },function (item,index,next) {


            $http({
                method : "DELETE",
                url : '/libs/api/'+$routeParams.module+"/"+item.id
            }).then(function(response) {

                $scope.read();
                $scope.closeDeleteLightbox();
                next();

            }, function (response) {

                $scope.closeDeleteLightbox();

                $rootScope.errorHandler(response);

            });

        })

    }

    $scope.itemsToDelete = [];

    $scope.query=  {p:1};

    $scope.read = function () {

        $scope.status = 'loading';

        CRUD.read($scope.query,function (response) {


            $scope.rows = (response.data.results)?response.data.results:[];
            $scope.pagination =  (response.data.pagination)?response.data.pagination:[];

            if($scope.pagination.offset && $scope.pagination.offset > 0 && $scope.rows.length == 0)
            {
                $scope.query.p = $scope.pagination.pages;
                $scope.read();
            }
            else
            {
                $scope.status = 'loaded';
            }
        },$rootScope.errorHandler);



    }

    $scope.$watch('query',function (newVal,oldVal) {

        if(newVal != oldVal)
        {
            $scope.read();
        }

    },true);

    $scope.getSelectedRows=function () {

        return ($scope.rows)?$scope.rows.filter(function (el) {
            return el._selected == true;
        }):[];

    }

    $scope.openDeleteLightbox = function (item) {

        if(item)
        {
            $scope.itemsToDelete.push(item);
        }
        else
        {
            var selectedRows = $scope.getSelectedRows();
            $scope.itemsToDelete = $scope.itemsToDelete.concat(selectedRows);
        }


        $scope.deleteLightbox = true;

    }

    $scope.closeDeleteLightbox = function () {
        $scope.itemsToDelete = [];
        $scope.deleteLightbox = false;
    }

    $controller($routeParams.module+'-list', {$scope: $scope,$routeParams:$routeParams});



    $scope.read();




});