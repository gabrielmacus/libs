<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace user\modules\team;

require_once (ROOT_PATH.'/system/modules/crud/controller.php');

use system\modules\crud\CrudController;

class TeamController extends CrudController
{
    static $paginationLimit = 5;

}