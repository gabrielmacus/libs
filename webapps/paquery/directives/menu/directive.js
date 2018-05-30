
app.directive('sidenav', function() {
    return {
        restrict: 'E',
        replace:true,
        scope: {
            menuItems: '='
        },
        templateUrl: 'directives/menu/view.html',
        controller:function ($scope) {
            
        
        }
        
    }
});