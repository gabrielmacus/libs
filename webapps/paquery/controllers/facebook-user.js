app.controller('facebook-user-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['Name','Location_Work'];
    $scope.properties = ['name','location_work'];
    $scope.query.fields = "name,location_work";



});
app.controller('facebook-user-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

});
app.controller('facebook-user-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

});