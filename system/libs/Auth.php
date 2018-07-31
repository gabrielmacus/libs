<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 25/06/2018
 * Time: 16:53
 */

namespace system\libs;


use Firebase\JWT\JWT;
use PHPUnit\Runner\Exception;

trait Auth
{
    static function checkAuth($params,$sendResponse)
    {
        $decoded = static::checkAuthentication($sendResponse,$params);
        return static::checkAuthorization($decoded,$sendResponse,$params);

    }
    static function checkAuthorization($user,$sendResponse=false,$params=null)
    {

        if(!empty($params) && !$user["root"])
        {

            $foundPermissions = array_filter($user["permissions"],  function ($var) use ($params){


                return $params["module"] == $var["module"] && $var["action"] == Services::DashesToCamelCase($params["action"],true);

            });
            if(empty($foundPermissions))
            {
                if($sendResponse)
                {
                    http_response_code(403);
                    echo json_encode("Action not authorized");
                    exit();
                }
                else{
                   return  false;
                }

            }

        }

        return true;

    }
    static function checkAuthentication($sendResponse=false, $params=null, &$getToken = null)
    {
        $decoded = false;
        $token = false;

        if(!empty($_COOKIE["_token"]))
        {
            $token = $_COOKIE["_token"];
        }


        $requestHeaders = Services::getRequestHeaders();

        if(!empty($requestHeaders["Authorization"]) && strpos($requestHeaders["Authorization"],"Bearer ") !== false)
        {
            $t = str_replace("Bearer ","",$requestHeaders["Authorization"]);
            $token  = ($t != "false")?$t:$token;
        }


        if(!empty($_GET["_token"]))
        {
         $token = $_GET["_token"];
        }




        if($token)
        {
            try{


                /**
                 * @var $decoded \stdClass
                 */
                $decoded =JWT::decode($token,$_ENV["app"]["jwt"]["key"],['HS256']);
                $decoded = json_decode(json_encode($decoded->data),true);
                $getToken = $token;


            }
            catch (\DomainException $e)
            {

            }
            catch (Exception $e)
            {
            }

        }



        if(!$decoded && $sendResponse)
        {

            http_response_code(401);
            echo json_encode("User not authenticated");
            exit();

        }


        return $decoded;



    }

}