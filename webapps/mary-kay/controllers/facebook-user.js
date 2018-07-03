app.controller('facebook-user-list', function ($window,$scope,$routeParams,$controller,$translate,CRUD) {

    $scope.headers = ['','Name','Location_Work'];
    $scope.properties = [function (item) {

        return '<img src="'+item.img+'">';

    },'name','location_work'];

    $scope.query.fields = "img,name,location_work,url";

    $scope.query.filter_any=true;

    $scope.actions.splice(1,1);

    $scope.actions.push({icon:"fas fa-external-link-square-alt",title:"View profile",action:function (item) {
        $window.open(item.url)
    }})
    $scope.pagesOffset = 6;
    $scope.searchTemplate = '../mary-kay/views/facebook-user-search.html';

});
app.controller('facebook-user-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

});
app.controller('facebook-user-update', function ($scope,$routeParams,$controller,$translate,CRUD) {

});