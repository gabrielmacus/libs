
app.directive('tableData', function() {
    return {
        restrict: 'E',
        scope: {
            headers:'=',
            title:'=',
            readUrl:'='
        },
        templateUrl: 'directives/table-data/view.html',
        controller:function ($scope,$http) {

            $scope.query= ($scope.query)?$scope.query:{p:1};
            $scope.read=function () {

                $http({
                    method : "GET",
                    url : $scope.readUrl,
                    params:$scope.query
                }).then(function(response) {

                    $scope.rows = (response.data.results)?response.data.results:[];
                    $scope.pagination =  (response.data.pagination)?response.data.pagination:[];

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