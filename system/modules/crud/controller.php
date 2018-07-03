<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 29/05/2018
 * Time: 10:14 AM
 */

namespace system\modules\crud;

use Rakit\Validation\ErrorBag;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use system\libs\Auth;
use system\libs\orm\ORMArray;
use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\orm\ORMRelation;
use system\libs\Services;
use user\modules\person\Person;

class CrudController
{
    use Auth;

    static $paginationLimit = 100;


    protected static function Validate(ORMObject $object, $rules  = null,$messages=[],$aliases = [])
    {

        if(!empty($rules))
        {
            if(is_array($rules))
            {

                $validator =  new Validator();
                $validation = $validator->make((array)$object,$rules,$messages);
                //$validation = $validator->validate((array)$object,$rules,$messages);
                $validation->setAliases($aliases);
                $validation->validate();

            }
            else if(is_a($rules,Validator::class))
            {

                $validation = $rules->validate();
            }

            /**
             * @var $validation Validation
             */
            if($validation->fails())
            {

                http_response_code(400);
                return $validation->errors();
            }



        }

        return true;


    }

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

    static function ProcessQuery($params,ORMObject $object)
    {

        $query = new ORMQuery();
        $query->orderBy = (empty($params["sort"]))?"-created_at":$params["sort"];
        $query->fields = (!empty($params["fields"]))?$params["fields"]:null;
        $query->groupBy = (!empty($params["group"]))?$params["group"]:null;

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
        if(!empty($params["filter"]) && is_array($params["filter"]))
        {
            //TODO: refactor this mess :c
            foreach ($params["filter"] as $key => $param)
            {
                if(!empty($param) && !is_array($param))
                {
                    $key ="{$object->prefix}_$key";
                    if(empty($query->where))
                    {
                        $query->where =" ";
                    }
                    else{
                        $query->where.=" AND ";
                    }


                    $query->where.=" {$key} LIKE ? ";


                    if(empty($query->params))
                    {
                        $query->params=[];
                    }

                    //Correspondences
                    $param = str_replace("~","%",$param);

                    //Put values to search inside wildcards ('%{value}%')
                    if(!empty($params["filter_any"]))
                    {
                        $param = str_replace('%',"",$param);
                        $param = "%{$param}%";
                    }

                    $query->params[] = $param;
                }
                elseif(is_array($param))
                {

                    if(!empty($param["not"]) && is_array($param["not"]))
                    {
                        $not = [];

                        if(empty($query->where))
                        {
                            $query->where =" ";
                        }
                        else{
                            $query->where.=" AND ";
                        }


                        $query->where.= "{$object->prefix}_{$key} NOT IN (".implode(",",array_map(function($el){return "'".$el."'";},$param["not"])).")";


                    }
                }

            }
        }

        return $query;
    }


    /**
     * Process params (generally from query string) to achieve array population
     *
     * @param ORMArray $results
     * @param \PDO $pdo
     * @param $params
     */
    static function ProcessPopulate(ORMArray &$results,\PDO $pdo,$params){

        if(is_array($params)  && count($params) > 0)
        {


            foreach ($params as $k=>$arr)
            {
                $relatedArr = null;
                foreach ($arr as $module => $data)
                {

                    $type = (!empty( $data["type"]))? $data["type"]:"child";
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


                    if($Model)
                    {
                        $obj = new $Model($pdo);

                        if(empty($relatedArr) || !is_a($relatedArr,ORMArray::class))
                        {

                            $relatedArr = $results->populate($obj,$path,null,$type);
                        }
                        else{

                            $relatedArr = $relatedArr->populate($obj,$path,null,$type);
                        }


                    }

                }

            }

        }


    }

