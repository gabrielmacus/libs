<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 24/05/2018
 * Time: 11:09 AM
 */

namespace system\libs\orm;
use system\libs\orm\ORMArray;
use system\libs\Services;

define("PARENT_RELATION_COMPONENT",1);
define("CHILD_RELATION_COMPONENT",2);


trait ORMPopulate
{
    function &populate(&$objectToPopulate,ORMObject $object,string $path = "",ORMPagination $pagination=null,$type = PARENT_RELATION_COMPONENT)
    {

        if(empty($objectToPopulate))
        {
            return false;
        }

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

        $pagination = !(empty($pagination))?$pagination:new ORMPagination();

        $component_type = ($type == CHILD_RELATION_COMPONENT)?"child":"parent";

        $component_type_reverse = ($type == CHILD_RELATION_COMPONENT)?"parent":"child";


        $whereArray = [];
        foreach ($objArray as $item)
        {
            $whereArray[$item->table][] =$item->id;
        }
        $where="";
        foreach ($whereArray as $table => $items)
        {

            $where.="(relation_{$component_type}_table='{$table}' AND relation_{$component_type}_id IN (";
            foreach ($items as $item)
            {


                $where.="{$item},";

            }
            $where = rtrim($where,",");
            $where.=")) AND ";

        }

        $where = rtrim($where,"AND ");


        $oSql = "SELECT * FROM _relations
           WHERE {$where} AND 
           relation_{$component_type_reverse}_table = '{$object->table}' AND
           relation_{$component_type_reverse}_key = '{$path}'";

        /*
        echo "\n";
        echo $oSql;*/


        $query = new ORMQuery();
        $query->join = "RIGHT JOIN ({$oSql}) as relations ON relations.relation_{$component_type_reverse}_id = {$object->prefix}_id";
        $query->orderBy = "relation_{$component_type_reverse}_position DESC";

        $relatedObjects=  $object->read($query,$pagination);

        foreach ($relatedObjects as $k => $v)
        {

            $searchKey =  array_search($v["relation_{$component_type}_id"], array_column($objArray, "id"));

            if($searchKey !== false)
            {
                $oKey = !empty($v["relation_{$component_type_reverse}_key"])?$v["relation_{$component_type_reverse}_key"]:$v["relation_{$component_type_reverse}_table"];

                if(!isset($objArray[$searchKey]->_related[$oKey]))
                {
                    $objArray[$searchKey]->_related[$oKey] = [];
                }

                $r = new ORMRelation($relatedObjects[$k]->PDOInstance);
                foreach ($relatedObjects[$k] as $i => $j)
                {
                    if(strpos($i,"relation_") !== false)
                    {

                        $rKey=str_replace("relation_","",$i);
                        $r[$rKey] = $j;

                    }
                }

                $relatedObjects[$k]->_relationData = $r;


                $objArray[$searchKey]->_related[$oKey][] = $relatedObjects[$k];


            }
        }



        return $relatedObjects;
    }

}