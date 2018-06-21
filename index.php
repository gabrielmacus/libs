<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 19:41
 */

include "autoload.php";

$pdo =  new PDO($_ENV["db"]["string"] ,$_ENV["db"]["user"] ,$_ENV["db"]["pass"] );


include ROOT_PATH."/user/modules/team/model.php";
include ROOT_PATH."/user/modules/team/controller.php";

include ROOT_PATH."/user/modules/person/model.php";
include ROOT_PATH."/user/modules/person/controller.php";

$player = New \user\modules\person\Person($pdo);
$player->read();

$team = new \user\modules\team\Team($pdo);
$team->readById(1);




\system\libs\Services::BeautyPrint($player);
\system\libs\Services::BeautyPrint($team);

/*
$relation = new \system\libs\orm\ORMRelation($pdo);
$relation->setParent($team);
$relation->setChild($player,"players");
$relation->save();*/






exit();

$router = new AltoRouter();

$router->setBasePath('/libs');
$router->map( 'GET', '/api/[*:module]/[i:id]','Read');
$router->map( 'POST', '/api/[*:module]/[i:id]','Update');
$router->map( 'DELETE', '/api/[*:module]/[i:id]','Delete');

$router->map( 'GET', '/api/[*:module]/','Read');
$router->map( 'POST', '/api/[*:module]/','Create');

// match current request
$match = $router->match();



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


            $pdo =  new PDO($_ENV["db"]["string"] ,$_ENV["db"]["user"] ,$_ENV["db"]["pass"] );
            $model = new $Model($pdo);
            $Controller::$Action($model,$match["params"]);



        }
        catch (Error $e)
        {
            \system\libs\Services::BeautyPrint($e);
            \system\libs\Services::BeautyPrint(\system\libs\orm\ORMObject::$logData);
        }


        exit();
    }





}


http_response_code(404);
//TODO: 404
echo "404";