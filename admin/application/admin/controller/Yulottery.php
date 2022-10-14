<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Yulottery extends Base
{
    public function index()
    {
       $lottery = $this->LotteryModel->selectList();
       $this->assign('lottery',$lottery);       
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['key'] = $this->request->get('key') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->YulotteryModel->getList($get);
        $start = ($get['page']-1)*$get['limit'];
        $newList = array_slice($list['data'],$start,$get['limit']);
        return json_table(0, "",count($list['data']),$newList);
    }   
    
    public function doSave(){
        $post = $this->request->param();
        if (empty($post['opencode'])){
            $post['opencode'] = $post['opencode1'].','.$post['opencode2'].','.$post['opencode3'];
            unset($post['opencode1']);
            unset($post['opencode2']);
            unset($post['opencode3']);
        }
        $this->YulotteryModel->saveYuKaiJiang($post);
    }
    public function doAllSave(){
        $post = $this->request->param();
        $this->YulotteryModel->saveAllYuKaiJiang($post);
    }
    
    public function doCancel(){
        $post = $this->request->param();
        $this->YulotteryModel->cancel($post);
    }

    public function edit_lottery(){
        $data = $this->request->param() ?: "";
        if (empty($data['name']) || empty($data['key']) || empty($data['opencode']) || empty($data['expect']) || empty($data['create_time'])){
            $this->error('参数不全！');
        }else{
            $opencode = $data['opencode'];
            $opencode = explode(',',$opencode);
            $this->assign('info',$data);
            $this->assign('opencode',$opencode);
            return $this->fetch();
        }
    }
}
