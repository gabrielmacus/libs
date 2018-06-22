<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.5/angular-route.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-translate/2.18.1/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-sanitize.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-animate.js"></script>

    <script>


        var app = angular.module("app", ['ngSanitize',"ngRoute",'pascalprecht.translate','ngAnimate']);

        app.config(function($routeProvider) {
            $routeProvider
                .when("/", {
                    templateUrl : "views/home.html",
                    controller:"home-controller"
                })
                .when("/sent-messages", {
                    templateUrl : "views/sent-messages.html",
                    controller:"home-controller"
                })
                .when("/contact-me", {
                    templateUrl : "views/contact-me.html",
                    controller:"contact-me-controller"
                })


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

        var max_messages = 150;

        app.controller('home-controller', function($scope,$window) {


            $scope.loadMessages=function () {
                var messages= ($window.localStorage.getItem("whagen_messages"))?JSON.parse($window.localStorage.getItem("whagen_messages")):[];

                return messages;
            };

            $scope.saveMessage=function (message) {


                $scope.sentMessages.push(message);

                if($scope.sentMessages.length > max_messages)
                {
                    $scope.sentMessages=   $scope.sentMessages.slice(1,$scope.sentMessages.length);
                }


                $window.localStorage.setItem("whagen_messages",JSON.stringify($scope.sentMessages));

            }

            $scope.sentMessages = $scope.loadMessages();


            $scope.deleteMessage=function (message) {

               $scope.sentMessages.splice($scope.sentMessages.indexOf(message),1);


                $window.localStorage.setItem("whagen_messages",JSON.stringify($scope.sentMessages));
            }

            $scope.generateMessage=function () {

                $scope.message.text = (!$scope.message.text)?"":$scope.message.text;
                $scope.message.time = new Date();
                var apiUrl = 'https://api.whatsapp.com/send';

                if($window.innerWidth > 800){
                    apiUrl = 'https://web.whatsapp.com/send';
                }

                var url = apiUrl+"?phone="+$scope.message.phone+"&text="+encodeURIComponent($scope.message.text);

                $scope.saveMessage(angular.copy($scope.message));

                $window.open(url);


            }

        });


        app.controller('contact-me-controller',function ($scope,$http) {

            $scope.sendContact=function () {

                var contact= angular.copy($scope.contact);
                var data  = Object.keys(contact).map(function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(contact[k])
                }).join('&');

                $http.post('contact-me.php',data,{
                    headers : {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                    }
                })
                    .then(function (response) {
                        if(response.data == "false")
                        {
                            alert($scope.$eval("'Error al enviar el formulario de contacto. Intentalo más tarde.' | translate"));
                        }
                        else
                        {
                            $scope.formSent =true;
                        }


                    },function (response) {
                        console.log(response);
                    })

            }

        })






    </script>

    <title>Whagen</title>


</head>
<body data-ng-app="app">

<nav class="float-left">
    <a class="logo">
        <!--
        <img src="https://png.pngtree.com/element_our/md/20180301/md_5a9797d574aa7.png">-->
    </a>
    <a class="logo-title">Whagen</a>
    <a class="logo-slogan">
        <p>{{'Enviá Whatsapps sin agendar contactos' | translate}}</p>
    </a>
</nav>



<main  data-ng-view class="fade">



</main>




</body>
</html>