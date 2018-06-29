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
    static $path = "/media/";

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
        //TODO: may be set galleries or file groups
        $file = reset($_FILES);
        $object->extension = Services::GetFileExtension($file["name"]);
        $filename = mt_rand(10000,99999)."_".$file["name"];
        $dir = Services::JoinPath(static::$path,date("Y/m/d"));
        $path = Services::JoinPath($dir,$filename);
        mkdir($dir,0777,true);
        if(!copy($file["tmp_name"],Services::JoinPath(ROOT_PATH,$path)))
        {
            throw new ORMException("Error copying file");
        }
        $object->size = $file["size"];
        $object->path = $dir;
    }
    /**
     * @var $object File
     */
    protected static function BeforeDelete(ORMObject &$object, &$params = null, &$template = null)
    {

        if(!unlink(Services::JoinPath(ROOT_PATH,$object->path)));

    }


}