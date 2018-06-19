
app.directive('field', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            inputType:'=?',
            placeholder:'=?'
        },
        transclude:true,
        templateUrl: '../system/directives/field/view.html',
        controller:function ($scope,$http) {

            if(typeof $scope.inputType === 'undefined')
            {
                $scope.inputType ='text';

            }


            $scope.getInclude=function () {



                switch ($scope.inputType)
                {
                    default:

                    return '../system/directives/field/input-field.html';
                        break;

                    case 'textarea':
                        return '../system/directives/field/textarea-field.html';
                        break;

                }
            }

        }

    }
});