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


define('ROOT_PATH',__DIR__);

//Includes envs
$envs =
    [
        "production",
        "development"
    ];

foreach ($envs as $env)
{
    if(file_exists(ROOT_PATH."/user/enviroments/{$env}.env.php"))
    {
        include_once ROOT_PATH."/user/enviroments/{$env}.env.php";
    }

}


