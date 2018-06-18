app.controller('facebook-user-list', function ($window,$scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['Name','Location_Work'];
    $scope.properties = ['name','location_work'];
    $scope.query.fields = "img,name,location_work,url";


    $scope.query.filter_any=true;
    $scope.actions.push({icon:"fas fa-external-link-square-alt",title:"View profile",action:function (item) {
        $window.open(item.url)
    }})


});
app.controller('facebook-user-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

});
app.controller('facebook-user-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

});