<?php
namespace app\admin\controller;
use think\Request;
use think\Controller;
use app\admin\controller\Base;
class Role extends Base
{
    public function index()
    {
       return $this->fetch();
    }
    public function list(){
        $get['page'] = $this->request->get('page') ?: 1;
        $get['limit'] = $this->request->get('limit') ?: 10;
        $get['name'] = $this->request->get('name') ?: "";
        $get['start_time'] = $this->request->get('start_time') ?: "";
        $get['end_time'] = $this->request->get('end_time') ?: "";
        $list = $this->RoleModel->getRoleList($get);
        
        return json_table(0, "",$list['count'],$list['data']);
    }
    public function roleTree(){
        $json = file_get_contents('../application/admin/data/role.json'); 
        $data = json_decode($json, true);
        json_exit(200,"获取成功！",$data);
    }    
    public function userRoleTree(){
        $post = $this->request->param();
        $role_json = $this->RoleModel->getUserRoleTree($post['id']);
        $role_arr = json_decode($role_json,true);
        $alljson = file_get_contents('../application/admin/data/role.json'); 
        $alljson_arr = json_decode($alljson, true);
        foreach ($alljson_arr as &$v) {
            foreach ($role_arr as $rv){
                if($v['field'] == $rv['field']){
                     foreach ($v['children'] as &$cv){
                         foreach ($rv['children'] as $rcv){
                             if($cv['id'] == $rcv['id']){
                                 $cv['checked'] = true;
                             }
                         }
                     }
                }else{
                     continue;
                }
            }
        }
        // dump($alljson_arr);exit;s
        json_exit(200,'获取成功！',$alljson_arr);
    }
    public function operation($operation = null, $id = null){
        if(!empty($operation)){
            if($operation == "add"){
                
            } else {
                if(!empty($id)){
                   $info = $this->RoleModel->getOneData($id);
                   $this->assign('info',$info);
                }else{
                   $this->error('编辑错误ID不能为空！'); 
                }
            }
            $this->assign('operation',$operation);
            return $this->fetch();
        } else {
            $this->error('操作类型错误！');
        }
    }   
    public function doSave(){
        $post = $this->request->param();
        $this->RoleModel->saveData($post);
    }
    
    
    // 删除
    public function doDel(){
        $post = $this->request->param();
        $this->RoleModel->delData($post['id']);
    }

}
