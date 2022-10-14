<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Data extends Base
{
    public function register()
    {
       $member = $this->MemberModel->selectList();
       $user = $this->UserModel->selectList(2);
       $this->assign('member',$member);    
       $this->assign('user',$user);    
       return $this->fetch();
    }
    public function register_list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['mid'] = $this->request->get('mid') ?: "";
        $get['uid'] = $this->request->get('uid') ?: "";
        $get['code'] = $this->request->get('code') ?: "";
        $get['status'] = $this->request->get('status') === "" ? "":$this->request->get('status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->Member_registerModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function recharge()
    {
       $member = $this->MemberModel->selectList();
       $user = $this->UserModel->selectList(2);
       $this->assign('member',$member);    
       $this->assign('user',$user);           
       return $this->fetch();
    }    
    public function recharge_list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['type'] = $this->request->get('type') ?: "";
        $get['uid'] = $this->request->get('uid') ?: "";
        $get['mid'] = $this->request->get('mid_name') ?: "";
        $get['username'] = $this->request->get('username') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->RechargeModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }   
    public function withdrawal()
    {
       $member = $this->MemberModel->selectList();
       $user = $this->UserModel->selectList(2);
       $this->assign('member',$member);    
       $this->assign('user',$user);           
       return $this->fetch();
    }    
    public function withdrawal_list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['uid'] = $this->request->get('uid') ?: "";
        $get['mid'] = $this->request->get('mid') ?: "";
        $get['username'] = $this->request->get('username') ?: "";
        $get['bankname'] = $this->request->get('bankname') ?: "";
        $get['status'] = $this->request->get('status') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->WithdrawalModel->getList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }

    // 删除
    public function doDel(){
        $post = $this->request->param();
        $result = $this->WithdrawalModel->where('id','in',$post['id'])->delete();
        if($result)
            return json_table(200, "删除成功！",0,[]);
        return json_table(201, "删除失败请联系管理员！",0,[]);
    }
    
}
