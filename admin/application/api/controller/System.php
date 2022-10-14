<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class System extends Base
{
    public function base(){
        $data = $this->SystemModel->getConfig("base");
        return json_exit_Base64(200,"获取成功",$data);
    }
    public function getBankList(){
        $json = file_get_contents('../application/admin/data/bank.json'); 
        $data = json_decode($json, true);
        json_exit_Base64(200,"获取成功！",$data);
    }
    public function config()
    {
        
        $data=[
            'notice'=>$this->NoticeModel->where(['hot'=>1,'status'=>1])->value("text")?:"蜜獾娱乐",
            'banners'=>$this->BannerModel->where(['status'=>1])->select(),

            'movielist_0'=> $this->VideoModel->getHotList(),
            'movielist_1'=>$this->VideoModel->getRandHotList(8)
            
         ];
        return json_exit_Base64(200,"获取成功",$data);
        
    }

}
