<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 12/05/2018
 * Time: 21:57
 */

namespace form\orm;


class ORMRelation extends ORMObject
{
    protected $table = "_relations";
    protected $prefix ="";

    function __construct(\PDO $PDOInstance )
    {
        parent::__construct($PDOInstance, $this->table, $this->prefix);
    }

}