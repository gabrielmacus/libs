app.controller('client-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Name','Surname'];
    $scope.properties = ['id','name','surname'];
    $scope.query.fields = "id,name,surname";



});
app.controller('client-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('client-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

});