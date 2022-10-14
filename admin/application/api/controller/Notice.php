<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Notice extends Base
{
    public function getNoticeList(){
        $post = $this->request->param();
        $this->NoticeModel->getNoticeList($post);
    }
}
