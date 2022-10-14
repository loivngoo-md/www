<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Lottery extends Base
{
    public function index()
    {
       $class = $this->LotteryclassModel->selectList();
       $this->assign('class',$class);         
       return $this->fetch();
    }
    public function peilvlist(){
        $get['id'] = $this->request->get('id');
        $data = $this->LotteryModel->peilvJsonToDataBase($get['id']);
        return json_table(0, "",0,$data);
    }
    // 开启禁用的状态
    public function doEditStatus(){
        $param = $this->request->param();
        $this->LotteryModel->editStatus($param);
    }
    public function peilv()
    {
       $get['id'] = $this->request->get('id');
       $this->assign('id',$get['id']);         
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['name'] = $this->request->get('name') ?: "";
        $get['cid'] = $this->request->get('cid') ?: "";
        $get['key'] = $this->request->get('key') ?: "";
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->LotteryModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }   
    public function doSave(){
        $post = $this->request->param();
        $this->LotteryModel->saveData($post);
    }    
    public function doupload(){
        $this->OssModel->doupload("lottery/ico/");
    }
    public function editPeilv(){
        $post = $this->request->param();
        $get['id'] = $this->request->get('id');
        $this->LotteryModel->editPeilv($post,$get['id']);
    }
    public function doEditPeilvState(){
        $post = $this->request->param();
        $get['id'] = $this->request->get('id');
        $this->LotteryModel->doEditPeilvState($post,$get['id']);
    }    
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
   
            } else {
                if(!empty($id) || $id == 0){
                   $info = $this->LotteryModel->getOneData($id);
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            $class = $this->LotteryclassModel->selectList();
            $this->assign('class',$class);              
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }
    public function doDel(){
        $post = $this->request->param();
        $this->LotteryModel->delData($post['id']);
    }    
}
