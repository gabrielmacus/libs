<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 13/06/2018
 * Time: 22:42
 */

namespace user\modules\facebook_user;

use system\libs\orm\ORMObject;

class FacebookUser extends ORMObject
{
    public $name;
    public $img;
    public $url;
    public $location_work;


}