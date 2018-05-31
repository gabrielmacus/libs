<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace system\modules\menu;

require_once (ROOT_PATH.'/system/modules/crud/controller.php');

use system\modules\crud\CrudController;

class MenuController extends CrudController
{
    static $paginationLimit = 15;

}