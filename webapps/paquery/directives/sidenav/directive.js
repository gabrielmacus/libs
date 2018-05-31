
app.directive('sidenav', function() {
    return {
        restrict: 'E',
        replace:true,
        scope: {
            menuItems: '='
        },
        templateUrl: 'directives/sidenav/view.html',
        controller:function ($scope) {
            
        
        }
        
    }
});