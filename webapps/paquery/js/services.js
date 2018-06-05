app.service('CRUD', function ($http) {
    this.url = "";


    this.read=function(params,success,error) {
        $http({
            method : "GET",
            url : this.url,
            params:params

        }).then(success,error);

    }
    this.delete=function(id,success,error)
    {
        $http({
            method : "DELETE",
            url : this.url+id
        }).then(success,error);
    }

});