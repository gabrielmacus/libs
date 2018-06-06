<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 22:02
 */

include "vendor/autoload.php";
include  "system/libs/orm/autoload.php";
include "system/libs/Services.php";
/*
spl_autoload_register(function ($class) {

    $systemPath = __DIR__."/system/modules/";
    $userPath = __DIR__."/system/modules/";
    $resource= "model";
    $class = str_replace('-', '', ucwords($_COOKIE, '-'));
    if(strpos($class,"Controller"))
    {
        $resource="controller";
    }
    if(file_exists($systemPath."{}/{$resource}.php"))
    {

    }
});*/
