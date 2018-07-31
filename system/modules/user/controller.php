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
use system\libs\Auth;
use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\Services;
use system\modules\crud\CrudController;
use system\modules\module\Module;
use system\modules\role\Role;

class UserController extends CrudController
{
    static $paginationLimit = 15;

    use Auth;



    protected static function BeforeCreate(ORMObject &$object, &$params = null, &$template = null)
    {
        unset($object->root);

        if(!empty($object->password))
        {
            $object->password = password_hash($object->password,PASSWORD_DEFAULT);
        }

    }

    protected static function BeforeUpdate(ORMObject &$object, &$params = null, &$template = null)
    {
        unset($object->root);
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
                    $Role =  Services::LoadClass("role",CLASS_TYPE_MODEL);
                    $Module =  Services::LoadClass("module",CLASS_TYPE_MODEL);
                    $role = new $Role($object->PDOInstance);
                    $module = new $Module($object->PDOInstance);

                    $user->populate($role)->populate($module,"permissions");


                    $status = 200;
                    $result = $user[0];


                    if(empty($result["root"]))
                    {
                        $result["role"] = $result["_related"]["role"][0]["name"];
                        $result["permissions"] = array_map(function($el){

                            return ["module"=>$el["name"],"action"=>$el["_relationData"]["extra_2"],"level"=>$el["_relationData"]["extra_1"]];

                        },$result["_related"]["role"][0]["_related"]["permissions"]);
                    }


                    unset($result["_related"]);
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

    static function Logged(ORMObject &$object, &$params = null, &$template = null)
    {

        $user = static::checkAuthentication(false,null,$token);
        $isLoggedIn = ["user"=>$user,"token"=>$token];
        static::SendResponse($isLoggedIn);

    }


}