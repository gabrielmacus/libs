<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/04/2018
 * Time: 23:34
 */

namespace system\libs\orm;



//TODO: create database schema based on object public properties

use system\libs\orm\ORMArray;

abstract class ORMObject implements \JsonSerializable, \ArrayAccess
{
    use ORMPopulate
    {
        populate as _populate;
    }
    use \Magic;
    public $id;
    public $created_at;
    public $updated_at;

    protected $table;
    protected $prefix;
    protected $PDOInstance;
    protected $results;
    static $logData;

    /**
     * Indicates if the schema should remain static or the database should be updated on class properties changes
     * @var bool
     */
    static $freezed = false;

    /**
     * Maps class schema in database
     */
    protected function mapSchema()
    {
        $r = new \ReflectionClass(get_class($this));

        $schemaMap = [];

        $properties = $r->getProperties();

        foreach ($properties as $property)
        {
            $comment = $r->getProperty($property)->getDocComment();

            $pattern = "#(@[a-zA-Z]+\s*[a-zA-Z0-9, ()_].*)#";

            preg_match_all($pattern,$comment,$matches,PREG_PATTERN_ORDER);

            $commentArray = [];

            foreach ($matches[0] as $k=>$match)
            {
                $explode =  explode(" ",$match);
                $commentArray[$explode[0]] = $explode[1];
            }

            $schemaMap[$property] = $commentArray["@var"];
        }

    }

    /**
     * ORMObject constructor.
     * @param \PDO $PDOInstance For executing database queries
     * @param string $table Optional. Table name that corresponds to the object, class name used if empty
     * @param string $prefix Optional. Prefix to be added to table columns
     */
    function __construct(\PDO $PDOInstance,string $table = "",string $prefix ="")
    {
        $this->log("Initializing ".get_class($this));

        $class= new \ReflectionClass($this);
        $this->PDOInstance = $PDOInstance;
        $this->table = (!empty($table))?$table:strtolower($class->getShortName());
        $this->prefix  =  (!empty($prefix))?$prefix:$this->table;

    }

    protected function getObjectVars()
    {
        $this->log("Getting object vars");

        $reflect = new \ReflectionClass($this);

        $propsPublic = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        $propsProtected = $reflect->getProperties(\ReflectionProperty::IS_PROTECTED);
        $propsPrivate = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
        $propsStatic = $reflect->getProperties(\ReflectionProperty::IS_STATIC);

        $propsPublic = array_diff($propsPublic,$propsProtected);
        $propsPublic = array_diff($propsPublic,$propsPrivate);
        $propsPublic = array_diff($propsPublic,$propsStatic);

        $objectVars = [];

        foreach ($propsPublic as $prop)
        {
            if(isset($this[$prop->name]))
            {
                $objectVars[$prop->name] = $this[$prop->name];
            }
        }

        $this->log("End getting object vars",$objectVars);
        return $objectVars;


    }

    function log($key,$value=""){
        $now = new \DateTime();
        static::$logData[$key." - ". $now->format("d-m-Y H:i:s")] = $value;

    }

    function makeObject(array $arr,ORMObject &$ormObject = null)
    {


        $this->log("Making object from array",$arr);

        $className = get_class($this);

        $ormObject =empty($ormObject)?new $className($this->PDOInstance):$ormObject;

        foreach ($arr as $key => $value)
        {
            $key = str_replace($this->prefix."_","",$key);
            $ormObject->$key = $value;
            //TODO: identify json and convert to array
        }

        $this->log("End making object from array",$ormObject);

        return $ormObject;

    }

    private function processPropertiesToSave()
    {

        $this->log("Processing properties to save");


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

        $result =["columns"=>$columns,"values"=>$values];

        $this->log("End processing properties to save",$result);

        return $result;
    }

    private function create()
    {

        $this->log("Creating record");
        $properties = $this->processPropertiesToSave();

        $oSql = "INSERT INTO {$this->table} (".implode(",",$properties["columns"]).") 
                  VALUES (".rtrim(str_repeat('?,', count($properties["columns"])),",").")";

        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute($properties["values"]))
        {
            throw new ORMException("Couldn't create object. Error info: ".implode($statement->errorInfo(),","));
        }

        $lastInsertId = $this->PDOInstance->lastInsertId();

        $this->log("End creating record",$lastInsertId);

