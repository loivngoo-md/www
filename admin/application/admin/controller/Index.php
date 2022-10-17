<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;
use app\admin\controller\Base;

class Index extends Base
{
    public function index()
    {   

        $list =  $this->IndexModel->countUsers();

        $total_users = $list['count'];
        $this->assign('total_users',$total_users);

        return $this->fetch('index', [
            'total_users'  => $total_users
        ]);
    }

}
