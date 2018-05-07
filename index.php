<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/04/2018
 * Time: 23:33
 */
include "autoload.php";

include "Person.php";

include "Job.php";

$pdo = new PDO("mysql:host=localhost;dbname=orm","root","");


$person  = new \form\orm\Person($pdo);

$person->readById(12);
