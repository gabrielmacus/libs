
app.directive('field', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            inputType:'=?',
            placeholder:'=?',
            inputData:'=?'
        },
        transclude:true,
        templateUrl: '../system/directives/field/view.html',
        controller:function ($scope,$http) {




            if(typeof $scope.inputType === 'undefined')
            {
                $scope.inputType ='';

            }

            $scope.formatDate=function (date) {

                date = date.split("-");
                var d = new Date();
                d.setFullYear(date[0],date[1] - 1,date[2]);

                return d.toLocaleDateString();
            }


            $scope.getInclude=function () {
                switch ($scope.inputType)
                {

                    case 'textarea':
                        return '../system/directives/field/textarea-field.html';
                        break;
                    case 'date':
                        return'../system/directives/field/date-field.html';
                        break;

                    default:

                        return '../system/directives/field/input-field.html';
                        break;


                }
            }

        }

    }
});