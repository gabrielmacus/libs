
app.directive('paginator', function() {
    return {
        restrict: 'E',
        scope: {
            offset:'=',
            pagination:'=',
            page:'='

        },
        templateUrl: 'directives/paginator/view.html',
        controller:function ($scope,$http) {

            $scope.goToPage = function(page)
            {
                $scope.page = page;
            }
            $scope.$watch('pagination',function (pagination) {

                if(pagination)
                {
                    var currentPage = pagination.offset;
                    var paginatorStart =  currentPage - $scope.offset;
                    var paginatorEnd = currentPage + $scope.offset;
                    var paginatorArray = [];

                    for(var i=paginatorStart;i<=paginatorEnd;i++)
                    {
                        if(i>-1)
                        {
                            var number = i+1;
                            if(number <= pagination.pages)
                            {
                                paginatorArray.push({number:number});
                            }


                        }

                    }

                    $scope.paginatorArray = paginatorArray;
                }

            })
            
        }

    }
});