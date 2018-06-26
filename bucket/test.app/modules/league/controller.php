<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace user\modules\league;

require_once(ROOT_PATH . '/system/modules/crud/controller.php');

use system\modules\crud\CrudController;

class LeagueController extends CrudController
{
    static $paginationLimit = 5;

}