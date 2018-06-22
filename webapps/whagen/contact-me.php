<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/06/2018
 * Time: 0:05
 */


try{
    if(!empty($_POST["email"]) && !empty($_POST["text"]))
    {
        $_ENV["db"]["string"] = "mysql:host=localhost;dbname=whagen";
        $_ENV["db"]["user"] ="root";
        $_ENV["db"]["pass"] ="";

        $pdo =  new PDO($_ENV["db"]["string"] ,$_ENV["db"]["user"] ,$_ENV["db"]["pass"] );
        $oSql = "INSERT INTO contact (contact_email,contact_text,contact_ip) VALUES ('{$_POST["email"]}','{$_POST["text"]}','{$_SERVER['REMOTE_ADDR']}')";

        if( $pdo->exec($oSql))
        {
            echo json_encode(true);
            exit();
        }


    }

}
catch (Exception $e)
{

}
echo json_encode(false);
