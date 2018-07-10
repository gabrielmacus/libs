app.controller('post-list', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['ID','Title'];
    $scope.properties = ['id','title'];
    $scope.query.fields = "id,title";
    $scope.searchTemplate = '../news/views/post-search.html';

});
app.controller('post-create', function ($scope,$routeParams,$controller,$translate,CRUD) {


});
app.controller('post-update', function ($scope,$routeParams,$controller,$translate,CRUD) {
    $scope.query = {populate:[{file:{type:"child",path:"images"}},{file:{type:"child",path:"images2"}}]};

});