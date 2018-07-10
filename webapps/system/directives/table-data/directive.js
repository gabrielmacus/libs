
app.directive('tableData', function() {
    return {
        restrict: 'E',
        scope: {
            headers:'=',
            properties:'=',
            title:'=',
            rows:'=',
            pagination:'=',
            emptyText:'=',
            currentPage:'=',
            actions:'=',
            status:'=',
            process:'=',
            pagesOffset:'='
        },
        transclude:true,
        templateUrl: '../system/directives/table-data/view.html',
        controller:function ($scope,$http) {

            $scope.select = {all:false};

            $scope.isFunction=function (e) {
                return (typeof e === "function");
            }
            $scope.isObject=function (e) {
                return (typeof e == "object");
            }
            $scope.$watch('select.all',function (newVal,oldVal) {

                for(var k in $scope.rows)
                {
                    $scope.rows[k]._selected = $scope.select.all;
                }

            },true)



        }

    }
});