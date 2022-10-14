<?php
namespace app\admin\controller;
use app\admin\model\LotteryModel;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Buylottery extends Base
{
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['lid'] = $this->request->get('cid') ?: '';
        $get['username'] = $this->request->get('username') ?: '';
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->GameModel->getList($get);
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function index()
    {
        $typeList = LotteryModel::select();
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }
    public function editBuyLottery(){
        $post = $this->request->param();
        $get['id'] = $this->request->get('id');
        $this->GameModel->editBuyLottery($post,$get['id']);
    }
  
}
