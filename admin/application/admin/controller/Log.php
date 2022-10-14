<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Log extends Base
{
    public function index()
    {
       return $this->fetch();
    }

    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['username'] = $this->request->get('username') ?: "";
//        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
//        $get['player'] = 1;
        $list = $this->LogModel->getList($get);

        $this->LogModel->saveLogData($get);

        return json_table(0, "",$list['count'],$list['data']);
    }

}
