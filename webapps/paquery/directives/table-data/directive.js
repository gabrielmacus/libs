
app.directive('tableData', function() {
    return {
        restrict: 'E',
        scope: {
            headers:'=',
            title:'=',
            readUrl:'=',
            deleteUrl:'=',
            emptyText:'=',
            actions:'='
        },
        templateUrl: 'directives/table-data/view.html',
        controller:function ($scope,$http) {


            $scope.query= ($scope.query)?$scope.query:{p:1};

            $scope.changeSelectAll=function () {

                var selectAll = document.querySelector("#select-all").checked;

                for (var k in $scope.rows)
                {
                    $scope.rows[k]._selected = selectAll;
                }

            }

            $scope.getSelectedRows=function () {

                return ($scope.rows)?$scope.rows.filter(function (el) {
                    return el._selected == true;
                }):[];

            }

            $scope.read=function () {

                $scope.status = 'loading';

                $http({
                    method : "GET",
                    url : $scope.readUrl,
                    params:$scope.query
                }).then(function(response) {

                    $scope.rows = (response.data.results)?response.data.results:[];
                    $scope.pagination =  (response.data.pagination)?response.data.pagination:[];

                    $scope.status = 'loaded';

                }, function (response) {

                    console.log(response);

                });

            }

            $scope.$watch('query',function (newVal,oldVal) {

                if(newVal != oldVal)
                {
                    $scope.read();
                }

            },true);

            $scope.read();

        }

    }
});