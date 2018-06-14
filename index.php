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
$router->map( 'GET', '/api/[*:module]/[i:id]','Read');
$router->map( 'POST', '/api/[*:module]/[i:id]','Update');
$router->map( 'DELETE', '/api/[*:module]/[i:id]','Delete');

$router->map( 'GET', '/api/[*:module]/','Read');
$router->map( 'POST', '/api/[*:module]/','Create');

// match current request
$match = $router->match();

define('ROOT_PATH',__DIR__);

if(!empty($match))
{


    $Action  = $match["target"];
    $Module = $match["params"]["module"];
    $namespace = "";

    //Loads controller
    $Controller = \system\libs\Services::LoadClass($Module,CLASS_TYPE_CONTROLLER);
    //Model controller
    $Model = \system\libs\Services::LoadClass($Module,CLASS_TYPE_MODEL);


    if($Controller && $Model)
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


http_response_code(404);
//TODO: 404
echo "404";