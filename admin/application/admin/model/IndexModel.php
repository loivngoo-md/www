<?php

namespace app\admin\model;

use think\Db;
use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;


class IndexModel extends Model
{
    protected $table = 'fa_user';

    protected static function init()
    {
    }

    public function countUsers()
    {
        $count =  $this->count();
        return [
            'count'=>$count
        ];
    }
}
?>