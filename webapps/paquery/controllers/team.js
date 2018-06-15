app.controller('team-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Name'];
    $scope.properties = ['id','name'];
    $scope.query.fields = "id,name";


});
app.controller('team-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

    //populate[0][person][path]=players


});
app.controller('team-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

    CRUD.url +="?populate[0][person][path]=players";
});