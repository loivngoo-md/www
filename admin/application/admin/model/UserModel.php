<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;
use app\api\model\Member_registerModel;

class UserModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'数据不存在！');
            }else{
                $data['password'] = md5($post['password'] ? (string)trim($post['password']):$info['password']);
                $code_info =  $this->where(['code'=>$post['code']])->find();
                if($code_info && $code_info['code'] != $info['code']){
                    json_exit(401,'邀请码重复！');
                }
                $data['rid'] = $post['rid'] ?(int)trim($post['rid']):$info['rid'];
                $data['code'] = $post['code'] ?(int)trim($post['code']):$info['code'];
                $data['update_time'] = time();
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['username'=>$post['username']])->find();
            $info_code = $this->where(['code'=>$post['code']])->find();
            if(!empty($info)){
               json_exit(401,'用户名重复！'); 
            }
            if(!empty($info_code)){
               json_exit(401,'邀请码重复！'); 
            }
            $data['username'] = (string)trim($post['username']);
            $data['password'] = md5((string)trim($post['password']));
            $data['code'] = (int)trim($post['code']);
            $data['status'] = 1;
            $data['player'] = (int)trim($post['player']);
            $data['rid'] = (int)trim($post['rid']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    
    
    
    // 添加或编辑管理员账号
    
        public function saveUserData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'数据不存在！');
            }else{
                $data['password'] = md5($post['password'] ? (string)trim($post['password']):$info['password']);
                $info_code = $this->where(['phone'=>$post['phone']])->find();
                if($info_code && $info_code['phone'] != $info['phone']){
                    json_exit(401,'手机号重复！');
                }
            $data['username'] = $post['username'] ?(string)trim($post['username']):$info['username'];
            $data['password'] = $post['password'] ?md5((string)trim($post['password'])):$info['password'];
            $data['phone']    = $post['phone'] ?(string)trim($post['phone']):$info['phone'];
            $data['status']   = isset($post['status']) ?(int)trim($post['status']):1;
            $data['player']   = isset($post['player']) ?(int)trim($post['player']):1;
            $data['rid']      = $post['rid'] ?(int)trim($post['rid']):$info['rid'];
            $data['update_time'] = time();
                
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['username'=>$post['username']])->find();
            $info_code = $this->where(['phone'=>$post['phone']])->find();
            if(!empty($info)){
               json_exit(401,'用户名重复！'); 
            }
            if(!empty($info_code)){
               json_exit(401,'手机号重复！'); 
            }
            $data['username'] = (string)trim($post['username']);
            $data['password'] = md5((string)trim($post['password']));
            $data['phone'] = (string)trim($post['phone']);
            $data['status'] = 1;
            $data['player'] = 1;
            $data['rid'] = (int)trim($post['rid']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    
    
    
    
    public function getUserList($get){
        $where = [];
        if(!empty($get['username'])){
            $where[] = ['username','like',"%".$get['username']."%"];
        }
        if(!empty($get['player'])){
            $where[] = ['player','=',$get['player']];
        }
        if(!empty($get['status']) || $get['status'] === "0"){
           $where[] = ['status','=',$get['status']];
        }        
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $userlist = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }else {
           $count = $this->where($where)->count();
           $userlist = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }   
        $role = new RoleModel;
        $MemberModel = new MemberModel;
        foreach ($userlist as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['role'] = $role->where(['id'=>$v['rid']])->value('name') ?:"暂无数据";
            if(!empty($v['ip'])){
                $res = https_get('http://ip-api.com/json/'.$v['ip'],['lang'=>"zh-CN"]);
                if($res['status'] === "success"){
                    $v['area'] = $res['country']." ".$res['regionName']." ".$res['city'];
                }else{
                   $v['area'] = "暂未登录"; 
                }
            }else{
                $v['area'] = "暂无地区";
            }
            if(!empty($v['last_time'])){
                $v['last_time'] = date("Y-m-d H:i",$v['last_time']);
            }else{
                $v['last_time'] = "暂未登录";
            }
            if(!empty($v['ip'])){
                $v['ip'] = $v['ip'];
            }else{
                $v['ip'] = "暂无IP";
            }     
            if($v['player'] == 1){
                
            }else if($v['player'] == 2){
                 $v['membercount'] = $MemberModel->where(['uid'=>$v['id']])->count();
            }
        }        
        return [
            'data'=>$userlist,
            'count'=>$count
            ];        
    }
    public function editStatus($post){
        $info = $this->where(['id'=>$post['id']])->find();
        if($info){
             $data['status'] = $post['status'];
             $data['update_time'] = time();
             $this->save($data,['id'=>$info['id']]) ? json_exit(200,'修改成功！'):json_exit(200,'修改失败！');
        }else{
             json_exit(401,'数据不存在！');
        }
    }
    // 管理员用户登录
    public function UserLogin($post)
    {
        $userinfo = $this->where(["username"=>$post["username"]])->find();
        $password = md5($post['password']);
        if (empty($userinfo)) {
            // $this->UserLoginAndRegister();
            json_exit(401, "管理员用户不存在！");
        } else {
            if($password != $userinfo['password']){
              json_exit(401, "管理员用户密码错误！"); 
            }
            if($userinfo['status']!=1){
                json_exit(401, "管理员用户被禁用！"); 
            }
            $data['last_time'] = time();
            $data['ip'] = getIP();
            $this->save($data,['id'=>$userinfo['id']]);
            Session::set('userinfo', $userinfo);
            json_exit(200, "登录成功！");
        }
    }
    public function getOneData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info;
        }
    }  
    public function selectList($player){
        $selectList = $this->where(['player'=>$player])->order('id','asc')->select();
        return $selectList;
    }
    //用于初始化新建超管用户
    public function UserLoginAndRegister()
    {
       $userinfo = $this->where(["username"=>"admin"])->find();
       if(empty($userinfo)){
           $data['username']="admin";
           $data['password']= md5("123456");
           $data['status'] = 1;
           $data['player'] = 1;
           $data['create_time']= time();
           $data['update_time']= time();
           $this->save($data) ? json_exit(200,"初始化管理员成功【账号】admin 【密码】123456") : json_exit(401,"初始化管理员失败！");
       } else {
           // code...
       }
    } 
    
    
        
    // 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if($id == 1){
            json_exit(401,'此用户禁止删除！');
        }
       
        if($info['player'] == 2){
           $list = MemberModel::where(['uid'=>$id])->find();
           if(!empty($list)){
               json_exit(401,'代理存在下级，不能删除！');
           }
        }
        
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
           return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
}