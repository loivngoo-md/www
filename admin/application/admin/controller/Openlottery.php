<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Openlottery extends Base
{
    public function index()
    {
       $class = $this->LotteryclassModel->selectList();
       $lottery = $this->LotteryModel->selectList();
       $this->assign('lottery',$lottery);   
       $this->assign('class',$class);         
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['key'] = $this->request->get('key') ?: "";
        $get['expect'] = $this->request->get('expect') ?: "";
        
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->OpenlotteryModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }   
    
}
