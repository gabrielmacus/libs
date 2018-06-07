<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 10:14 AM
 */

namespace system\modules\crud;

use system\libs\orm\ORMArray;
use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\Services;
use user\modules\person\Person;

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

    static function ProcessQuery(ORMQuery &$query,ORMObject $object)
    {
        if(!empty($query->orderBy))
        {
            $orderBy = [];

            $explode = explode(",",$query->orderBy);

            foreach ($explode as $k=>$v)
            {
                if($asc = strpos($v,"+") === 0 || $desc = strpos($v,"-") === 0 )
                {
                    $orderBy[$k] = $object->prefix."_".str_replace("-","",str_replace("+","",$v)). " ";
                    $orderBy[$k] .= (isset($asc))?"ASC":"DESC";

                }
                $query->orderBy = implode(",",$orderBy);
            }
        }

        if(!empty($query->fields))
        {
            $query->fields = explode(",",$query->fields);

            foreach ($query->fields as $k=>$v)
            {
                $query->fields[$k] = $object->prefix."_".$v;
            }

            $query->fields = implode(",",$query->fields);

        }
        if(!empty($query->groupBy))
        {
            $query->groupBy = explode(",",$query->groupBy);

            foreach ($query->groupBy as $k=>$v)
            {
                $query->groupBy[$k] = $object->prefix."_".$v;
            }

            $query->groupBy = implode(",",$query->groupBy);
        }
    }

    static function ProcessPopulate(ORMArray &$results,\PDO $pdo,$params){

        if(is_array($params)  && count($params) > 0)
        {
            foreach ($params as $arr)
            {


                foreach ($arr as $module => $data)
                {
                    $type = (!empty( $data["type"]))? $data["type"]:"parent";
                    switch ($type)
                    {
                        case "parent":

                            $type = PARENT_RELATION_COMPONENT;

                            break;
                        case "child":

                            $type = CHILD_RELATION_COMPONENT;

                            break;
                    }


                    $path = (!empty($data["path"]))?$data["path"]:"";


                    $Model =Services::LoadClass($module,CLASS_TYPE_MODEL);

                    $obj = new $Model($pdo);

                    if($Model)
                    {

                       $results->populate($obj,$path,null,$type);


                    }

                }

            }

        }


    }



    static function Read(ORMObject $object,$params,$template = null)
    {
        $query = new ORMQuery();

        $query->orderBy = (empty($_GET["sort"]))?"-created_at":$_GET["sort"];
        $query->fields = (!empty($_GET["fields"]))?$_GET["fields"]:null;
        $query->groupBy = (!empty($_GET["group"]))?$_GET["group"]:null;
        self::ProcessQuery($query,$object);

        $pagination = new ORMPagination();
        $pagination->offset = (!empty($_GET["p"]) && is_numeric($_GET["p"]))?$_GET["p"]-1:0;
        $pagination->limit = static::$paginationLimit;

        if(empty($params["id"]))
        {
            include ROOT_PATH."/user/modules/person/model.php";


            $results = $object->read($query,$pagination);



            self::ProcessPopulate($results, $object->PDOInstance,(!empty($_GET["populate"]))?$_GET["populate"]:[]);



            $data = ["results"=>$results,"pagination"=>$pagination];
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