
app.directive('paginator', function() {
    return {
        restrict: 'E',
        scope: {
            offset:'=',
            pagination:'=',
            page:'='

        },
        templateUrl: '../system/directives/paginator/view.html',
        controller:function ($scope,$http) {

            console.log($scope.page);
            $scope.goToPage = function(page)
            {
                console.log("Going to page "+page);
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