<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 22:02
 */



include_once "vendor/autoload.php";
include_once  "system/libs/orm/autoload.php";
include_once "system/libs/Services.php";
include_once  "system/libs/Auth.php";


define('ROOT_PATH',__DIR__);

//Includes envs
$envs =
    [
        "production",
        "development"
    ];

foreach ($envs as $env)
{
    $envPath = ROOT_PATH."/app/enviroments/{$env}.env.php";
    if(file_exists($envPath))
    {
        include_once ($envPath);
    }

}


