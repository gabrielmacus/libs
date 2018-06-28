app.factory('CRUD', function ($http,Authorization) {
    function CRUD(url) {
        this.url =url;

        this.create=function (data,success,error) {
            $http({
                method : "POST",
                url : this.url,
                data:window.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded','Authorization':'Bearer '+Authorization.token}

            }).then(success,error);
        }

        this.read=function(params,success,error) {

            $http({
                method : "GET",
                url : this.url+"?"+window.decodeURI(Qs.stringify(params)),
                headers : {'Authorization':'Bearer '+Authorization.token}
                //params:params

            }).then(success,error);

        }

        this.update=function (data,success,error) {

            this.create(data,success,error);
        }


        this.delete=function(id,success,error)
        {
            $http({
                method : "DELETE",
                url : this.url+id,
                headers : {'Authorization':'Bearer '+Authorization.token}
            }).then(success,error);
        }
    }

    return CRUD;

});

app.factory('Authorization', function ($http,$rootScope) {


    return {
        user:false,
        token:false,
        login:function (data,callback) {

            var factory = this;

            $http({
                method : "POST",
                url :'/libs/api/user/login/',
                data:window.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}

            }).then(function (response) {

                var user =response.data.user;
                var token = response.data.token;

                factory.user = user;
                factory.token = token;
                callback();

            },$rootScope.errorHandler);

        },
        isLoggedIn:function (callback) {

            var factory = this;
            $http.get('/libs/api/user/logged/')
                .then(function (response) {

                    if(response.data)
                    {
                        factory.user = response.data.user;
                        factory.token = response.data.token;
                    }

                    callback(factory.user);

                },$rootScope.errorHandler);

        }

    };

});