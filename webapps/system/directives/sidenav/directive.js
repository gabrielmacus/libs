
app.directive('sidenav', function() {
    return {
        restrict: 'E',
        replace:true,
        scope: {
            menuItems: '='
        },
        templateUrl: '../system/directives/sidenav/view.html',
        controller:function ($scope,$location,$rootScope) {
            $rootScope.$on('$routeChangeSuccess',
                function(event, viewConfig){
                    for(var k in $scope.menuItems)
                    {
                        $scope.menuItems[k].active = false;

                        if($scope.menuItems[k].href=="#!"+$location.$$path)
                        {
                            $scope.menuItems[k].active=true;
                        }
                    }
                });

        
        }
        
    }
});