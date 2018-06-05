<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 10:14 AM
 */

namespace system\modules\crud;

use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;

class CrudController
{

    static $paginationLimit = 100;

    protected static function SendResponse($data,$template = null)
    {
        if(empty($template))
        {
            echo json_encode($data,JSON_NUMERIC_CHECK);
        }
        else
        {
            include $template;
        }
    }

    static function Read(ORMObject $object,$params,$template = null)
    {
        $query = new ORMQuery();
        $query->fields = (!empty($_GET["fields"]))?explode(",",$_GET["fields"]):null;

        //$orderBy = (empty($_GET["sort"]))?"-created_at":$_GET["sort"];

        $orderBy = (empty($_GET["sort"]))?"{$object->prefix}_created_at DESC":$_GET["sort"];

        $query->orderBy = $orderBy;



        $pagination = new ORMPagination();
        $pagination->offset = (!empty($_GET["p"]) && is_numeric($_GET["p"]))?$_GET["p"]-1:0;
        $pagination->limit = static::$paginationLimit;

        if(empty($params["id"]))
        {
            $data = ["results"=>$object->read($query,$pagination),"pagination"=>$pagination];
        }
        else
        {
            $object->readById($params["id"]);
            $data = (!empty($object->id))?$object:[];
        }


        self::SendResponse($data,$template);

    }

    static function Update(ORMObject $object,$params,$template = null)
    {
        $object->id = (!empty($params["id"]))?$params["id"]:null;

        self::AssignProperties($object);


        $object->save();

        $object->readById($object->id);

        self::SendResponse($object,$template);
    }

    protected static function AssignProperties(ORMObject &$object)
    {
        foreach ($_POST as $key => $value)
        {
            $object[$key] = $value;
        }
    }

    static function Delete(ORMObject $object,$params,$template=null)
    {
        $object->id = (!empty($params["id"]))?$params["id"]:null;
        $result = $object->delete();

        self::SendResponse($result,$template);
    }

    static function Create(ORMObject $object,$params,$template = null)
    {

        self::AssignProperties($object);

        $id = $object->save();

        $object->readById($id);

        self::SendResponse($object,$template);


    }

}