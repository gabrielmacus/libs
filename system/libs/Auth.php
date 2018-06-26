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
    static function checkAuthorization($sendResponse=false)
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
            $token  = str_replace("Bearer ","",$requestHeaders["Authorization"]);
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


            }
            catch (\DomainException $e)
            {

            }
            catch (Exception $e)
            {
            }

        }

        if(!$sendResponse)
        {

            return $decoded;
        }

        if($decoded)
        {
            //http_response_code(200);
           // echo json_encode($decoded);

        }
        else{


            http_response_code(401);
            echo json_encode("User not authenticated");
            exit();
        }




    }

}