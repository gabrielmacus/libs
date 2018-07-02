


app.factory('FileType',function () {
    return function (filename) {
        if(!filename)
        {
            return false;
        }

        var mime = filename.match(/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).*,.*/);
        var extension ="";

        if(mime && mime.length == 2)
        {
            //is base64 encoded
             extension  = mime[1].split("/");
             extension = extension[1];

        }
        else
        {
             extension = filename.split(".");
             extension =  extension[extension.length-1];
        }



       switch (extension.toLowerCase())
       {
           case 'jpg':
           case 'jpeg':
           case 'png':
           case 'svg':
           case 'gif':
           case 'bmp':
           case 'webp':

               return 'image';

               break;

           case 'ogg':
           case 'mp3':
               return 'audio';
               break;
           case 'webm':
           case 'mp4':
               return 'video';
               break;
           case 'docx':
           case 'doc':
           case 'xls':
           case 'xlsx':
           case 'pdf':

               return 'document';
           default:

               return 'binary';

               break;
       }
    }
});

app.factory('BytesConverter',function () {
    return function (bytes) {

        var sizes = ['bytes', 'kb', 'mb', 'gb', 'tb'];
        if (bytes == 0) return '0 bytes';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    }
});


app.factory('CRUD', function ($http,Authorization) {
    function CRUD(url) {
        this.url =url;
        this.multipart = false;

        this.create=function (data,success,error) {

            var request = {
                method : "POST",
                url : this.url,
                data:window.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded','Authorization':'Bearer '+Authorization.token}

            };
            if(this.multipart)
            {

                var payload = new FormData();
                for(var k in data)
                {

                    if(data[k] instanceof FileList)
                    {
                        var fileList = [];
                        for(var i =0;i<data[k].length;i++)
                        {
                            fileList.push(data[k][i]);
                        }
                        data[k] =fileList;

                    }

                    if(data[k] instanceof Array)
                    {

                        for(var i =0;i<data[k].length;i++)
                        {

                            payload.append(k+"[]", data[k][i]);

                        }
                    }
                    else
                    {

                        payload.append(k, data[k]);
                    }

                }
                request.data = payload;
                request.headers['Content-Type'] = undefined;
                request.transformRequest= angular.identity;
            }
            $http(request).then(success,error);
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