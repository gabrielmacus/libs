app.controller('home-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID'];
    $scope.properties = ['id'];
    $scope.query.fields = "id";

});
app.controller('home-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('home-update', function ($scope,$routeParams,$controller,$translate,CRUD) {
    $scope.query = {populate:[{post:{type:"child",path:"mainBlock"},file:{type:'child',path:'images'}}]};

});