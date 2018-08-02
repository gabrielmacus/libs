<?php
include_once "../../autoload.php";
include_once "../../system/libs/simple_html_dom.php";
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 02/08/2018
 * Time: 02:55 PM
 */

if(empty($_GET["league"]))
{
    http_response_code(400);
    exit();
}
$league = $_GET["league"];

$html = file_get_html("http://www.espn.com.ar/futbol/posiciones/_/liga/{$league}");

if(!$html){
    http_response_code(400);
    exit();
}

$table =[];
foreach ($html->find(".standings-row") as $row)
{
    $team["position"] = $row->find(".number",0)->innertext;
    $team["shield"] = 'data:image/png;base64,'.base64_encode(file_get_contents($row->find(".team-logo",0)->src));
    $team["team"] = $row->find(".team-names",0)->innertext;
    $team["played"] = $row->find("td",1)->innertext;
    $team["won"] = $row->find("td",2)->innertext;
    $team["draw"] = $row->find("td",3)->innertext;
    $team["loses"] = $row->find("td",4)->innertext;
    $team["gf"] = $row->find("td",5)->innertext;
    $team["ga"] = $row->find("td",6)->innertext;
    $team["gd"] = $row->find("td",7)->innertext;
    $team["points"] = $row->find("td",8)->innertext;

    $empty=false;
    foreach ($team as $k => $v)
    {
        if(is_null($team[$k]) || $team[$k] === false)
        {
            $empty = true;
        }
    }

    if(!$empty)
    {
        $table[]=$team;
    }

}

$fileDir = \system\libs\Services::JoinPath(CACHE_PATH,"league-positions.{$league}.json");

if(count($table))
{
    file_put_contents($fileDir,json_encode($table));
}

?>
<pre>
    <?php print_r($table); ?>
</pre>

