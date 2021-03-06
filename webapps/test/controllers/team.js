app.controller('team-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Name'];
    $scope.properties = ['id','name'];
    $scope.query.fields = "id,name";

    $scope.searchTemplate = '../test/views/team-search.html';
});
app.controller('team-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('team-update', function ($scope,$routeParams,$controller,$translate,CRUD) {


    $scope.query = {populate:[{person:{path:"players"}}]};
});