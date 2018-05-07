<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/04/2018
 * Time: 21:37
 */

include "../autoload.php";

include "../Person.php";
include "../Job.php";
include "../Company.php";

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


class ORMTest extends PHPUnit_Framework_TestCase
{
    /***
     * @var $pdo PDO
     */
    protected $pdo;

    public function setUp()
    {

        $this->pdo = new PDO("mysql:host=localhost;dbname=orm","root","");

        $this->pdo->exec("TRUNCATE _relations");
        $this->pdo->exec("TRUNCATE job");
        $this->pdo->exec("TRUNCATE company");
        $this->pdo->exec("TRUNCATE person");

    }

    public function testCreate()
    {

        $person = new \form\orm\Person($this->pdo);

        $person->name="Gabriel";

        $person->surname ="Macus";

        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,6,16,1996));

        $person->birthdate =   $date->format(DATE_ATOM);

        $this->assertEquals(($person->save() > 0),true);

        $person->name="Roberto";

        $person->surname ="Rodriguez";

        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,5,22,1975));

        $person->birthdate =   $date->format(DATE_ATOM);


        $this->assertEquals(($person->save() > 0),true);

        $person->name="Ginko";

        $person->surname ="Biloba";

        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,12,1,1986));

        $person->birthdate =   $date->format(DATE_ATOM);

        $this->assertEquals(($person->save() > 0),true);

        $total = $this->pdo->query("SELECT count(*) as 'total' FROM {$person->table}")->fetchAll(PDO::FETCH_ASSOC)[0]["total"];

        $this->assertEquals($total,3);

    }


    public function testRelate()
    {

        $company = new \form\orm\Company($this->pdo);
        $company->name ="Google";
        $company->location = "Palo Alto, CA";
        $company->save();

        $job = new \form\orm\Job($this->pdo);
        $job->name  ="PHP semi-senior programmer";
        $job->description = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
        $job->save();

        $job = new \form\orm\Job($this->pdo);
        $job->name  ="NodeJS junior programmer";
        $job->description = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
        $job->save();

        $job = new \form\orm\Job($this->pdo);
        $job->name  ="JAVA senior programmer";
        $job->description = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
        $job->save();

        $job = new \form\orm\Job($this->pdo);
        $job->name  ="Python junior tester";
        $job->description = "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.";
        $job->save();

        $person = new \form\orm\Person($this->pdo);

        $person->name="Gabriel";
        $person->surname ="Macus";
        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,6,16,1996));
        $person->save();

        $person->birthdate =   $date->format(DATE_ATOM);
        $person->name="Roberto";
        $person->surname ="Rodriguez";
        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,5,22,1975));
        $person->save();

        $person->birthdate =   $date->format(DATE_ATOM);
        $person->name="Ginko";
        $person->surname ="Biloba";
        $date = new DateTime();
        $date->setTimestamp(mktime(0,0,0,12,1,1986));
        $person->birthdate =   $date->format(DATE_ATOM);
        $person->save();


        //Relating...
        $company = new \form\orm\Company($this->pdo);
        $company->readById(1);

        for($i=1;$i<=4;$i++)
        {
            $job = new \form\orm\Job($this->pdo);
            $job->readById(1);
            $company->relate($job);
        }

        $c = $company->read();
        $c->populate(new \form\orm\Job($this->pdo));
        echo json_encode($c->results);





        $job = new \form\orm\Job($this->pdo);
        $job->readById(1);
        $person = new \form\orm\Job($this->pdo);
        $person->readById(1);
        //$job->relate($person,["ref1path"=>"jobs"]);







    }


}
