<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 21:58
 */

namespace system\modules\permission;

use system\libs\orm\ORMObject;

class Permission extends ORMObject
{
    public $module;
    public $action;

}