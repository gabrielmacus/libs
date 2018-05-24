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


$pdo = new PDO("mysql:host=localhost;dbname=libs","root","");

$job = new \form\orm\Job($pdo);

$person = new \form\orm\Person($pdo);
$persons = $person->read();
$persons->populate($job);





exit();
