<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/04/2018
 * Time: 2:27
 */

trait Magic
{
    public function __get($name) {

        return $this->$name;
    }

    public function __set($name, $value) {


        $this->$name = $value;
    }
}