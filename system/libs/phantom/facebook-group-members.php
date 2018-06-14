<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 14/06/2018
 * Time: 02:58 PM
 */
ini_set('max_execution_time', 0);
set_time_limit ( 0 );

define('ROOT_PATH',__DIR__.'/../../../');
include "../../../autoload.php";
include "../../../user/modules/facebook-user/controller.php";
include "../../../user/modules/facebook-user/model.php";



$pdo =  new PDO("mysql:host=localhost;dbname=libs","root","");



$group = (!empty($_GET["group"]))?$_GET["group"]:false;

if($group && file_exists("members-{$group}.json"))
{

    $contents =json_decode( file_get_contents("members-{$group}.json"),true);

    $count = 0;
    foreach ($contents as $content)
    {

        $orm = new \user\modules\facebook_user\FacebookUser($pdo);
        foreach ($content as $k=>$v)
        {
            $orm->$k =$v;
        }
        $id = $orm->save();

        if($id)
        {
            $count++;
        }
        else{
            echo  "Error saving item<br>";
        }

    }

    echo "{$count} facebook users saved in database";

}
else{
    die("File doesn't exists");
}