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


    protected static function SendResponse($data,$template = null)
    {
        if(empty($template))
        {
            echo json_encode($data);
        }
        else
        {
            include $template;
        }
    }

    static function Read(ORMObject $object,$params,$template = null)
    {
        $query = new ORMQuery();
        $pagination = new ORMPagination();
        $pagination->offset = (!empty($_GET["p"]) && is_numeric($_GET["p"]))?$_GET["p"]-1:0;

        if(empty($params["id"]))
        {

            $data = ["docs"=>$object->read($query,$pagination),"pagination"=>$pagination];
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