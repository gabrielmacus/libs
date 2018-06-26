<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 06/06/2018
 * Time: 12:40 PM
 */

namespace system\libs;
define("CLASS_TYPE_MODEL",1);
define("CLASS_TYPE_CONTROLLER",2);

class Services
{

    static function getRequestHeaders()
    {
        //getallheaders polyfill for nginx
        if (!function_exists('getallheaders'))
        {
            function getallheaders()
            {
                $headers = [];
                foreach ($_SERVER as $name => $value)
                {
                    if (substr($name, 0, 5) == 'HTTP_')
                    {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }

        return getallheaders();

    }
    static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }
    static function LoadClass(string $module,int $type)
    {
        $suffix = '';
        switch ($type)
        {
            case CLASS_TYPE_MODEL:

                $type = 'model';
                break;

            case CLASS_TYPE_CONTROLLER:
                $type ='controller';
                $suffix = 'Controller';
                break;
            default:
                return false;
                break;
        }

        $systemPath = ROOT_PATH."/system/modules/";
        $userPath =  ROOT_PATH."/app/modules/";
        //Loads model
        if(file_exists($userPath.$module."/{$type}.php"))
        {
            include_once ($userPath.$module."/{$type}.php");
            $namespace = "user\\modules\\".$module."\\";

        }
        else if(file_exists($systemPath.$module."/{$type}.php"))
        {
            include_once ($systemPath.$module."/{$type}.php");
            $namespace = "system\\modules\\".$module."\\";

        }

        $namespace = str_replace("-","_",$namespace);

        if(empty($namespace))
        {
            return false;
        }

        return $namespace.str_replace('-', '', ucwords($module, '-'))."{$suffix}";

    }

    static function BeautyPrint($data){

        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}