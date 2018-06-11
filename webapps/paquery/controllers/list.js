app.controller('list', function ($scope,$http,$routeParams,$controller,$rootScope,$translate,CRUD,$location) {

    if(!$scope.module)
    {
        $scope.module = $routeParams.module;
    }


    $scope.emptyText ='No results available';

    CRUD.url = '/libs/api/'+$scope.module+'/';


    $scope.title = $translate.instant($scope.module+' list');

    $scope.actions = [

        {title:'Delete element',icon:'fas fa-trash',action:function (item) {

            $scope.openDeleteLightbox(item);
        }},
        {title:'Edit element',icon:'fas fa-edit',action:function (item) {
            $location.path('/'+$scope.module+"/save/"+item.id);
        }}
    ];

    $scope.multipleActions = [

        {
            title:'Create',
            icon:"fas fa-file",
            action:function () {
            $location.path("/"+$scope.module+"/save");
            }
        }
        ,
        {
            title:'Delete elements',
            visible:function () {
            return $scope.getSelectedRows().length > 0;
            },
            titleData:function () {
            return {count:$scope.getSelectedRows().length};
            },
            icon:"fas fa-trash",
            action:function () {
            $scope.openDeleteLightbox();
            }
        }
    ]


    $scope.delete= function () {
        asyncForEach($scope.itemsToDelete,function () {



        },function (item,index,next) {


            CRUD.delete(item.id,function (response) {
                $scope.read();
                $scope.closeDeleteLightbox();
                next();
            },function (response) {

                $scope.closeDeleteLightbox();

                $rootScope.errorHandler(response);

            })


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
            $scope.itemsToDelete = [item];
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

    $controller($scope.module+'-list', {$scope: $scope,$routeParams:$routeParams});



    $scope.read();




});