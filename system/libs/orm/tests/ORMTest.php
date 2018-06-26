<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/04/2018
 * Time: 21:37
 */

include "../autoload.php";

include "../Person.php";
include "../League.php";
include "../Team.php";


class ORMTest extends PHPUnit_Framework_TestCase
{
    /***
     * @var $pdo PDO
     */
    protected $pdo;

    public function setUp()
    {


        $this->pdo = new PDO("mysql:host=localhost;dbname=libs","root","");

        $pdo = $this->pdo;

        $concat = $pdo->query("SELECT CONCAT('truncate table ',table_schema,'.',table_name,';') 
  FROM information_schema.tables 
 WHERE table_schema IN ('libs');")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($concat as $k=>$v)
        {

            $pdo->exec(reset($v));
        }


    }

    public function testCreate()
    {

        $person = new \system\libs\orm\Person($this->pdo);
        $birthdate = new DateTime();
        $birthdate->setDate(1987,3,3);
        $person->name ="John";
        $person->surname="Smith";
        $this->assertEquals(1,$person->save());


        $person = new \system\libs\orm\Person($this->pdo);
        $birthdate = new DateTime();
        $birthdate->setDate(1987,3,3);
        $person->name ="John";
        $person->surname="Smith";
        $this->assertEquals(2,$person->save());

        $pagination = new \system\libs\orm\ORMPagination();
        $persons = $person->read(null,$pagination);

        $this->assertCount(2,$persons);




    }
    public function testDelete()
    {

        $person = new \system\libs\orm\Person($this->pdo);
        $birthdate = new DateTime();
        $birthdate->setDate(1987,3,3);
        $person->name ="John";
        $person->surname="Smith";
        $id = $person->save();

        $person = new \system\libs\orm\Person($this->pdo);
        $person->readById($id);
        $this->assertEquals(1, $person->delete());

    }
    public function testUpdate()
    {

        try
        {
            $person = new \system\libs\orm\Person($this->pdo);
            $birthdate = new DateTime();
            $birthdate->setDate(1987,3,3);
            $person->name ="John";
            $person->surname="Smith";
            $id = $person->save();

            $person = new \system\libs\orm\Person($this->pdo);
            $person->readById($id);
            $person->name ="Juan";
            $person->save();

            $person = new \system\libs\orm\Person($this->pdo);
            $person->readById($id);
            $this->assertEquals("Juan",$person->name);
            $this->assertEquals("Smith",$person->surname);
            $this->assertEquals($id,$person->id);
        }
        catch(Exception $e)
        {


            $this->fail($e);


        }




    }
    public function testRelate()
    {


        $pdo =$this->pdo;


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

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Mohamed";
        $person->surname ="Salah";
        $players["Premier League"]["Liverpool F.C"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Loris";
        $person->surname ="Karius";
        $players["Premier League"]["Liverpool F.C"][]= $person->save();


        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Willy";
        $person->surname ="Caballero";
        $players["Premier League"]["Chelsea F.C"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Eden";
        $person->surname ="Hazard";
        $players["Premier League"]["Chelsea F.C"][]= $person->save();




        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Alex";
        $person->surname ="Bruce";
        $players["Premier League"]["Wigan Athletic"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Nick";
        $person->surname ="Powell";
        $players["Premier League"]["Wigan Athletic"][]= $person->save();



        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Lionel";
        $person->surname ="Messi";
        $players["La Liga"]["F.C Barcelona"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Luis";
        $person->surname ="Suárez";
        $players["La Liga"]["F.C Barcelona"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Gareth";
        $person->surname ="Bale";
        $players["La Liga"]["Real Madrid F.C"][]= $person->save();

        $person = new \system\libs\orm\Person($pdo);
        $person->name ="Sergio";
        $person->surname ="Ramos";
        $players["La Liga"]["Real Madrid F.C"][]= $person->save();

        foreach ($teams as $i => $j)
        {
            foreach ($j as $k)
            {
                $relation = new \system\libs\orm\ORMRelation($pdo);
                $league = new League($pdo);
                $league->readById($leagues[$i]);
                $relation->setParent($league,'leagues');

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

                    $relation = new \system\libs\orm\ORMRelation($pdo);
                    $team = new Team($pdo);
                    $team->readById($teams[$i][$k]);
                    $relation->setParent($team);

                    $player = new \system\libs\orm\Person($pdo);
                    $player->readById($m);
                    $relation->setChild($player,"players");
                    $relation->save();



                }
            }
        }

        $league = new League($pdo);
        $pagination = new \system\libs\orm\ORMPagination();
        $team =  new Team($pdo);
        $leagues = $league->read(null,$pagination);
        $player = new \system\libs\orm\Person($pdo);

        //Before populate

        $this->assertArrayNotHasKey('teams', $leagues[0]);

        $this->assertArrayNotHasKey('teams', $leagues[1]);

        $leagues->populate($team,"teams")->populate($player,"players");

        //After populate

        $this->assertCount(3, $leagues[0]->_related["teams"]);

        $this->assertCount(2, $leagues[1]->_related["teams"]);



        $this->assertCount(2, $leagues[0]->_related["teams"][0]->_related["players"]);


        $this->assertCount(2, $leagues[0]->_related["teams"][1]->_related["players"]);

        $this->assertCount(2, $leagues[0]->_related["teams"][2]->_related["players"]);


        $this->assertCount(2, $leagues[1]->_related["teams"][0]->_related["players"]);

        $this->assertCount(2, $leagues[1]->_related["teams"][1]->_related["players"]);


        //Population in child

        $player = new \system\libs\orm\Person($this->pdo);
        $pagination = new \system\libs\orm\ORMPagination();
        $players = $player->read(null,$pagination);
        $team = new Team($this->pdo);

        $players->populate($team,"teams");
        $this->assertAttributeEmpty("_related",$players[0]);
        $players->populate($team,"",null,PARENT_RELATION_COMPONENT);

        $this->assertAttributeNotEmpty('_related', $players[0]);
        $this->assertArrayHasKey('team', $players[0]->_related);

        $team = new Team($this->pdo);
        $pagination = new \system\libs\orm\ORMPagination();
        $teams = $team->read(null,$pagination);
        $this->assertAttributeEmpty('_related', $teams[0]);


        $league = new League($this->pdo);
        $teams->populate($league,"leagues");
        $this->assertAttributeEmpty('_related', $teams[0]);
        $teams->populate($league,"leagues",null,PARENT_RELATION_COMPONENT);
        $this->assertAttributeNotEmpty('_related', $teams[0]);
        $this->assertCount(1, $teams[0]->_related["leagues"]);
        $this->assertCount(1, $teams[1]->_related["leagues"]);





    }



}
