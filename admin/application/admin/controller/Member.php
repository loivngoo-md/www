<?php
namespace app\admin\controller;
use app\admin\model\MemberModel;
use app\admin\model\UserModel;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Member extends Base
{
    public function index()
    {

        $uids = MemberModel::field(['uid'])->group('uid')->select()->toArray();
        $uis = array_column($uids,'uid');
       $userList = UserModel::field(['id','username'])->whereIn('id',$uis)->select();
        $this->assign('userlist',$userList);
       return $this->fetch();
    }
    
    
    
//保存
    public function doSave(){
        $post = $this->request->param();
        $this->MemberModel->saveData($post);
    }
    
    
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['uid'] = $this->request->get('uid') ?: '';
        $get['ip'] = $this->request->get('ip') ?: '';
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['username'] = $this->request->get('username') ?: "";
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->MemberModel->getList($get);

        return json_table(0, "",$list['count'],$list['data']);
    }
    public function doEditStatus(){
        $post = $this->request->param();
        $this->MemberModel->editStatus($post);
    }
    public function doupload(){
        $this->OssModel->doupload("user/headimg/");
    }    
    
    public function doAddMoney(){
        $post = $this->request->param();
        $this->MemberModel->addMoney($post);
    }       
    public function addmoney(){
        $id = $this->request->get('id') ?: "";
        $userinfo = $this->MemberModel->where(['id'=>$id])->find();
        if(empty($userinfo)){
            $this->error('用户信息不存在！');
        }else{
            $this->assign('info',$userinfo);
            return $this->fetch();
        }
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
   
            } else {
                if(!empty($id) || $id == 0){
                   $info = $this->MemberModel->getOneData($id);
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            $class = $this->UserModel->selectList(2);
            $this->assign('class',$class);           
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    } 
    
    
        
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->MemberModel->delData($post['id']);
    }

    public function addAmountCode(){
        $id = $this->request->get('id') ?: "";
        $userinfo = $this->MemberModel->where(['id'=>$id])->find();
        if(empty($userinfo)){
            $this->error('用户信息不存在！');
        }else{
            $this->assign('info',$userinfo);
            return $this->fetch();
        }
    }
    public function doAddAmountCode(){
        $post = $this->request->param();
        $this->MemberModel->addAmountCode($post);
    }

    // 删除
    public function doTi(){
        $post = $this->request->param();
        $this->MemberModel->editTi($post);
    }
}
