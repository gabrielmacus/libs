
app.directive('field', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            inputType:'=?'
        },
        transclude:true,
        templateUrl: 'directives/field/view.html',
        controller:function ($scope,$http) {

            if(typeof $scope.inputType === 'undefined')
            {
                $scope.inputType ='text';

            }


            $scope.getInclude=function () {



                switch ($scope.inputType)
                {
                    default:

                    return 'directives/field/input-field.html';
                        break;

                    case 'textarea':
                        return 'directives/field/textarea-field.html';
                        break;

                }
            }

        }

    }
});