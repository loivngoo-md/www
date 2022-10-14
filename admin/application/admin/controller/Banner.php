<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Banner extends Base
{
    public function index()
    {
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
        $list = $this->BannerModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function doupload(){
        $this->OssModel->doupload("lottery/banner/");
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
   
            } else {
                if(!empty($id) || $id == 0){
                   $info = $this->BannerModel->getOneData($id);
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
        $this->BannerModel->saveData($post);
    }
    public function doEditHot(){
        $post = $this->request->param();
        $this->BannerModel->editStatus($post);
    }    
    public function doEditStatus(){
        $post = $this->request->param();
        $this->BannerModel->editStatus($post);
    }  
    
    
     
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->BannerModel->delData($post['id']);
    }
    
}
