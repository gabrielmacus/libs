<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 25/06/2018
 * Time: 17:07
 */

namespace system\modules\file;

require_once (ROOT_PATH.'/system/modules/crud/controller.php');

use system\libs\orm\ORMException;
use system\libs\orm\ORMObject;
use system\libs\Services;
use system\modules\crud\CrudController;

class FileController extends CrudController
{
    static $paginationLimit = 15;
    static $path ="/media/";

    protected static function Validate(ORMObject $object, $rules = null, $messages = [], $aliases = [])
    {
        //TODO:Validate file upload

        return parent::Validate($object, $rules, $messages, $aliases);
    }

    /**
     * @var $object File
     */
    protected static function BeforeCreate(ORMObject &$object, &$params = null, &$template = null)
    {
        Services::NormalizeFiles();

        //TODO: may be set galleries or file groups
        if(!empty($_FILES))
        {
            $file = reset($_FILES);



            $object->extension = Services::GetFileExtension($file["name"]);
            $filename = mt_rand(10000,99999)."_".$file["name"];
            $dir = Services::JoinPath(static::$path,date("Y/m/d"));
            $path = Services::JoinPath($dir,$filename);

            @mkdir(Services::JoinPath(ROOT_PATH,$dir),0777,true);

            if(!copy($file["tmp_name"],Services::JoinPath(ROOT_PATH,$path)))
            {
                throw new ORMException("Error copying file");
            }
            $object->size = $file["size"];
            $object->path = $path;
        }
    }

    protected static function BeforeUpdate(ORMObject &$object, &$params = null, &$template = null)
    {

        if(!empty($_FILES))
        {   Services::NormalizeFiles();

            //Deletes old file. Uploads new file
            static::BeforeDelete($object, $params, $template);
            static::BeforeCreate($object,$params,$template);
        }


    }


    /**
     * @var $object File
     */
    protected static function BeforeDelete(ORMObject &$object, &$params = null, &$template = null)
    {

        if(!empty($object["path"]))
        {
            $deletePath = Services::JoinPath(ROOT_PATH,$object->path);
            if(file_exists($deletePath))
            {
                if( !unlink($deletePath))
                {
                    throw new ORMException("Error deleting file");
                }
            }

        }


    }

    protected static function AfterRead(&$results, ORMObject &$object, &$params = null, &$template = null)
    {

        /*
         *        //TODO:may be load base url from gallery or file group
         * foreach ($results as $k=>$v)
        {
            if(!empty($v["path"])){

                $v->src = Services::JoinPath($_ENV["app"]["url"],$v["path"]);

                $results[$k] = $v;
            }
        }*/
    }


}