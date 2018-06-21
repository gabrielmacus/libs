/** Functions **/
var checkTplUrl = function(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return (http.status !== 404) ? url : false;
};
/** End functions **/


var app = angular.module("app", ['ngSanitize',"ngRoute",'pascalprecht.translate','ng-sortable']);


app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "views/home.html"
        })
        .when("/:module",{
            templateUrl : function (params) {

                var tpl = 'views/'+params.module+"-list.html";

                if(!checkTplUrl(tpl))
                {
                    tpl = '../system/views/list.html';
                }
                return tpl;
            },
            controller: "list"
        })
        .when("/:module/save",{
            templateUrl : function (params) {
                return 'views/'+params.module+"-save.html";
            },
            controller: "save"
        })
        .when("/:module/save/:id",{
            templateUrl : function (params) {
                return 'views/'+params.module+"-save.html";
            },
            controller: "save"
        })
        .otherwise({
            //TODO: 404
            redirectTo: '/route1/default-book/default-page'
        });

});
app.config(['$translateProvider', function ($translateProvider, $translatePartialLoaderProvider) {
    $translateProvider.useStaticFilesLoader({
        files: [
            {
                prefix: 'lang/',
                suffix: '.json'
            }]
    });
    $translateProvider.preferredLanguage('es');
}]);


app.controller('controller', function($scope) {
    $scope.start=function () {
        alert('a');
    }
});