
app.directive('mediaPreview', function() {
    return {
        restrict: 'E',
        scope: {
            src:'='
            },
        transclude:true,
        templateUrl: '../system/directives/media-preview/view.html',
        controller:function ($scope,$http,FileType) {



            $scope.FileType  = FileType;

        }

    }
});