        return  $lastInsertId;
    }

    function readById(int $id)
    {

        $this->log("Reading record by id",$id);

        $oSql ="SELECT * FROM {$this->table} WHERE {$this->prefix}_id = ?";

        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute([$id]))
        {
            throw new ORMException("Couldn't fetch object by id. Error info: ".implode($statement->errorInfo(),","));
        }


        if($row = $statement->fetch())
        {
            $this->makeObject($row,$this);
            $this->log("End reading record by id",$this);
            return true;
        }
        $this->log("End reading record by id");
        return false;
    }

    function &read(ORMQuery $query= null,ORMPagination &$pagination ,array $fields = null)
    {
        $this->log("Reading records");

        $this->results =  new ORMArray();

        $params =[];

        $query->fields = (empty($query->fields))?"*":implode(",",array_map(function($el){ return $this->prefix."_".$el;},$query->fields));

        $oSql = "SELECT {$query->fields} FROM {$this->table} ";

        if(!empty($query))
        {
            if(!empty($query->join))
            {
                $oSql.=" {$query->join} ";
                $this->log("Appending JOIN to query",$query->join);
            }

            if(!empty($query->where))
            {
                $oSql.=" WHERE {$query->where} ";
                $this->log("Appending WHERE to query",$query->where);
            }

            if(!empty($query->groupBy))
            {
                $oSql.=" GROUP BY {$query->groupBy} ";
                $this->log("Appending GROUP BY to query",$query->groupBy);
            }

            if(!empty($query->orderBy))
            {
                $oSql.=" ORDER BY {$query->orderBy} ";
                $this->log("Appending ORDER BY to query",$query->orderBy);
            }


            if(!empty($query->params))
            {
                $params = $query->params;
                $this->log("Appending PARAMS to query",$query->params);
            }

        }
        $this->log("Counting records for pagination");


        $pagination->total = $this->PDOInstance->query("SELECT count(*) as 'total' FROM ({$oSql}) as t")->fetchAll(\PDO::FETCH_ASSOC)[0]['total'];

        $this->log("End counting records for pagination",$pagination->total);

        $oSql.= " LIMIT {$pagination->limit} OFFSET ".($pagination->offset * $pagination->limit);

        $this->log("End counting records for pagination",$pagination->total);

        $statement = $this->PDOInstance->prepare($oSql);

        $this->log("Executing query",$oSql);

        if(!$statement->execute($params))
        {
            throw new ORMException("Couldn't fetch objects. Error info: ".implode($statement->errorInfo(),","));
        }


        while ($row = $statement->fetch())
        {
            $this->results[]=$this->makeObject($row);
        }

        $this->log("End reading records",$this->results);

        return $this->results;//["objects"=>$results,"limit"=>$limit,"offset"=>$offset];


    }


    private function update()
    {
        $this->log("Updating record");

        $properties = $this->processPropertiesToSave();

        $oSql = "UPDATE {$this->table}  SET  ";
        $set = "";
        foreach ($properties["columns"] as $k => $column)
        {
            $set.=" {$column} = ? ,";
        }
        $set = rtrim($set,",");

        $oSql.=" {$set} WHERE {$this->prefix}_id = '{$this->id}'";

        $this->log("UPDATE sql",$oSql);

        $statement = $this->PDOInstance->prepare($oSql);

        if(!$statement->execute($properties["values"]))
        {
            throw new ORMException("Couldn't update object. Error info: ".implode($statement->errorInfo(),","));
        }
        $this->log("End updating record");
    }

    function save()
    {
        $now = new \DateTime();
        $now = $now->format('Y-m-d H:i:s');
        if(empty($this->id))
        {

            $this->created_at = $now;
            $id = $this->create();
            $this->id = $id;

            return $id;
        }
        else
        {
            $this->updated_at = $now;
            return $this->update();
        }


    }

    function delete():int
    {
        $this->log("Deleting record");

        if(empty($this->id))
        {
            throw new ORMException("Element intended to be deleted should exist");
        }

        $oSql ="DELETE FROM {$this->table} WHERE {$this->prefix}_id = {$this->id} LIMIT 1";

        $this->log("DELETE query",$oSql);

        $result = $this->PDOInstance->exec($oSql);

        if($result === false)
        {
             throw new ORMException("Couldn't delete object. Error info: ".implode($this->PDOInstance->errorInfo(),","));
        }

        $this->log("End deleting record",$result);



        return $result;

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


    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return (isset($this->$offset))?$this->$offset:null;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset =$value;
    }

    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }
}