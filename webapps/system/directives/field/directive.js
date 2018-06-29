
app.directive('field', function() {
    return {
        restrict: 'E',
        scope: {
            model:'=',
            label:'=',
            inputType:'=?',
            placeholder:'=?',
            inputData:'=?',
            validationErrors:'=?'

        },
        transclude:true,
        templateUrl: '../system/directives/field/view.html',
        link:function(scope, element, attrs) {

            var s = attrs.model.split(".");
            scope.key = (s.length == 1)?s[0]:s[1];
        },
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