<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Xuanfei extends Base
{
    /*选妃地区*/
    public function xuanfeiaddress()
    {
        return $this->fetch();
    }
    public function addresslist()
    {
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['name'] = $this->request->get('name') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->XuanfeiAddressModel->getAddressList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function addressSave()
    {
        $post = $this->request->param();
        $this->XuanfeiAddressModel->saveData($post);
    }
    public function addressDel()
    {
        $post = $this->request->param();
        $this->XuanfeiAddressModel->delData($post['id']);
    }
     public function addressoperation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
                
            } else {
                if(!empty($id)){
                   $info = $this->XuanfeiAddressModel->getOneData($id);
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
}
