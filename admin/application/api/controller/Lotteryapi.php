<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Lotteryapi extends Base
{
    public function kj(){
        $this->LotterykjModel->kj();
    }
    public function settle(){
        $this->GameModel->settle();
    }

    public function updateKj(){
        $this->GameModel->updateKj();
    }

//    针对意外情况导致的没有开奖也没有人投注的期号

    public function updateNewKj(){
        $this->GameModel->updateNewKj();
    }
    public function autoProgram(){
        $this->kj();
       // $this->updateKj();
        $this->settle();
    }
}
