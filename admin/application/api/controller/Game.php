<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Game extends Base
{
    public function placeOrder(){
        $post = $this->request->param();
        $this->GameModel->placeOrder($post);
    }
}
