
app.directive('relatedItems', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            module:'=',
            deleted:'=',
            relationType:'=?',
            parentKey:'=?',
            childKey:'=?',
            query:'=?'
        },
        transclude:true,
        templateUrl: '../system/directives/related-items/view.html',
        controller:function ($scope,$http,CRUD,$location,$routeParams,$controller) {

            if(!$scope.relationType)
            {
                $scope.relationType = 'child';
            }



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


            $scope.setModule=function (newVal) {

                if(newVal)
                {
                    for(var k in $scope.model)
                    {
                        if(!$scope.model[k]._relationData)
                        {
                            $scope.model[k]._relationData={};
                        }
                        $scope.model[k]._relationData.module = $scope.module;
                        $scope.model[k]._relationData.type = $scope.relationType;

                        if($scope.relationType == 'child')
                        {
                            $scope.model[k]._relationData.parentKey = ($scope.parentKey)?$scope.parentKey:"";
                        }
                        else if($scope.relationType == 'parent')
                        {
                            $scope.model[k]._relationData.childKey = ($scope.childKey)?$scope.childKey:"";
                        }


                    }
                }
            }


            $scope.$watchCollection('model',function (newVal) {

                if(newVal)
                {
                    if(!$scope.query)
                    {
                        $scope.query = {};
                    }

                    if(! $scope.query.filter)
                    {
                        $scope.query.filter = {};
                    }

                    //Excludes already selected items
                    $scope.query.filter.id = {not:$scope.model.map(function (item) {
                        return item.id;
                    })};

                }



                $scope.setModule(newVal);

            },true);
            $scope.goToSelect=function () {

                $scope.relatedItemsLightbox = true;

                $controller('list', {$scope: $scope,$routeParams:$routeParams});

                $scope.multipleActions.splice(0,1);


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