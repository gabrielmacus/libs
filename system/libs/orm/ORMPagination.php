<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 26/05/2018
 * Time: 15:38
 */

namespace system\libs\orm;


class ORMPagination implements \JsonSerializable
{
    use \Magic;
    protected $offset=0;
    protected $limit=100;
    protected $total;
    protected $pages;

    public function jsonSerialize()
    {
        $json = [];

        foreach ($this as $k=>$v)
        {
            $json[$k] = $v;
        }

        return $json;
    }


    public function __get($name)
    {

        if($name == "total" && isset($this->total) && isset($this->limit))
        {
            $this->pages =  ceil($this->total / $this->limit);
        }

        return $this->$name;
    }


    public function getPaginator($offset = 2)
    {
        //TODO: add code
    }

}