<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Login extends Base
{
    public function index()
    {
       return $this->fetch();
    }
    public function doLogin(){
        $post = $this->request->param();
        $this->UserModel->UserLogin($post);
    }
}
