<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 10/07/2018
 * Time: 0:05
 */

namespace app\modules\home;

use app\modules\news\News;
use system\libs\orm\ORMObject;
use system\libs\orm\ORMPagination;
use system\libs\orm\ORMQuery;
use system\libs\Services;
use system\modules\crud\CrudController;

require_once ROOT_PATH."/system/modules/crud/controller.php";


class HomeController extends CrudController
{
    static function CrudAuthentication()
    {
        $ca =  parent::CrudAuthentication();
        $ca["Read"] = false;
        return $ca;
    }


    protected static function BeforeUpdate(ORMObject &$object, &$params = null, &$template = null)
    {
        if($object->selected == 1)
        {
            /**
             * @var $pdo \PDO
             */
            $pdo =  $object->PDOInstance;
            $oSql ="UPDATE {$object->table} SET {$object->prefix}_selected = 0";
            $pdo->exec($oSql);
        }

    }

    protected static function BeforeCreate(ORMObject &$object, &$params = null, &$template = null)
    {
        static::BeforeUpdate($object,$params,$template);
    }

    static function Read(ORMObject $object, $params, $template = null)
    {
        if($template)
        {

            include Services::JoinPath(ROOT_PATH,"app/modules/news/model.php");
            $news = new News($object->PDOInstance);
            $pagination = new ORMPagination();
            $query = new ORMQuery();
            $posts = $news->read($query,$pagination);

            /*
            $_GET["filter"]["selected"] = 1;
            $_GET["populate"][0]["post"]["path"]="mainBlock";
            $_GET["populate"][0]["file"]["path"]="images";*/

        }

        parent::SendResponse(["posts"=>$posts],$template,$params);
        //parent::Read($object, $params, $template);
    }


}