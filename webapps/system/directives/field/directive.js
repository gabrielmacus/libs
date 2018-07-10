
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



            scope.richTextFieldLoaded=function () {

                 scope.richTextField = element.find("div")[element.find("div").length-2];


            }


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
        controller:function ($scope,$http,$routeParams,$controller,$compile) {


            if(typeof $scope.inputType === 'undefined')
            {
                $scope.inputType ='';

            }


            if($scope.inputType == 'richtext')
            {

                $scope.closeRelatedItemsLightbox=function () {
                    $scope.relatedItemsLightbox = false;
                }
                var unregister = $scope.$watch('model',function () {


                    if($scope.model && $routeParams.id)
                    {

                        $scope.richTextField.innerHTML =  $scope.model;

                        unregister();
                    }

                });

                $scope.lastTextSelection=false;

                $scope.selectText=function () {


                    $scope.lastTextSelection =window.getSelection();

                }
                $scope.changedRichText=function () {

                    $scope.model =$scope.richTextField.innerHTML;

                }

                function appendRichText(text,range) {
                    range.deleteContents();
                    var div = document.createElement("div");
                    div.innerHTML = text;

                    var frag = document.createDocumentFragment(), child;
                    while ( (child = div.firstChild) ) {
                        frag.appendChild(child);
                    }
                    range.insertNode(frag);

                    $scope.changedRichText();
                }
                $scope.modifyRichText=function (type,options) {

//https://stackoverflow.com/questions/3997659/replace-selected-text-in-contenteditable-div
                    //https://stackoverflow.com/questions/6251937/how-to-replace-selected-text-with-html-in-a-contenteditable-element
                    var selection =  $scope.lastTextSelection;

                    if (selection) {

                        var range = selection.getRangeAt(0);
                        var div = document.createElement('div');
                        div.appendChild(range.cloneContents());
                        var text = div.innerHTML;

                        if(text !='')
                        {



                            switch (type)
                            {
                                case 'italic':
                                    text = '<i>'+text+'</i>';
                                    break;
                                case 'bold':
                                    text = '<strong>'+text+'</strong>';
                                    break;
                                case 'clear':
                                    text = div.innerText;
                                    break;

                            }

                            appendRichText(text,range);

                        }

                        switch (type)
                        {
                            case 'image':

                                $scope.relatedItemsLightbox = true;
                                $scope.module = 'file';
                                $controller('list', {$scope: $scope,$routeParams:$routeParams});
                                $scope.multipleActions.splice(0,1);
                                $scope.multipleActions = $scope.multipleActions.concat([
                                    {
                                        title:'Accept',
                                        icon:"fas fa-check",
                                        visible:function () {
                                            return $scope.getSelectedRows().length > 0;
                                        },
                                        action:function () {

                                            var selected = $scope.getSelectedRows();


                                            for(var k in selected){
                                                text = $compile("<media-preview src='\""+selected[k].src+"\"'></media-preview>")($scope)[0];

                                            }

                                            appendRichText(text,range);
                                            $scope.closeRelatedItemsLightbox();
                                        }
                                    }

                                ]);

                            break;
                        }



                    }

                }


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
                    case 'richtext':
                        return '../system/directives/field/richtext-field.html';
                        break;
                    default:

                        return '../system/directives/field/input-field.html';
                        break;


                }
            }

        }

    }
});