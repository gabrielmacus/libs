app.controller('client-list', function ($scope,$routeParams,$controller) {

    $scope.headers = ['Nombre','Apellido','ID'];
    $scope.emptyText ='No hay resultados para mostrar';
    $scope.readUrl = '/orm/api/client/?fields=id,name,surname';
    $scope.title = 'Listado de clientes';

});