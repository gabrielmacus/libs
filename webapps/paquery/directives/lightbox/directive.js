
app.directive('lightbox', function() {
    return {
        restrict: 'E',
        scope: {
            opened:'='
        },
        transclude:true,
        templateUrl: 'directives/lightbox/view.html',
        controller:function ($scope,$http) {


        }

    }
});