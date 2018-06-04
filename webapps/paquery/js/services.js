app.service('CRUD', function ($http) {
    this.url = "";


    this.read=function(params,success,error) {
        $http({
            method : "GET",
            url : this.url,
            params:params

        }).then(success,error);

    }

});