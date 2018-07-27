<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace system\modules\permission;

require_once(ROOT_PATH . '/system/modules/crud/controller.php');

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use system\libs\orm\ORMObject;
use system\libs\Services;
use system\modules\crud\CrudController;

class PermissionController extends CrudController
{
    static $paginationLimit = 20;


    /**
     * Reads modules to assign permissions to them
     * @param $object
     * @param $params
     * @param $template
     */
    public static function Modules($object, $params, $template)
    {

        static::SendResponse(Services::GetModules(["crud","root-templates","post"]));

    }

    protected static function Validate(ORMObject $object,$rules = null,$messages=[],$aliases =[])
    {


        /*
        $rules = [
            "title"=>"required",
            "body"=>"required",
            "_related.images"=>"required|between:1,2"
        ];
        $messages =[
            "required"=>":attribute es requerido",
            "_related.images:between"=>"Debe seleccionar entre 1 y 2 :attribute",
            "_related.images:required"=>"Debe seleccionar al menos una imágen"
        ];
        $aliases = [
            "title"=>"Título",
            "body"=>"Texto",
            "_related.images"=>"Imágenes"
        ];
        */


        return parent::Validate($object, $rules,$messages,$aliases);
    }


}