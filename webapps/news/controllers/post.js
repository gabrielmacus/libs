app.controller('post-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Title'];
    $scope.properties = ['id','title'];
    $scope.query.fields = "id,title";


});
app.controller('post-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('post-update', function ($scope,$routeParams,$controller,$translate,CRUD) {
    $scope.query = {populate:[{file:{type:"child",path:"images"}}]};

});