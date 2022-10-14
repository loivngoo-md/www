<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Notice extends Base
{
    public function index()
    {
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['name'] = $this->request->get('name') ?: "";
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->NoticeModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
   
            } else {
                if(!empty($id) || $id == 0){
                   $info = $this->NoticeModel->getOneData($id);
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            // $class = $this->MemberModel->selectList();
            // $this->assign('class',$class);              
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }
    public function doSave(){
        $post = $this->request->param();
        $this->NoticeModel->saveData($post);
    }
    public function doEditHot(){
        $post = $this->request->param();
        $this->NoticeModel->doEditHot($post);
    }    
    public function doEditStatus(){
        $post = $this->request->param();
        $this->NoticeModel->editStatus($post);
    }
    
    
     
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->NoticeModel->delData($post['id']);
    }

}
