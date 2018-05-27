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
include "Company.php";


$pdo = new PDO("mysql:host=localhost;dbname=libs","root","");

$company = new \form\orm\Company($pdo);
$job  = new \form\orm\Job($pdo);
$person  = new \form\orm\Person($pdo);
$pagination = new \form\orm\ORMPagination();

$person->readById(5);
$company->readById(2);
$relation = new \form\orm\ORMRelation($pdo);
$relation->setParent($company);
$relation->setChild($person,"employees");
$relation->save();






/*
$companies = $company->read(null,$pagination);
$companies->populate($person,"employees")->populate($job);

echo json_encode($companies);
*/