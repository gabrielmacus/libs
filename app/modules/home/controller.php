<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 10/07/2018
 * Time: 0:05
 */

namespace app\modules\home;

use system\libs\orm\ORMObject;
use system\modules\crud\CrudController;

require_once ROOT_PATH."/system/modules/crud/controller.php";


class HomeController extends CrudController
{
    static function Read(ORMObject $object, $params, $template = null)
    {
        static::SendResponse([],$template,$params);
    }


}