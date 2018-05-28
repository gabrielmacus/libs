<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/04/2018
 * Time: 23:33
 */
include "autoload.php";

include "Person.php";
include "Team.php";
include "League.php";

$pdo = new PDO("mysql:host=localhost;dbname=libs","root","");

$concat = $pdo->query("SELECT CONCAT('truncate table ',table_schema,'.',table_name,';') 
  FROM information_schema.tables 
 WHERE table_schema IN ('libs');")->fetchAll(PDO::FETCH_ASSOC);

foreach ($concat as $k=>$v)
{

    $pdo->exec(reset($v));
}


$leagues = [];

$league = new League($pdo);
$league->name = "Premier League";
$leagues[$league->name] = $league->save();

$league = new League($pdo);
$league->name = "La Liga";
$leagues[$league->name] = $league->save();


$teams = [];

$team = new Team($pdo);
$team->name ="Liverpool F.C";
$teams["Premier League"][$team->name]=$team->save();

$team = new Team($pdo);
$team->name ="Chelsea F.C";
$teams["Premier League"][$team->name]=$team->save();

$team = new Team($pdo);
$team->name ="Wigan Athletic";
$teams["Premier League"][$team->name]=$team->save();


$team = new Team($pdo);
$team->name ="F.C Barcelona";
$teams["La Liga"][$team->name]=$team->save();


$team = new Team($pdo);
$team->name ="Real Madrid F.C";
$teams["La Liga"][$team->name]=$team->save();

$players = [];

$person = new \form\orm\Person($pdo);
$person->name ="Mohamed";
$person->surname ="Salah";
$players["Premier League"]["Liverpool F.C"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Loris";
$person->surname ="Karius";
$players["Premier League"]["Liverpool F.C"][]= $person->save();


$person = new \form\orm\Person($pdo);
$person->name ="Willy";
$person->surname ="Caballero";
$players["Premier League"]["Chelsea F.C"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Eden";
$person->surname ="Hazard";
$players["Premier League"]["Chelsea F.C"][]= $person->save();




$person = new \form\orm\Person($pdo);
$person->name ="Alex";
$person->surname ="Bruce";
$players["Premier League"]["Wigan Athletic"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Nick";
$person->surname ="Powell";
$players["Premier League"]["Wigan Athletic"][]= $person->save();



$person = new \form\orm\Person($pdo);
$person->name ="Lionel";
$person->surname ="Messi";
$players["La Liga"]["F.C Barcelona"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Luis";
$person->surname ="Suárez";
$players["La Liga"]["F.C Barcelona"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Gareth";
$person->surname ="Bale";
$players["La Liga"]["Real Madrid F.C"][]= $person->save();

$person = new \form\orm\Person($pdo);
$person->name ="Sergio";
$person->surname ="Ramos";
$players["La Liga"]["Real Madrid F.C"][]= $person->save();

foreach ($teams as $i => $j)
{
     foreach ($j as $k)
     {
         $relation = new \form\orm\ORMRelation($pdo);
         $league = new League($pdo);
         $league->readById($leagues[$i]);
         $relation->setParent($league);

         $team = new Team($pdo);
         $team->readById($k);
         $relation->setChild($team,"teams");
         $relation->save();
     }
}

foreach ($players as $i => $j)
{
    foreach ($j as $k => $l)
    {
        foreach ($l as $m)
        {


            $relation = new \form\orm\ORMRelation($pdo);
            $team = new Team($pdo);
            $team->readById($teams[$i][$k]);
            $relation->setParent($team);

            $player = new \form\orm\Person($pdo);
            $player->readById($m);
            $relation->setChild($player,"players");
            $relation->save();

        }
    }
}

$league = new League($pdo);
$pagination = new \form\orm\ORMPagination();
$team =  new Team($pdo);
$leagues = $league->read(null,$pagination);
$player = new \form\orm\Person($pdo);
$leagues->populate($team,"teams")->populate($player,"players");


echo json_encode($leagues);