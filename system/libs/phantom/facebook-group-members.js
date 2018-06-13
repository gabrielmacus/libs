
console.log("Starting...");
/**
 * An asynchronous for-each loop
 *
 * @param   {array}     array       The array to loop through
 *
 * @param   {function}  done        Callback function (when the loop is finished or an error occurs)
 *
 * @param   {function}  iterator
 * The logic for each iteration.  Signature is `function(item, index, next)`.
 * Call `next()` to continue to the next item.  Call `next(Error)` to throw an error and cancel the loop.
 * Or don't call `next` at all to break out of the loop.
 */
function asyncForEach(array, done, iterator) {
    var i = 0;
    next();

    function next(err) {
        if (err) {
            done(err);
        }
        else if (i >= array.length) {
            done();
        }
        else if (i < array.length) {
            var item = array[i++];
            setTimeout(function() {
                iterator(item, i - 1, next);
            }, 0);
        }
    }
}

/**
 * -- Params --
 * 1: Group ids JSON array
 * 2: Email
 * 3: Password
 *
 */

var args = require('system').args;

var fs = require('fs');

var page = require('webpage').create();



var groups = JSON.parse(args[1]);

function logIn() {

    page.evaluate(function(args){
        document.querySelector("input[name='email']").value = args[2];
        document.querySelector("input[name='pass']").value = args[3];
        document.querySelector("#login_form").submit();
        console.log("Logged in ");
    },args);
}

function postInGroup() {


    for(var i=1;i<=3;i++)
    {
        var arg = 6+i;
        if(args[arg])
        {
            page.uploadFile('input[name="file'+i+'"]', args[arg]);
        }


    }


    page.evaluate(function (args) {
        document.querySelector("[name='composer_attachment_sell_title']").value=args[4];
        document.querySelector("[name='composer_attachment_sell_price']").value=args[5];
        document.querySelector("[name='xc_message']").value=args[6];
        document.querySelector("[type='submit']").click();

    },args);

}

function onConsoleMessage(msg) {
    console.log(msg);
};
function sellSomething() {
    page.evaluate(
        function () {
            document.querySelector("[href*='/groups/sell/']").click()
        }
    );
}
function goHome() {

    var goHome= page.evaluate(function () {

        if( document.querySelector("[target]"))
        {
            document.querySelector("[target]").click();
            return false;
        }
        else
        {
            return true;
        }
    });

    if(goHome)
    {
        page.open("https://www.facebook.com/home.php");
    }


}

var jsonPath ='';
function goToGroup(id) {

    console.log("Going to group "+id);
    jsonPath=id+'-members.json';
    page.open("https://m.facebook.com/browse/group/members/?id="+id);

}
page.onConsoleMessage = onConsoleMessage;
page.onError =function (e) {

    console.error(e);
    phantom.exit(1);
}
page.onCallback = function(data) {

    switch (data.type)
    {
        case "goto":

            page.open(data.url);

            break;
    }


};
page.settings.userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko';

var status = false;

var membersPage = 1;
var membersMaxPages=300;
var results = [];

function fetchMembersPage() {


    if(membersPage <= membersMaxPages)
    {
        console.log("Fetching page "+membersPage);


        status="in-group";
        membersPage++;

        var data = page.evaluate(
            function () {

                var results = [];
                var rows = document.querySelectorAll(".ba > table > tbody > tr");

                for(var i=0;i<rows.length;i++)
                {
                    var row = rows[i];
                    var name = row.querySelector("h3").innerText;
                    var link = row.querySelector("a").href;
                    var img = row.querySelector("img").src;
                    results.push({name:name,link:link,img:img});
                }

                return {results:results,href:document.querySelector("#m_more_item > a").href};
            }
        );

        results = results.concat(data.results);
        page.open(data.href);


    }
    else
    {

        fs.write(jsonPath,JSON.stringify(results),'w');
        status = 'end';
        //page.open("https://www.facebook.com/home.php");
     }


}



asyncForEach(groups,function () {
    phantom.exit();
},function (item,index,next) {
    var groupId = item;

    page.onLoadFinished=function (response) {

        if(response=='success')
        {
            console.log("Status: "+status);

            page.render('C:\\Users\\Puers\\Pictures\\facebook\\image'+groupId+"-"+status+".png");

            if(!status || typeof  status == "undefined" || status == "false")
            {

                logIn();
                status="onetouch-login";
            }
            else if(status =="onetouch-login")
            {

                status="logged-in";
                goHome();


            }
            else if(status == "logged-in")
            {
                status="in-group";
                goToGroup(groupId);

            }
            else if(status == "in-group")
            {

                fetchMembersPage();
            }
            else if(status=='end')
            {
                console.log("Succesfully fetched pages");

                status='logged-in';
                next();
            }
            else
            {
                console.log("Unknown status:"+status);
                phantom.exit();
            }

        }
        else
        {
            console.log("Failed");
            phantom.exit();
        }

    }
    page.open("https://m.facebook.com");

});
