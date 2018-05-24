<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 24/05/2018
 * Time: 11:09 AM
 */

namespace form\orm;
define("PARENT_RELATION_COMPONENT",1);
define("CHILD_RELATION_COMPONENT",2);


trait ORMPopulate
{
    function &populate(&$objectToPopulate,ORMObject $object,string $path = "",$type = PARENT_RELATION_COMPONENT)
    {
        if(!is_a($objectToPopulate,ORMArray::class) && !is_a($objectToPopulate,ORMObject::class))
        {
            throw new ORMException("Object to populate should be either a ORMArray or a ORMObject");
        }

        if(method_exists($objectToPopulate,"getArrayCopy"))
        {
            $objArray = $objectToPopulate->getArrayCopy();
        }
        else{
            $objArray = [$objectToPopulate];

        }

        $ids = array_map(function($el){return $el->id;},$objArray);

        $component_type = ($type == CHILD_RELATION_COMPONENT)?"child":"parent";

        $component_type_reverse = ($type == CHILD_RELATION_COMPONENT)?"parent":"child";

        $oSql = "SELECT * FROM _relations
           WHERE relation_{$component_type}_id IN (".implode(",",$ids).") AND 
           relation_{$component_type_reverse}_table = '{$object->table}' AND
           relation_{$component_type_reverse}_key = '{$path}'";








        echo $oSql;

    }

}