<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 21:58
 */

namespace app\modules\person;

use system\libs\orm\ORMObject;

class Person extends ORMObject
{
    public $name;
    public $surname;
    public $birthdate;

}