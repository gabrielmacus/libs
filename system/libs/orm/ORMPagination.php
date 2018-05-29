<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 26/05/2018
 * Time: 15:38
 */

namespace system\libs\orm;


class ORMPagination
{
    public $offset=0;
    public $limit=100;
    public $total;

    public function getPages()
    {
     return floor($this->total / $this->limit);
    }
    public function getPaginator($offset = 2)
    {
        //TODO: add code
    }

}