<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Xuanfei extends Base
{
    public function address(){
        $this->XuanfeiaddressModel->getaddressList();
    }
    public function xuanfeilist()
    {
        $get = $this->request->param();
        $this->XuanfeiModel->getList($get);    
    }
    public function xuanfeidata()
    {
        $get = $this->request->param();
        $this->XuanfeiModel->xuanfeidata($get);
    }
}
