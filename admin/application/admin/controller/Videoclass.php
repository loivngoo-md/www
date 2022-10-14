<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Videoclass extends Base
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
        $list = $this->VideoclassModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    
    

    //  开启禁用的状态
    public function doEditStatus(){
        $param = $this->request->param();
        $this->VideoclassModel->editStatus($param);
    }

    
    
    
    public function doSave(){
        $post = $this->request->param();
        $this->VideoclassModel->saveData($post);
    }    
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
                
            } else {
                if(!empty($id)){
                   $info = $this->RoleModel->getOneData($id);
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }
    
     
    
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->VideoclassModel->delData($post['id']);
    } 
}
