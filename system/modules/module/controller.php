<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 12:14 PM
 */

namespace system\modules\module;




use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\Services;

class ModuleController
{
    protected static function Save(ORMObject $object, $params, $template = null)
    {
        $modules = Services::GetModules(static::GetExcludedModules(),true);

        /**
         * @var \PDO
         */
        $object->PDOInstance->query("TRUNCATE TABLE {$object->table}");

        foreach ($modules as $k=>$module)
        {

            $object = new Module($object->PDOInstance,$object->table,$object->prefix);
            $object["name"] = $module["module"];
            $object["actions"] = json_encode($module["actions"]);
            $object->save();
        }


    }
    static function GetExcludedModules()
    {
        return ["crud","root-templates","post","permission","role"];
    }
    static function Read(ORMObject $object, $params, $template = null)
    {
        if(!empty($_GET["update"]))
        {
            static::Save($object,$params,$template);
        }

        $q = new ORMQuery();
        $p = new ORMPagination();
        $results = $object->read($q,$p);
        foreach ($results as $k=>$v){

            $results[$k]["actions"] = json_decode($results[$k]["actions"],true);

        }
        echo json_encode(["results"=>$results]);
    }

}