    static function SaveRelations(ORMObject &$object,$post = null)
    {
        //TODO: Optimize
        $post = empty($post)?$_POST:$post;

        $relationsSaved = [];

        if(!empty($post["_deletedRelations"]) && is_array($post["_deletedRelations"]))
        {
            foreach ($post["_deletedRelations"] as $deleted)
            {

                if(!empty($deleted["_relationData"]["id"]) && is_numeric($deleted["_relationData"]["id"]))
                {
                    $relation = new ORMRelation($object->PDOInstance);
                    $relation->readById($deleted["_relationData"]["id"]);

                    $relation->delete();
                }

            }
        }

        if(!empty($post["_related"]) && is_array($post["_related"]))
        {
            foreach ($post["_related"] as $key => $relatedArray)
            {
                foreach ($relatedArray as $position =>$relatedObject)
                {


                    if(!empty($relatedObject["_relationData"]["module"]) && !empty($relatedObject["id"]) && is_numeric($relatedObject["id"]) &&   $RelatedObjectClass = Services::LoadClass($relatedObject["_relationData"]["module"],CLASS_TYPE_MODEL))
                    {

                        $relation = new ORMRelation($object->PDOInstance);
                        $relationType = 'child';


                        if(!empty($relatedObject["_relationData"]))
                        {
                            if(!empty($relatedObject["_relationData"]["id"]) && is_numeric($relatedObject["_relationData"]["id"]))
                            {
                                $relation->id = $relatedObject["_relationData"]["id"];

                            }

                            foreach ($relatedObject["_relationData"] as $k=>$v)
                            {
                                //Sets extra data
                                if(strpos($k,"extra_") !== false)
                                {

                                    $relation->$k = $v;
                                }

                            }

                            if(!empty($relatedObject["_relationData"]['type']) && $relatedObject["_relationData"]['type'] == 'parent')
                            {
                                $relationType = 'parent';
                            }
                        }

                        $r = new $RelatedObjectClass($object->PDOInstance);
                        $r["id"] = $relatedObject["id"];

                    if($key == $relatedObject["_relationData"]["module"])
                    {
                        $key ="";
                    }

                    switch ($relationType)
                    {
                        case 'parent':

                            $childKey = (!empty($relatedObject["_relationData"]["childKey"]))?$relatedObject["_relationData"]["childKey"]:"";

                            $relation->setChild($object,$childKey);
                            $relation->setParent($r,$key,$position);

                            break;

                        case 'child':

                            $parentKey = (!empty($relatedObject["_relationData"]["parentKey"]))?$relatedObject["_relationData"]["parentKey"]:"";

                            $relation->setParent($object,$parentKey);
                            $relation->setChild($r,$key,$position);


                            break;
                    }

                        $relationsSaved[]=$relation->save();


                    }

                }
            }
        }

        return $relationsSaved;

    }

    protected static function BeforeCreate(ORMObject &$object,&$params=null,&$template = null)
    {
    }

    protected static function AfterCreate(ORMObject &$object,&$params=null,&$template = null)
    {

    }

    protected static function BeforeUpdate(ORMObject &$object,&$params=null,&$template = null)
    {

    }

    protected static function AfterUpdate(ORMObject &$object,&$params=null,&$template = null)
    {

    }

    protected static function BeforeDelete(ORMObject &$object,&$params=null,&$template = null)
    {

    }
    protected static function AfterDelete(ORMObject &$object,&$params=null,&$template = null)
    {

    }

    protected static function AfterRead(&$results,ORMObject &$object,&$params=null,&$template = null)
    {

    }

    static function Create(ORMObject $object,$params=null,$template = null)
    {

        static::checkAuthorization(true);

        static::AssignProperties($object);

        if(($validationResult = static::Validate($object)) === true)
        {
            static::BeforeCreate($object,$params,$template);

            $id = $object->save();

            $object->readById($id);

            static::SaveRelations($object);


            static::AfterCreate($object,$params,$template);


        }
        else
        {
            /**
             * @var $validationResult ErrorBag
             */
            $object = $validationResult->toArray();
        }



        static::SendResponse($object,$template);

    }

    static function Read(ORMObject $object,$params,$template = null)
    {

        static::checkAuthorization(true);

        $query =static::ProcessQuery($_GET,$object);

        $pagination = new ORMPagination();
        $pagination->offset = (!empty($_GET["p"]) && is_numeric($_GET["p"]) && $_GET["p"]>0)?$_GET["p"]-1:0;
        $pagination->limit = static::$paginationLimit;

        if(empty($params["id"]))
        {

            $results = $object->read($query,$pagination);
            static::ProcessPopulate($results, $object->PDOInstance,(!empty($_GET["populate"]))?$_GET["populate"]:[]);



            $data = ["results"=>$results,"pagination"=>$pagination];
        }
        else
        {
            $object->readById($params["id"]);

            $results = new ORMArray([$object]);

            static::ProcessPopulate($results, $object->PDOInstance,(!empty($_GET["populate"]))?$_GET["populate"]:[]);

            $data = (!empty($object->id))?$object:[];
        }

        static::AfterRead($results,$object,$params,$template);

        static::SendResponse($data,$template);

    }



    static function Update(ORMObject $object,$params,$template = null)
    {

        static::checkAuthorization(true);

        $object->readById((!empty($params["id"]))?$params["id"]:null);

        //$object->id = (!empty($params["id"]))?$params["id"]:null;

        static::AssignProperties($object);

        static::Validate($object);

        static::BeforeUpdate($object,$params,$template);

        $object->save();

        $object->readById($object->id);

        static::SaveRelations($object);



        static::AfterUpdate($object,$params,$template);

        static::SendResponse($object,$template);
    }

    static function Delete(ORMObject $object,$params,$template=null)
    {
        static::checkAuthorization(true);

        $object->readById((!empty($params["id"]))?$params["id"]:null);

       // $object->id = (!empty($params["id"]))?$params["id"]:null;

        static::BeforeDelete($object,$params,$template);

        $result = $object->delete();

        static::AfterDelete($object,$params,$template);

        static::SendResponse($result,$template);
    }


    protected static function AssignProperties(ORMObject &$object,$post = null)
    {
        $post = empty($post)?$_POST:$post;

        foreach ($post as $key => $value)
        {

            $object[$key] = $value;
        }
    }


}