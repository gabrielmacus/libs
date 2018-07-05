<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 05/07/2018
 * Time: 02:46 PM
 */

namespace app\modules\news;

require_once ROOT_PATH."/system/modules/post/controller.php";


use system\libs\orm\ORMObject;
use system\modules\post\PostController;

class NewsController extends PostController
{
    static function Read(ORMObject $object, $params, $template = null)
    {
        $imagesPopulate["file"]["path"] = "images";
        $_GET["populate"][] =$imagesPopulate;

        parent::Read($object, $params, $template);
    }


}