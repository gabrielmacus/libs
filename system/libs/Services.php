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


    /**
     * This looks like a mess, and it is, but it's a powerful mess.
     * Auto-scoped $block('name') and template inheritance.
     *
     * "foo.php" child view:
     *
     * 		<?php $title = 'SEO Title'; ?>
     * 		<?php $nav = view('nav', array('links'=> $links_array))); ?>
     * 		This is the content of foo.php
     * 		<?php $extends = 'layout'; ?>
     *
     * "nav.php" view:
     *
     * 		<ul>
     * 		<?php foreach($links as $a => $link): ?>
     * 			<li><a href="<?= $a; ?>"><?= $link; ?></a></li>
     * 		<?php endif; ?>
     * 		</ul>
     *
     * Then inside 'layout.php':
     *
     * 		<?= $block('nav', '<ul>...</ul>'); ?>
     * 		<h1><?= $block('title', 'Hello World'); ?></h1>
     * 		<?= $block('content'); ?>
     * 		&copy; 2014 Company
     */
    static function View($file, $data = array())
    {
        $__scope = $data;
        unset($data);
        $extends = $file;
        while(isset($extends)) {
            $__file = $extends;
            unset($extends, $content);
            $__v = $__scope = $__scope + array_diff_key(get_defined_vars(), array('__file'=>0, '__scope'=>0));

            $start = function($name) use(&$__v) {
                ob_start(function($buffer) use(&$name, &$__v) {
                    if(empty($__v[$name])) {
                        $__v[$name] = $buffer;
                    }
                    return $__v[$name];
                });
            };

            $end = function() {
                ob_end_flush();
            };

            $block = function($key, $default = null) use(&$__v) {
                return isset($__v[$key]) ? $__v[$key] : $default;
            };

            ob_start();
            require("theme/$__file.php");
            $content = trim(ob_get_contents());
            extract($__v, EXTR_SKIP);
            // $content is a funny variable (keep the original unless this is the last parent)
            if(isset($__v['content'])) {
                if(empty($__scope['content'])) {
                    $__scope['content'] = $__v['content'];
                }
            }
            unset($__v, $__file, $block, $start, $end);
            ob_end_clean();
        }

        return $content;
    }
   static function NormalizeFiles()
    {
        foreach ($_FILES as $k => $file)
        {
            if(is_array($file["name"]))
            {
                $normalizedArray = [];

                foreach ($file as $j => $property)
                {
                    foreach ($property as $i => $v)
                    {
                        $normalizedArray[$i][$j] =$v;
                    }
                }

                $_FILES[$k] = $normalizedArray;
            }
        }
    }

    static function GetFiles()
    {
        static::NormalizeFiles();
        return $_FILES;
    }
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
    static  function DashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    static function GetModules($exclude = [],$loadActions = false)
    {
        $systemPath = ROOT_PATH."/system/modules/";
        $userPath =  ROOT_PATH."/app/modules/";
        $modules =[];
        $dirs =["system"=>$systemPath,"app"=>$userPath];
        foreach ($dirs as $k=> $dir)
        {
            if($scan = scandir($dir))
            {
                foreach ($scan as $item)
                {
                    if($item != ".." && $item !=".")
                    {

                        if($loadActions && file_exists(static::JoinPath($dir,"{$item}/controller.php")))
                        {
                            include_once (static::JoinPath($dir,"{$item}/controller.php"));
                            $actions = [];
                            $Class = "{$k}\\modules\\{$item}\\".self::DashesToCamelCase($item,true)."Controller";

                            if(method_exists($Class,"GetModuleActions"))
                            {
                                $actions = $Class::GetModuleActions();
                            }

                        }


                        if(!in_array($item,$exclude))
                        {
                            if($loadActions)
                            {
                                $modules[]=["module"=>$item,"actions"=>$actions];
                            }
                            else{
                                $modules[]=$item;
                            }


                        }
                    }
                }
            }
        }
        return $modules;




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
            $namespace = "app\\modules\\".$module."\\";

        }
        else if(file_exists($systemPath.$module."/{$type}.php"))
        {
            include_once ($systemPath.$module."/{$type}.php");
            $namespace = "system\\modules\\".$module."\\";

        }


        if(empty($namespace))
        {
            return false;
        }
        $namespace = str_replace("-","_",$namespace);



        return $namespace.str_replace('-', '', ucwords($module, '-'))."{$suffix}";

    }

    static function BeautyPrint($data){

        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    static function JoinPath($a,$b)
    {
        $a  = rtrim($a,"/");
        $b = ltrim($b,"/");
        return $a."/".$b;
    }
    static function GetFileExtension($filename){

        $ext = explode(".",$filename);

        return end($ext);

    }
}