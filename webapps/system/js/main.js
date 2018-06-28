/** Functions **/
var checkTplUrl = function(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return (http.status !== 404) ? url : false;
};
/** End functions **/


var app = angular.module("app", ['ngCookies','ngSanitize',"ngRoute",'pascalprecht.translate','ng-sortable','720kb.datepicker']);


app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "views/home.html"
        })
        .when('/login',
            {
                templateUrl : function (params) {

                    var tpl = 'views/login.html';

                    if(!checkTplUrl(tpl))
                    {
                        tpl = '../system/views/login.html';
                    }
                    return tpl;
                },
                controller:'login'
            })
        .when('/404',
            {
                templateUrl:function (params) {


                    var tpl = 'views/404.html';

                    if(!checkTplUrl(tpl))
                    {
                        tpl = '../system/views/404.html';
                    }
                    return tpl;

                }
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
            redirectTo: '/404'
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



app.run(function ($rootScope, $location, Authorization) {

    // Register listener to watch route changes.
    $rootScope.$on('$routeChangeStart', function (event, next, current) {
        console.clear();

       Authorization.isLoggedIn(function (isLoggedIn) {



           if (!isLoggedIn && $location.$$url != '/login') {

               event.preventDefault();
               $location.path("/login");

           }
           else if (isLoggedIn && $location.$$url == '/login'){
               $location.path("/");
           }

        });



    });

});