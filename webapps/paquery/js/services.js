app.service('CRUD', function ($http) {
    this.url = "";

    this.create=function (data,success,error) {
        $http({
            method : "POST",
            url : this.url,
            data:window.param(data),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

        }).then(success,error);
    }

    this.read=function(params,success,error) {
        $http({
            method : "GET",
            url : this.url,
            params:params

        }).then(success,error);

    }

    this.update=function (data,success,error) {

        this.create(data,success,error);
    }


    this.delete=function(id,success,error)
    {
        $http({
            method : "DELETE",
            url : this.url+id
        }).then(success,error);
    }

});