<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace system\modules\post;

require_once(ROOT_PATH . '/system/modules/crud/controller.php');

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use system\libs\orm\ORMObject;
use system\libs\Services;
use system\modules\crud\CrudController;

class PostController extends CrudController
{
    static $paginationLimit = 20;

    protected static function Validate(ORMObject $object,$rules = null,$messages=[],$aliases =[])
    {

        $rules = [
            "title"=>"required",
            "body"=>"required"
        ];
        $messages =[
            "required"=>":attribute es requerido"
        ];
        $aliases = [
            "title"=>"Título",
            "body"=>"Texto"
        ];






        return parent::Validate($object, $rules,$messages,$aliases);
    }


}