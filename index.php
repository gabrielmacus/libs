<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 19:41
 */

include "autoload.php";

$router = new AltoRouter();

$router->setBasePath('/libs');
$router->map( 'GET', '/api/[a:module]/[i:id]','Read');
$router->map( 'POST', '/api/[a:module]/[i:id]','Update');

$router->map( 'GET', '/api/[a:module]/','Read');
$router->map( 'POST', '/api/[a:module]/','Create');


// match current request
$match = $router->match();

define('ROOT_PATH',__DIR__);


if(!empty($match))
{


    $systemPath = __DIR__."/system/modules/";
    $userPath = __DIR__."/user/modules/";
    $Action  = $match["target"];
    $namespace = "";

    $controllerLoaded = false;
    $modelLoaded = false;
    //Loads controller
    if(file_exists($userPath.$match["params"]["module"]."/controller.php"))
    {
        include_once ($userPath.$match["params"]["module"]."/controller.php");
        $namespace = "user\\modules\\".$match["params"]["module"]."\\";
        $controllerLoaded = true;
    }
    else if(file_exists($systemPath.$match["params"]["module"]."/controller.php"))
    {
        include_once ($systemPath.$match["params"]["module"]."/controller.php");
        $namespace = "system\\modules\\".$match["params"]["module"]."\\";
        $controllerLoaded = true;
    }

    $Controller = $namespace.str_replace('-', '', ucwords($match["params"]["module"], '-'))."Controller";

    //Loads model
    if(file_exists($userPath.$match["params"]["module"]."/model.php"))
    {
        include_once ($userPath.$match["params"]["module"]."/model.php");
        $namespace = "user\\modules\\".$match["params"]["module"]."\\";
        $modelLoaded = true;

    }
    else if(file_exists($systemPath.$match["params"]["module"]."/model.php"))
    {
        include_once ($systemPath.$match["params"]["module"]."/model.php");
        $namespace = "system\\modules\\".$match["params"]["module"]."\\";
        $modelLoaded = true;
    }

    $Model = $namespace.str_replace('-', '', ucwords($match["params"]["module"], '-'));


    if($controllerLoaded && $modelLoaded)
    {

        try{

            $pdo =  new PDO("mysql:host=localhost;dbname=libs","root","");
            $model = new $Model($pdo);
            $Controller::$Action($model,$match["params"]);



        }
        catch (Error $e)
        {
            var_dump($e);
            var_dump(\system\libs\orm\ORMObject::$logData);
        }


        exit();
    }





}



//TODO: 404
echo "404";