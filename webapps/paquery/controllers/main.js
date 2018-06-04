
app.controller('main', function ($scope,$rootScope) {

    $rootScope.errorHandler=function (error) {
        console.log(error);
    }

});