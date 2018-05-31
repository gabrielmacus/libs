var app = angular.module("app", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "views/home.html"
        })

});

app.controller('controller', function($scope) {
    $scope.start=function () {
        alert('a');
    }
});