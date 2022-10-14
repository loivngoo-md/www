<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class User extends Base
{
    public function index()
    {
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['username'] = $this->request->get('username') ?: "";
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $get['player'] = 1;
        $list = $this->UserModel->getUserList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    
    
        public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation != "add"){
                if(!empty($id) || $id == 0){
                    $info = $this->UserModel->getOneData($id);
                    $this->assign('info',$info);
                }else{
                    $this->error('编辑错误ID不能为空！');
                }
            }
            $class = $this->RoleModel->selectList();
            $this->assign('class',$class);
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }
    
    
    
//保存
    public function doSave(){
        $post = $this->request->param();
        $this->UserModel->saveUserData($post);
    }
    
    
    
//  开启禁用管理员的状态
    public function doEditStatus(){
        $param = $this->request->param();
        $this->UserModel->editStatus($param);
    }
    
        
    
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->UserModel->delData($post['id']);
    }

}
