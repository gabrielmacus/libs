
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

function JSON_to_URLEncoded(element,key,list){
    var list = list || [];
    if(typeof(element)=='object'){
        for (var idx in element)
            JSON_to_URLEncoded(element[idx],key?key+'['+idx+']':idx,list);
    } else {
        list.push(key+'='+encodeURIComponent(element));
    }
    return list.join('&');
}


/**
 * -- Params --
 * 1: Group ids JSON array
 * 2: Email
 * 3: Password
 * 4: Quantity of pages to fetch
 */

var args = require('system').args;

var fs = require('fs');

var page = require('webpage').create();
var request = require('webpage').create();;

var groups = JSON.parse(args[1]);

page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';

var status = false;

//https://www.facebook.com/groups/407850475895832/local_members/
function logIn(user,password) {

    page.evaluate(function (user,password) {

        document.querySelector("[name='email']").value=user;
        document.querySelector("[name='pass']").value=password;
        document.querySelector("[type='submit']").click();

    },user,password);

}
function goToGroup(id) {

    results =[];
    jsonPath ="members-"+id+".json";
    page.open("https://www.facebook.com/groups/"+id+"/local_members/");
}




var prevCount = 0;
var pageNumber = 1;

var maxTimeout = 3500;
var elapsedTime = 0;
var maxPages = parseInt(args[4]);


var results = [];
var jsonPath="";
function fetchPage() {



        // Checks for bottom div and scrolls down from time to time
        var interval = window.setInterval(function() {

            elapsedTime+=500;

            if(pageNumber==150){
                page.render('whats.png');

            }

            if((maxPages> 0 && pageNumber >maxPages) || elapsedTime >= maxTimeout)
            {

                //Saves results to json
                fs.write(jsonPath,JSON.stringify(results),'w');

                status = 'end';
                page.open("https://facebook.com");
                window.clearInterval(interval);
            }

            // Checks if there is a div with class=".has-more-items"
            // (not sure if this is the best way of doing it)
            var count = page.evaluate(function () {
                return document.querySelectorAll("#groupsMemberBrowser .uiList > .clearfix").length;
            });




            if(count == prevCount) { // Didn't find
                page.evaluate(function() {

                    if( document.querySelector("a.uiMorePagerPrimary"))
                    {
                        // Scrolls to the bottom of page
                        //window.document.body.scrollTop = document.body.scrollHeight;
                        document.querySelector("a.uiMorePagerPrimary").click();
                    }

                });
            }
            else { // Found
                // Do what you want
                elapsedTime = 0;
                var items = page.evaluate(function () {

                    var dom = document.querySelectorAll("#groupsMemberBrowser .uiList > .clearfix");
                    var items = [];
                    for(var i=0;i<dom.length;i++)
                    {
                        var item = {};
                        item.img = dom[i].querySelector("img").src;
                        item.name =  dom[i].querySelector("img").getAttribute("aria-label");
                        item.url = dom[i].querySelector("a").href;
                        item.location_work='';
                        if(dom[i].querySelector("._60rj"))
                        {
                            item.location_work = dom[i].querySelector("._60rj").innerText;
                        }
                        items.push(item);
                    }

                    return  items;
                });


                console.log("Retrieving page "+pageNumber);

                 results = results.concat(items);
                 console.log(results.length+" results so far...");
                prevCount = count;
                pageNumber++;

            }
        }, 500); // Number of milliseconds to wait between scrolls



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
                status="log-in";
                page.open("https://www.facebook.com/login.php");

            }
            else if(status == "log-in")
            {
                status = 'logged-in';
                logIn(args[2],args[3]);
            }
            else if(status == "logged-in")
            {

                status="in-group";
                goToGroup(item);

            }
            else if(status == "in-group")
            {

                status="in-group";
                fetchPage();


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
    page.open("https://facebook.com");

});
