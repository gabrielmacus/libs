<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 22:02
 */

include "vendor/autoload.php";
include  "system/libs/orm/autoload.php";

spl_autoload_register(function ($class) {
    echo $class;
    require_once ucfirst($class) . '.php';
});
