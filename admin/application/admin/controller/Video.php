<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Video extends Base
{
    public function index()
    {
       return $this->fetch();
    }


    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['vod_name'] = $this->request->get('vod_name') ?: "";
//        $get['cid'] = $this->request->get('cid') ?: "";
//        $get['key'] = $this->request->get('key') ?: "";
        $get['status'] = $this->request->get('vod_status') === "" ? "":$this->request->get('vod_status');
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->VideoModel->getList($get);

        return json_table(0, "",$list['count'],$list['data']);
    }

    public function doEditHot(){
        $post = $this->request->param();
        $this->VideoModel->doEditHot($post);
    }  
    public function play(){
        $url = $this->request->get('url');
        $this->assign('url',$url);
        return $this->fetch();
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation != "add"){
                if(!empty($id) || $id == 0){
                    $info = $this->VideoModel->getOneData($id);
                    $this->assign('info',$info);
                }else{
                    $this->error('编辑错误ID不能为空！');
                }
            }
            $class = $this->VideoclassModel->selectList();
            $this->assign('class',$class);
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }


//保存
    public function doSave(){
        $post = $this->request->param();
        if (empty($post['vod_pic'])){
            $post['vod_pic'] = $post['vod_pic1'];
        }
        unset($post['vod_pic1']);
         $this->VideoModel->saveData($post);
    }

// 删除
    public function doDel(){
        $post = $this->request->param();
        $this->VideoModel->delData($post['id']);
    }

    //上传封面
    public function doupload(){
        $this->OssModel->doupload("video/fmimg/");
    }
}
