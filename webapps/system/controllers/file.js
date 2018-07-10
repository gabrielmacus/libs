app.controller('file-list', function ($sce,$parse,$scope,$routeParams,$controller,$translate,CRUD,BytesConverter,FileType,$compile) {


    $scope.headers = ['','Name','Size'];

    function sizeProcess(item) {
        return BytesConverter(item.size);
    }

    $scope.properties = [{type:'file',property:'src'},'name',sizeProcess];
    $scope.query.fields = "id,name,size,path,created_at";
    $scope.query.sort ="-created_at";
    $scope.query.filter= (!$scope.query.filter)?{}:$scope.query.filter;

    $scope.query.filter.filter_any={name:true};
    $scope.searchTemplate = '../system/views/file-search.html';

    /*
    $scope.actions.push({icon:"far fa-eye",title:"Vista previa",action:function (item) {

    }})*/

});
app.controller('file-create', function ($scope,$routeParams,$controller,$translate,CRUD) {

    $scope.uploads  = [];
    $scope.saveSingle  =angular.copy( $scope.save);

    $scope.save = function () {

        for(var i=0;i<$scope.uploads.length;i++)
        {
            $scope.item = $scope.uploads[i];


            $scope.saveSingle();
        }
    }

    $scope.$watchCollection('files',function (newVal) {

        if($scope.files)
        {

            asyncForEach($scope.files,function () {

                $scope.$apply();

            },function (item,i,next) {
                //Reads files for previews
                var reader  = new FileReader();

                var f = $scope.uploads.filter(function (t) { if(!t.file || !t.file.name){return false;} return t.file.name == $scope.files[i].name });

                if(!f.length)
                {

                    reader.onloadend = function () {

                        $scope.uploads.push({src:reader.result,file:$scope.files[i],name:$scope.files[i].name,preview:reader.result});
                        next();
                    }
                    reader.readAsDataURL(item);

                }
                else
                {
                    next();
                }


            });


        }

    });


    CRUD.multipart = true;
});
app.controller('file-update', function ($scope,$routeParams,$controller,$translate,CRUD) {
    $scope.save =function () {

        /*
        if(typeof $scope.item.file === "object")
        {
            $scope.item.file = $scope.item.file[0];
        }*/


        $scope.saveSingle();
    };
    $scope.$watch('item.file',function (newVal) {

        if(newVal)
        {
            var reader  = new FileReader();

            reader.onloadend = function () {
                $scope.item.src=  reader.result;
                $scope.$apply();
            }
            reader.readAsDataURL(newVal);


        }

    },true);

});