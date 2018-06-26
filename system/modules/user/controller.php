<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 25/06/2018
 * Time: 17:07
 */

namespace system\modules\user;

require_once (ROOT_PATH.'/system/modules/crud/controller.php');

use Firebase\JWT\JWT;
use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\Services;
use system\modules\crud\CrudController;

class UserController extends CrudController
{
    static $paginationLimit = 15;

    protected static function BeforeCreate(ORMObject &$object, &$params = null, &$template = null)
    {

        if(!empty($object->password))
        {
            $object->password = password_hash($object->password,PASSWORD_DEFAULT);
        }

    }

    protected static function BeforeUpdate(ORMObject &$object, &$params = null, &$template = null)
    {
        unset($object->password);
    }

    static function Login(ORMObject &$object, &$params = null, &$template = null)
    {
        $status = 400;
        $result = "Incorrect login data";

        if(!empty($_POST["username"]) && !empty($_POST["password"]))
        {
            $query  = new ORMQuery();
            $query->where = "{$object->prefix}_username = ?";
            $query->params[] =$_POST["username"];
            $pagination = new ORMPagination();
            /**
             * @var $object User
             */
            $user = $object->read($query,$pagination);

            if(count($user))
            {
                if(password_verify($_POST["password"],$user[0]->password))
                {
                    $status = 200;
                    $result = $user[0];

                    unset($result["password"]);

                    $time = time();
                    $key =$_ENV["app"]["jwt"]["key"];
                    $token = array(
                        'iat' => $time, // Tiempo que inició el token
                        'aud'=> Services::Aud(),
                        'exp' => $time + $_ENV["app"]["jwt"]["exp"], // Tiempo que expirará el token
                        'data' => $result
                    );
                    $jwt = JWT::encode($token, $key);
                    $result = ["token"=>$jwt,"user"=>$result];



                }


            }

        }

       http_response_code($status);
       static::SendResponse($result,$template);
    }

}