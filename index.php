<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 19:41
 */

$_ENV["enviroments"] = ["production", "development"];

include "autoload.php";

$router = new AltoRouter();

$router->setBasePath('/libs');

/**
 * API
 */

/**
 * Action
 */
$router->map( 'GET|POST', '/[api:mode]/[*:module]/[a:action]/','');
$router->map( 'GET|POST', '/[api:mode]/[*:module]/[i:id]/[a:action]','');


/**
 * CRUD
 */
$router->map( 'POST', '/[api:mode]/[*:module]/','Create');
$router->map( 'GET', '/[api:mode]/[*:module]/','Read');
$router->map( 'GET', '/[api:mode]/[*:module]/[i:id]','Read');
$router->map( 'POST', '/[api:mode]/[*:module]/[i:id]','Update');
$router->map( 'DELETE', '/[api:mode]/[*:module]/[i:id]','Delete');

/**
 * Template
 */
$router->map( 'GET', '/[*:module]/','Read');




// match current request
$match = $router->match();



if(!empty($match))
{


    $Action  = (!empty($match["target"]))?$match["target"]:$match["params"]["action"];
    $Module = $match["params"]["module"];
    $Template = null;
    $namespace = "";

    if(empty($match["params"]["mode"]) || $match["params"]["mode"] == "template")
    {

        if(empty($match["params"]["template"]))
        {
            $Template = "index";
        }
        else{
            $Template = $match["params"]["template"];
        }


    }


    //Loads template
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
                    $Controller::$Action($model,$match["params"],$Template);
                }
                else{
                    $Controller::$Action($match["params"],$Template);
                }


                exit();
            }



        }
        catch (Error $e)
        {
            \system\libs\Services::BeautyPrint($e);
            \system\libs\Services::BeautyPrint(\system\libs\orm\ORMObject::$logData);
        }
        catch (Exception $e)
        {
            \system\libs\Services::BeautyPrint($e);
            \system\libs\Services::BeautyPrint(\system\libs\orm\ORMObject::$logData);
        }



    }





}


http_response_code(404);
//TODO: 404
echo "404";