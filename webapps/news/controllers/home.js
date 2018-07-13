app.controller('home-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Nombre'];
    $scope.properties = ['id','name'];
    $scope.query.fields = "id,name";

});
app.controller('home-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('home-update', function ($scope,$routeParams,$controller,$translate,CRUD) {
    $scope.query = {populate:[{post:{type:"child",path:"mainBlock"},file:{type:'child',path:'images'}}]};

});