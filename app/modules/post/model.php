<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 21:58
 */

namespace user\modules\post;

use system\libs\orm\ORMObject;

class Post extends ORMObject
{
    public $title;
    public $body;

}