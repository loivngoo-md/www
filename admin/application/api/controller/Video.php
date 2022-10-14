<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
use think\Session;
class Video extends Base
{
    public function getVideoList(){
      $post = $this->request->param();
      $this->VideoModel->getVideoList($post);    
    }
    public function getVideoInfo(){
        $seen = Session('seen');
        $post = $this->request->param();
        $post['seen'] = $seen;
        $this->VideoModel->getVideoInfo($post);        
    }     
    public function itemlist(){
        $data = $this->VideoclassModel->getItemList();
        json_exit_Base64(200,"获取成功",$data);
    }
    public function getVideoInfoItem(){
        $data = $this->VideoModel->getRandHotList(8);
        json_exit_Base64(200,"获取成功",$data);
    }
}
