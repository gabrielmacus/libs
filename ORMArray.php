<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/04/2018
 * Time: 2:01
 */

namespace form\orm;

class ORMArray extends \ArrayObject implements \JsonSerializable
{
    use ORMPopulate
    {
        populate as _populate;
    }

    public function offsetSet($index, $newval)
    {
        if(!is_a($newval,ORMObject::class))
        {
            throw new \InvalidArgumentException("Not an ".ORMObject::class);

        }

        parent::offsetSet($index, $newval);
    }

    public function jsonSerialize()
    {
       return $this->getArrayCopy();
    }


     function &populate(ORMObject $object, string $path = "",ORMPagination $pagination =null,$type = PARENT_RELATION_COMPONENT)
    {
       return $this->_populate($this,$object,$path,$pagination,$type);
    }


}