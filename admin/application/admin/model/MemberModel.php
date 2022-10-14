<?php
namespace app\admin\model;

use think\Db;
use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;
class MemberModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'member';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
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

    /**
     * @param $post
     * 踢下线
     */
    public function editTi($post){
        $info = $this->where(['id'=>$post['id']])->find();
        if($info){
            $data['is_online'] = 0;
            $data['update_time'] = time();
            $this->save($data,['id'=>$info['id']]) ? json_exit(200,'踢下线成功！'):json_exit(200,'踢下线失败！');
        }else{
            json_exit(401,'数据不存在！');
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
    public function addMoney($post){
        $RechargeModel = new RechargeModel;
        $userinfo = $this->where(['id'=>$post['id']])->find();
        $rechargeLog = $RechargeModel->where('mid',$userinfo['id'])->find();
        if ($rechargeLog && (time() - $rechargeLog['update_time'])<30) {
            json_exit(401,'请不要重复点击！');
        }
        $id = cookie('id');
        if ($id == $userinfo['id']) {
            json_exit(401,'请不要重复点击！');
        }
        if(empty($userinfo)){
            json_exit(401,'数据不存在！');
        }else{
            Db::startTrans();
            try {
                $data['money'] = $post['money'];
                $data['desc']  = $post['desc'];
                $data['mid']  = $userinfo['id'];
                $data['type']  = $post['type'];
                $data['uid']  = Session::get('userinfo')['id'];
                $data['create_time'] = time();
                $data['update_time'] = time();
                $res = $RechargeModel->insert($data);
                $res1 = '';
                if ($res){
                    $res1 = $this->save(['money'=>$userinfo['money'] + $data['money']],['id'=>$userinfo['id']]);
                }else{
                    json_exit(401,'修改余额失败！');
                }
                if ($res && $res1){
                    cookie('id',$userinfo['id'],'30');
                    Db::commit();
                    json_exit(200,'变更余额成功！');
                }else{
                    json_exit(401,'变更余额失败！');
                }
            }catch (Exception $e) {
                $this->error($e->getMessage());
                Db::rollback();
            }
        }
    }

    public function addAmountCode($post){
        $userinfo = $this->where(['id'=>$post['id']])->find();
        if(empty($userinfo)){
            json_exit(401,'数据不存在！');
        }else{
            $this->save(['amount_code'=>$post['amount_code']],['id'=>$userinfo['id']]) ? json_exit(200,'变更打码量成功！') : json_exit(401,'变更打码量失败！');
        }
    }

    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'数据不存在！');
            }else{
            $data['uid'] = (int)$post['uid'] ?(int)trim($post['uid']):$info['uid'];
            $data['username'] = (string)$post['username'] ?trim($post['username']):$info['username'];
            $data['name'] = (string)$post['name'] ?trim($post['name']):$info['name'];
            $data['password'] = (string)$post['password'] ? md5(trim($post['password'])):$info['password'];
            $data['paypassword'] = (string)$post['paypassword'] ? md5(trim($post['paypassword'])):$info['paypassword'];
            $data['money'] = $post['money'] ?trim($post['money']):$info['money'];
            $data['num']  =  $post['num'] ?trim($post['num']):$info['num'];
            $data['min']  = $post['min'] ?trim($post['min']):$info['min'];
            $data['max']  = $post['max'] ?trim($post['max']):$info['max'];
            $data['header_img'] = $post['header_img'] ?trim($post['header_img']):$info['header_img'];
            
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['username'=>$post['username']])->find();
            if(!empty($info)){
               json_exit(401,'会员名称重复！');
            }
            $data['uid'] = (int)trim($post['uid']);
            $data['name'] = (string)trim($post['name']);
            $data['username'] = (string)trim($post['username']);
            $data['password'] = (string)md5(trim($post['password']));
            $data['paypassword'] = (string)md5(trim($post['paypassword']));
            $data['money'] = (int)trim($post['money']);
            $data['header_img'] = (string)trim($post['header_img']);
            $data['num']  = (int)trim($post['num'])??3;
            $data['min']  = (float)trim($post['min'])??50;
            $data['max']  = (float)trim($post['max'])??100;
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    public function getList($get){
        $where = [];
        if(!empty($get['username'])){
             $where[] = ['username','=',$get['username']];
            // 特殊处理偶发出现的相同用户名 多条的用户数据
            
            $delete_list = $this->where($where)->select()->toarray();
            
            if(count($delete_list) > 1){
               $new_list =  array_column($delete_list, 'money','id');
               
               $key= iconv('UTF-8', 'GBK', array_search(max($new_list),$new_list));
                unset($new_list[$key]);
              foreach ($new_list as $k=>$v){
                  $this->where('id',$k)->delete();
              }
            }

        }

        if(!empty($get['uid'])){
            $where[] = ['uid','=',$get['uid']];
        }

        if(!empty($get['ip'])){
            $where[] = ['ip','=',$get['ip']];
        }

        if(!empty($get['status']) || $get['status'] === "0"){
           $where[] = ['status','=',$get['status']];
        }   
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $userlist = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $userlist = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }   
        $role = new RoleModel;
        $UserModel = new UserModel;
        foreach ($userlist as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['role'] = $role->where(['id'=>$v['uid']])->value('name') ?:"暂无上级";
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
            if(empty($v['name'])){
                $v['name'] = "暂未设置";
            }
            if(!empty($v['ip'])){
                $v['ip'] = $v['ip'];
            }else{
                $v['ip'] = "暂无IP";
            }     
            if($v['sex'] === 0){
                $v['sex'] = "未知";
            }else if($v['sex'] === 1){
                $v['sex'] = "男";
            }else if($v['sex'] === 2){
                $v['sex'] = "女";
            }
            $v['daili'] = $UserModel->where(['id'=>$v['uid']])->value('username')?:"暂未数据";
            $v['min'] = $this->funGetUserWithdrawRole($v['id'])['min'];
            $v['max'] = $this->funGetUserWithdrawRole($v['id'])['max'];
            $v['num'] = $this->funGetUserWithdrawRole($v['id'])['num'];
        }        
        return [
            'data'=>$userlist,
            'count'=>$count
            ];        
    }
    public function funGetUserWithdrawRole($mid){
        $info = $this->where(['id'=>$mid])->find();
        if($info['num'] && $info['min'] && $info['max'] ){
            $data = [
                'num'=>$info['num'],
                'min'=>$info['min'],
                'max'=>$info['max'],
                ];
        }else{
            $SystemModel = new SystemModel;
            $sys_info = $SystemModel->getConfig("base");
            $data = [
                'num'=>$sys_info['withraw_num'],
                'min'=>$sys_info['withraw_min'],
                'max'=>$sys_info['withraw_max'],
                ];
        }
        return $data;
    }
    public function selectList(){
        $selectList = $this->where(['status'=>1])->order('id','asc')->select();
        return $selectList;
    }   
    
    
            
    // 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
      
       
       $list = $this->where(['uid'=>$id])->find();
       if(!empty($list)){
           json_exit(401,'会员存在下级，不能删除！');
       }
        
        
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
           return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
    
    
    
}