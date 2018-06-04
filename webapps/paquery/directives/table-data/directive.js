
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
            actions:'='
        },
        transclude:true,
        templateUrl: 'directives/table-data/view.html',
        controller:function ($scope,$http) {

            $scope.select = {all:false};

            $scope.$watch('select.all',function (newVal,oldVal) {

                for(var k in $scope.rows)
                {
                    $scope.rows[k]._selected = $scope.select.all;
                }

            },true)



        }

    }
});