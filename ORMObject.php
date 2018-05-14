<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/04/2018
 * Time: 23:34
 */

namespace form\orm;


define("ORM_RELATE_CHILD",1);
define("ORM_RELATE_PARENT",2);

abstract class ORMObject implements \JsonSerializable
{
    use \Magic;
    public $id;

    protected $table;
    protected $prefix;
    protected $PDOInstance;
    protected $results;

    /**
     * ORMObject constructor.
     * @param \PDO $PDOInstance For executing database queries
     * @param string $table Optional. Table name that corresponds to the object, class name used if empty
     * @param string $prefix Optional. Prefix to be added to table columns
     */
    function __construct(\PDO $PDOInstance,string $table = "",string $prefix ="")
    {

        $class= new \ReflectionClass($this);
        $this->PDOInstance = $PDOInstance;
        $this->table = (!empty($table))?$table:strtolower($class->getShortName());
        $this->prefix  =  (!empty($prefix))?$prefix:$this->table;

    }

    protected function getObjectVars()
    {
        //TODO: Review for better alternative. This may be deprecated
        //Only public properties. The "trick" is that get_object_vars is being called from the scope of call_user_func and not the scope of the object
        $objectVars = call_user_func('get_object_vars', $this);
        foreach ($objectVars as $key =>$var)
        {
            if(is_numeric($key))
            {
                unset($objectVars[$key]);
            }
        }
        return $objectVars;


    }

    function makeObject(array $arr,ORMObject &$ormObject = null)
    {
        $className = get_class($this);

        $ormObject =empty($ormObject)?new $className($this->PDOInstance):$ormObject;

        foreach ($arr as $key => $value)
        {
            $key = str_replace($this->prefix."_","",$key);
            $ormObject->$key = $value;
            //TODO: identify json and convert to array
        }

        return $ormObject;

    }

    private function processPropertiesToSave()
    {
        $columns =[];
        $values = [];

        $objectVars = $this->getObjectVars();

        foreach ($objectVars as $key => $value)
        {

            if(!is_array($value) && !is_object($value))
            {
                $columns[] = "{$this->prefix}_{$key}";
                $values[] = "{$value}";
                //$columns.= "{$this->prefix}_{$key},";
                //$values.= "'{$value}',";
            }
        }

        //$columns = rtrim($columns,",");
        //$values  = rtrim($values,",");

        return ["columns"=>$columns,"values"=>$values];
    }

    private function create()
    {
        $properties = $this->processPropertiesToSave();

        $oSql = "INSERT INTO {$this->table} (".implode(",",$properties["columns"]).") 
                  VALUES (".rtrim(str_repeat('?,', count($properties["columns"])),",").")";

        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute($properties["values"]))
        {
            throw new ORMException("Couldn't create object. Error info: ".implode($statement->errorInfo(),","));
        }

        return  $this->PDOInstance->lastInsertId();
    }

    function readById(int $id)
    {
        $oSql ="SELECT * FROM {$this->table} WHERE {$this->prefix}_id = ?";

        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute([$id]))
        {
            throw new ORMException("Couldn't fetch object by id. Error info: ".implode($statement->errorInfo(),","));
        }

        if($row = $statement->fetch())
        {
            $this->makeObject($row,$this);
            return true;
        }

        return false;
    }

    function &read(array $query = ["where"=>"","params"=>[]],$limit = 100,$offset =0,array $fields = null)
    {

        $this->results =  new ORMArray();

        $oSql = "SELECT ".((empty($fields))?"*":implode($fields,","))." FROM {$this->table} ";

        if(!empty($query["where"]))
        {
            $oSql.=" WHERE {$query["where"]} ";
        }


        //TODO: paginate
        $oSql.= " LIMIT {$limit} OFFSET {$offset}";


        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute($query["params"]))
        {
            throw new ORMException("Couldn't fetch objects. Error info: ".implode($statement->errorInfo(),","));
        }


        while ($row = $statement->fetch())
        {
            $this->results[]=$this->makeObject($row);
        }


        return $this->results;//["objects"=>$results,"limit"=>$limit,"offset"=>$offset];


    }

    private function update()
    {

        $properties = $this->processPropertiesToSave();

        $oSql = "UPDATE {$this->table}  SET  ";
        $set = "";
        foreach ($properties["columns"] as $k => $column)
        {
            $set.=" {$column} = ? ,";
        }
        $set = rtrim($set,",");

        $oSql.=" {$set} WHERE {$this->prefix}_id = '{$this->id}'";


        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute($properties["values"]))
        {
            throw new ORMException("Couldn't update object. Error info: ".implode($statement->errorInfo(),","));
        }

    }


    function unrelate(int $id)
    {
        $oSql = "DELETE FROM _relations WHERE relation_id = {$id}";
        return $this->PDOInstance->exec($oSql);
    }

    /*
    function relate(ORMObject $object,string $path = null,int $relationId= null,int $pos = 0,array $extraData = [],int $relationType = ORM_RELATE_CHILD)
    {

        $fields = ["relation_id","ref1","ref1table","ref2","ref2table","ref1pos","ref2pos","ref1path","ref2path"];

        $extraFields = ["field_1","field_2"];

        $fields =  $fields + $extraFields;

        $oSql = "REPLACE INTO _relations   (".implode(",",$fields).")
         VALUES (".implode(",",array_map(function($el){return ":{$el}";},$fields)).")";

       foreach ($extraFields as $field)
       {
           $params[":{$field}"] = (!empty($extraData[$field]))?$extraData[$field]:"";
       }

        $params[":relation_id"] = (!empty($relationId))?$relationId:"";

        if($relationType == ORM_RELATE_CHILD)
        {
            $params[":ref1"] = $this->id;
            $params[":ref1table"] = $this->table;
            $params[":ref1path"] = "";
            $params[":ref1pos"] = "";


            $params[":ref2"] = $object->id;
            $params[":ref2table"]  =$object->table;
            $params[":ref2pos"] =  $pos;
            $params[":ref2path"] = (!empty($path))?$path:"";


        }
        else if($relationType ==ORM_RELATE_PARENT)
        {
            $params[":ref2"] = $this->id;
            $params[":ref2table"] = $this->table;
            $params[":ref2path"] = "";
            $params[":ref2pos"] =  $pos;

            $params[":ref1"] = $object->id;
            $params[":ref1table"]  =$object->table;
            $params[":ref1pos"] =  $pos;
            $params[":ref1path"] = (!empty($path))?$path:"";
        }
        else
        {
            throw new ORMException("Wrong relation type option");
        }

        echo $oSql;
        echo json_encode($params);

        $statement=$this->PDOInstance->prepare($oSql);

        if(!$statement->execute($params))
        {
            throw new ORMException("Couldn't relate objects. Error info: ".implode($statement->errorInfo(),","));

        }

        return $this->PDOInstance->lastInsertId();




    }
    */


    function save()
    {

        if(empty($this->id))
        {
            return $this->create();
        }
        else
        {
            return $this->update();
        }


    }



    function delete():bool
    {

    }

    public function jsonSerialize()
    {
        $json = [];

        $objectVars = $this->getObjectVars();


        foreach ($objectVars as $key=>$value)
        {
            $json[$key] = $value;

        }

        return $json;

    }


}