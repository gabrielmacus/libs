<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 12/05/2018
 * Time: 21:57
 */

namespace system\libs\orm;



class ORMRelation extends ORMObject implements \ArrayAccess
{
    protected $table = "_relations";
    protected $prefix ="relation";

    public $parent_id;
    public $child_id;
    public $parent_table;
    public $child_table;
    public $parent_key;
    public $child_key;
    public $parent_position;
    public $child_position;
    public $extra_1;
    public $extra_2;
    public $extra_3;

    function __construct(\PDO $PDOInstance )
    {
        parent::__construct($PDOInstance, $this->table, $this->prefix);
    }


    public function setParent(ORMObject $parent,string $key = "",int $position = 0)
    {   $this->log("Setting parent");
        $this->setComponent($parent,PARENT_RELATION_COMPONENT,$key,$position);
    }
    public function setChild(ORMObject $child,string $key = "",int $position = 0)
    {
        $this->log("Setting child");
        $this->setComponent($child,CHILD_RELATION_COMPONENT,$key,$position);
    }


    protected function setComponent(ORMObject $component,int $type,string $key = "",int $position = 0)
    {
        if(empty($component->id)) {
            throw new ORMException("Relation component should exist in database");
        }

        $component_type = ($type == CHILD_RELATION_COMPONENT)?"child":"parent";

        $this[$component_type."_id"] = $component->id;
        $this[$component_type."_table"] = $component->table;
        $this[$component_type."_key"] = $key;
        $this[$component_type."_position"] = $position;

    }



    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        return isset($this->$offset) ? $this->$offset : null;
    }

    public function offsetSet($offset, $value)
    {

        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}