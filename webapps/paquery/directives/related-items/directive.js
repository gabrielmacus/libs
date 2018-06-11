
app.directive('relatedItems', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            module:'='
        },
        transclude:true,
        templateUrl: 'directives/related-items/view.html',
        controller:function ($scope,$http,CRUD,$location) {

            $scope.goToCreate=function () {

                $location.path('/'+$scope.module+'/save');

            }

            $scope.goToSelect=function () {

                $location.path('/'+$scope.module);
            }

        }

    }
});