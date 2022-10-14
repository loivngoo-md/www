<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class System extends Base
{
    public function index()
    {
       $data = $this->SystemModel->getConfig("base");
       
       $this->assign('base',$data);
       return $this->fetch();
    }
    public function doupload(){
        $this->OssModel->doupload("base/ico/");
    }

    public function doSave(){
        $post = $this->request->param();
        $this->SystemModel->saveData($post,"base");
    }

}
