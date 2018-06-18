
app.directive('relatedItems', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            module:'=',
            deleted:'='
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
            $scope.deleteRelated=function (key) {
                if(!$scope.deleted)
                {
                    $scope.deleted = [];
                }
                var relatedItem  =$scope.model[key];

                if(relatedItem.id)
                {
                    $scope.model.splice(key,1);
                    $scope.deleted.push(relatedItem);

                }

            }
            $scope.sortableConf = {
                animation: 200,
                handle: '.grab-handle',
                forceFallback: true,
            };

            $scope.goToSelect=function () {

                $scope.relatedItemsLightbox = true;

                $controller('list', {$scope: $scope,$routeParams:$routeParams});

                $scope.$watch('model',function (newVal) {
                    if(newVal)
                    {
                        for(var k in $scope.model)
                        {
                            if(!$scope.model[k]._relationData)
                            {
                                $scope.model[k]._relationData={};
                            }
                            $scope.model[k]._relationData.module = $scope.module;
                        }
                    }
                },true);


                $scope.multipleActions = $scope.multipleActions.concat([
                    {
                        title:'Accept',
                        icon:"fas fa-check",
                        visible:function () {
                            return $scope.getSelectedRows().length > 0;
                        },
                        action:function () {

                            var selected = $scope.getSelectedRows();
                            if(!     $scope.model )
                            {
                                $scope.model=[];
                            }
                            for(var k in selected){
                                $scope.model.push(selected[k]);
                            }

                            $scope.closeRelatedItemsLightbox();
                        }
                    }

                ]);

                //$location.path('/'+$scope.module);
            }


        }

    }
});