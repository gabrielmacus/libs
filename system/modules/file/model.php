<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 25/06/2018
 * Time: 17:07
 */

namespace system\modules\file;


use system\libs\orm\ORMObject;

class File extends ORMObject
{

    public $name;
    public $description;
    public $size;
    public $extension;
    public $extra_1;
    public $extra_2;
    public $path;




}