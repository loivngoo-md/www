<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\UserModel;
class RoleModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'role';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getOneData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info;
        }
    }    
    public function getUserRoleTree($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info['role'];
        } 
    }
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'管理组不存在！');
            }else{
                $data['name'] = $post['name'] ?(string)trim($post['name']):$info['name'];
                $data['role'] = json_encode($post['role']);
                $data['update_time'] = time();
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $data['name'] = (string)trim($post['name']);
            $data['role'] = json_encode($post['role']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    public function selectList(){
        $selectList = $this->order('id','asc')->select();
        return $selectList;
    }    
    public function getRoleList($get){
        $where = [];
        if(!empty($get['name'])){
            $where[] = ['name','like',"%".$get['name']."%"];
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $userlist = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }else {
           $count = $this->where($where)->count();
           $userlist = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }   
        $user  = new UserModel;
        foreach ($userlist as $k=>&$v){
            $v['count'] = $user->where(['rid'=>$v['id']])->count();
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
        }
        return [
            'data'=>$userlist,
            'count'=>$count
            ];        
    }
    
    // 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            
             $list = \app\admin\model\UserModel::where(['rid'=>$id])->find();
            if(!empty($list)){
                json_exit(401,'管理员组有已存在的管理员！');
            }
            
            
            
            return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
}