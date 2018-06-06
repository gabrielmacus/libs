<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 26/05/2018
 * Time: 14:58
 */

namespace system\libs\orm;


class ORMQuery
{
    use \Magic;

    public $fieldsPrefix;
    public $fields;
    public $params;
    public $where;
    public $join;
    public $orderBy;
    public $groupBy;


    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }




}