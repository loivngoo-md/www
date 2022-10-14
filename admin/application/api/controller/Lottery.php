<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Lottery extends Base
{
    public function list()
    {
        $data=[
            ['id'=>1,'class'=>1,'cover'=>"https://m3u8.baidu-cn.live/20210607/Ofdek4uq/1.jpg",'title'=>'测试标题1'],
            ['id'=>2,'class'=>1,'cover'=>"https://m3u8.baidu-cn.live/20210607/Ofdek4uq/1.jpg",'title'=>'测试标题2'],
            ['id'=>3,'class'=>1,'cover'=>"https://m3u8.baidu-cn.live/20210607/Ofdek4uq/1.jpg",'title'=>'测试标题2'],
            ];
        json_exit_Base64(200,"获取成功",$data);
        
    }
    public function getLotteryPeilv(){
        $post = $this->request->param();
        $this->LotteryModel->getLotteryPeilv($post);
    }
    public function getLotteryOneList(){
        $post = $this->request->param();
        $this->LotteryModel->getLotteryOneList($post);
    }
    public function getLotteryInfo(){
        $post = $this->request->param();
        $this->LotteryModel->getLotteryInfo($post);
    }
    public function hotLottery(){
        $data = $this->LotteryModel->hotLottery();
        json_exit_Base64(200,"获取成功",$data);
    }
    public function lotteryList(){
        $get = $this->request->param();
        $classInfo = $this->classToID(isset($get['class'])? $get['class'] : 0);
        $data = $this->LotteryModel->lotteryList($classInfo);
        json_exit_Base64(200,"获取成功",$data);
    }    
    public function classToID($class){
        if($class != 0){
            $data = $this->LotteryclassModel->getItemList();
            return $data[$class-1];
        }else {
            return "all";
        }
    }
    public function itemlist(){
        $data = $this->LotteryclassModel->getItemList();
        json_exit_Base64(200,"获取成功",$data);
    }
}
