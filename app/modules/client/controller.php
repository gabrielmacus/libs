<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace user\modules\client;

require_once(ROOT_PATH . '/system/modules/crud/controller.php');

use system\modules\crud\CrudController;

class ClientController extends CrudController
{
    static $paginationLimit = 5;

}