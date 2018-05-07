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


    function &populate(ORMObject $object,string $path = null)
    {

        $ids = array_map(function($el){return $el->id;},$this->getArrayCopy());

        if(empty($path))
        {
            $ref2Sql ="SELECT * FROM _relations WHERE ref1table='{$object->table}' AND ref2path IS NULL AND ref2 IN (".implode(",",$ids).")";
            $ref1Sql ="SELECT * FROM _relations WHERE ref2table='{$object->table}' AND ref1path IS NULL AND ref1 IN (".implode(",",$ids).")";
            $path  = $object->table;
        }
        else{
            $ref2Sql ="SELECT * FROM _relations WHERE ref1table='{$object->table}' AND ref2path = '{$path}' AND ref2 IN (".implode(",",$ids).")";
            $ref1Sql ="SELECT * FROM _relations WHERE ref2table='{$object->table}' AND ref1path  = '{$path}' AND ref1 IN (".implode(",",$ids).")";
        }


        $oSql = "{$ref1Sql} UNION {$ref2Sql}";


        $statement = $object->PDOInstance->prepare($oSql);

        if(!$statement->execute())
        {
            throw new ORMException("Couldn't get relations. Error info: ".implode($statement->errorInfo(),","));
        }

        $relatedIds = [];
        $relatedMap = [];

        while ($row = $statement->fetch(\PDO::FETCH_ASSOC))
        {

            /*
            echo "<br>";
            print_r($row);
            echo "<br>";
            print_r($this->getArrayCopy());
            echo "<br>";
            */

            if(is_numeric(($parentKey =  array_search($row["ref1"], array_column($this->getArrayCopy(), 'id')))))
            {
                $relatedIds[]=$row["ref2"];
                $relatedMap[$row["ref2"]][] = $parentKey;
            }
            elseif ( is_numeric(($parentKey =  array_search($row["ref2"], array_column($this->getArrayCopy(), 'id')))))
            {
                $relatedIds[]=$row["ref1"];
                $relatedMap[$row["ref1"]][] = $parentKey;
            }


            /*
            echo "<br>";
            print_r($relatedIds);
            echo "<br>";
            print_r($relatedMap);
            echo "<br>";
            var_dump($parentKey);
            echo "<br>";
            */

        }
        $relatedItems = [];
        if(count($relatedIds))
        {

            $relatedItems = $object->read(["where"=>"{$object->prefix}_id IN (".implode(",",$relatedIds).")","params"=>[]]);


            foreach ($relatedItems as $relatedItem)
            {
                $parentKeys = $relatedMap[$relatedItem->id];
                foreach ($parentKeys as $parentKey)
                {
                    if(empty($this[$parentKey]->$path))
                    {
                        $this[$parentKey]->$path = [];
                    }

                    $this[$parentKey]->$path[] = $relatedItem;
                }
            }

            return  $relatedItems;

        }

        return $relatedItems;

    }

}