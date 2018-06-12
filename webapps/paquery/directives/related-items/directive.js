
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
        controller:function ($scope,$http,CRUD,$location,$routeParams,$controller) {


            $scope.goToCreate=function () {

                $location.path('/'+$scope.module+'/save');

            }
            $scope.closeRelatedItemsLightbox=function () {


                $scope.relatedItemsLightbox = false;

            }


            $scope.goToSelect=function () {

                $scope.relatedItemsLightbox = true;

                $controller('list', {$scope: $scope,$routeParams:$routeParams});


                $scope.multipleActions = $scope.multipleActions.concat([
                    {
                        title:'Accept',
                        icon:"fas fa-check",
                        visible:function () {
                            return $scope.getSelectedRows().length > 0;
                        },
                        action:function () {
                           $scope.model = $scope.getSelectedRows();
                            $scope.closeRelatedItemsLightbox();
                        }
                    }

                ]);

                //$location.path('/'+$scope.module);
            }


        }

    }
});