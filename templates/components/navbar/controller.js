$(document).on("click",function (e) {


    $("[data-navbar] [data-item]").each(function () {
        var item = $(this);

        if(item.has(e.target).length && !(item == e.target))
        {

        }
        else
        {

            item.find("[type='checkbox']").prop("checked",false);
        }

    });


});