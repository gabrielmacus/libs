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

/**
 * Action
 */
$router->map( 'GET|POST', '/api/[*:module]/[a:action]/','');
$router->map( 'GET|POST', '/api/[*:module]/[i:id]/[a:action]','');


/**
 * CRUD
 */
$router->map( 'POST', '/api/[*:module]/','Create');
$router->map( 'GET', '/api/[*:module]/','Read');
$router->map( 'GET', '/api/[*:module]/[i:id]','Read');
$router->map( 'POST', '/api/[*:module]/[i:id]','Update');
$router->map( 'DELETE', '/api/[*:module]/[i:id]','Delete');


// match current request
$match = $router->match();


if(!empty($match))
{


    $Action  = (!empty($match["target"]))?$match["target"]:$match["params"]["action"];
    $Module = $match["params"]["module"];
    $namespace = "";

    //Loads controller
    $Controller = \system\libs\Services::LoadClass($Module,CLASS_TYPE_CONTROLLER);
    //Model controller
    $Model = \system\libs\Services::LoadClass($Module,CLASS_TYPE_MODEL);


    if($Controller)
    {

        try{


            if(method_exists($Controller,$Action))
            {

                if($Model)
                {
                    $pdo =  new PDO($_ENV["db"]["string"] ,$_ENV["db"]["user"] ,$_ENV["db"]["pass"] );
                    $model = new $Model($pdo);
                    $Controller::$Action($model,$match["params"]);
                }
                else{
                    $Controller::$Action($match["params"]);
                }


                exit();
            }



        }
        catch (Error $e)
        {
            \system\libs\Services::BeautyPrint($e);
            \system\libs\Services::BeautyPrint(\system\libs\orm\ORMObject::$logData);
        }



    }





}


http_response_code(404);
//TODO: 404
echo "404";