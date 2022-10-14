<?php
namespace app\admin\controller;
use app\admin\model\MemberModel;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Banks extends Base
{
    public function index()
    {
       return $this->fetch();
    }
    public function list(){
        
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['username'] = $this->request->get('username') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->BanksModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }    
    public function doSave(){
        $post = $this->request->param();
        $this->BanksModel->saveData($post);
    }    
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){

            } else {
                if(!empty($id) || $id == 0){
                   $info = $this->BanksModel->getOneData($id);
                   $userinfo = MemberModel::find($info['uid']);
                   $info['username'] = $userinfo['username'];
                   $info['name'] = $userinfo['name'];
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            $bankinfo = $this->MemberModel->selectList();
            $this->assign('bankinfo',$bankinfo);              
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    } 
    
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->BanksModel->delData($post['id']);
    }
    
}
