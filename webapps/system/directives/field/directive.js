
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

           scope.fileFieldLoaded=function () {
               var fileInput =element.children().find("div").find("input");


               if(scope.inputData && scope.inputData.multiple)
               {

                   fileInput[0].setAttribute("multiple","multiple");
               }
               else
               {
                   fileInput[0].removeAttribute("multiple");
               }

               fileInput.on("change",function (e) {
                   if(this.files)
                   {

                       scope.model =(scope.inputData && scope.inputData.multiple)?this.files:this.files[0];
                       scope.$apply();

                   }
               });
           }
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
                    case 'file':
                        return'../system/directives/field/file-field.html';
                        break;
                    default:

                        return '../system/directives/field/input-field.html';
                        break;


                }
            }

        }

    }
});