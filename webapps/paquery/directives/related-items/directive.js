
app.directive('relatedItems', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
        },
        transclude:true,
        templateUrl: 'directives/related-items/view.html',
        controller:function ($scope,$http,CRUD) {


        }

    }
});