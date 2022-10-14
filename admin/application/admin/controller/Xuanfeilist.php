<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Xuanfeilist extends Base
{
    /*选妃列表*/
    public function xuanfeilist()
    {
        return $this->fetch();
    }
    public function xuanfeilistdata()
    {
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['xuanfei_name'] = $this->request->get('xuanfei_name') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->XuanfeilistModel->getxuanfeilist($get);
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation != "add"){
                if(!empty($id) || $id == 0){
                    $info = $this->XuanfeilistModel->getOneData($id);
                    $this->assign('info',$info);
                }else{
                    $this->error('编辑错误ID不能为空！');
                }
            }
            $class = $this->XuanfeilistModel->selectList();
            $this->assign('class',$class);
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }
    public function doupload(){
        $this->OssModel->upload_img("xuanfei","xuanfei");
    }
     public function doSave(){
        $post = $this->request->param();
        if (empty($post['pc_src'])){
            $this->error('图片不能为空！');
        }
         $this->XuanfeilistModel->saveData($post);
    }
    public function doDel(){
        $post = $this->request->param();
        $this->XuanfeilistModel->delData($post['id']);
    }
}
