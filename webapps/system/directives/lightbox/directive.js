
app.directive('lightbox', function() {
    return {
        restrict: 'E',
        scope: {
            opened:'='
        },
        transclude:true,
        templateUrl: '../system/directives/lightbox/view.html',
        controller:function ($scope,$http) {


        }

    }
});