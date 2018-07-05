<?php
/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 28/05/2018
 * Time: 21:58
 */

namespace app\modules\news;

use system\modules\post\Post;

require_once ROOT_PATH."/system/modules/post/model.php";

class News extends Post
{
    protected $table = 'post';
    protected $prefix = 'post';

}