var app = angular.module("app", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "views/home.html"
        })
        .when("/:module",{
            templateUrl : "views/list.html",
            controller: "list"
        })
        .otherwise({
            //TODO: 404
            redirectTo: '/route1/default-book/default-page'
        });

});

app.controller('controller', function($scope) {
    $scope.start=function () {
        alert('a');
    }
});