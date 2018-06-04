app.controller('client-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Name','Surname'];
    $scope.properties = ['id','name','surname'];
    CRUD.url = '/libs/api/client/';
    $scope.query.fields = "id,name,surname";
    $scope.title = $translate.instant('Clients list');


});