app.controller('person-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Name','Surname','Birthdate'];
    $scope.properties = ['id','name','surname','birthdate'];
    $scope.query.fields = "id,name,surname,birthdate";
    $scope.title = 'Players list';

});
app.controller('person-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

});
app.controller('person-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.query = {populate:[{team:{type:"parent",path:"teams"}}]};
});