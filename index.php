<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/04/2018
 * Time: 23:33
 */

class Demo
{
    /**
     * @var string
     */
    public $demo;
}


$r = new ReflectionClass(Demo::class);

$comment = $r->getProperty("demo")->getDocComment();

$pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";

preg_match_all($pattern,$comment,$matches,PREG_PATTERN_ORDER);

$commentArray = [];

foreach ($matches[0] as $k=>$match)
{
    $explode =  explode(" ",$match);
    $commentArray[$explode[0]] = $explode[1];
}


var_dump($commentArray);