<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
class BanksModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'bank_bind';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function selectList(){
        $selectList = $this->where(['status'=>1])->order('id','asc')->select();
        return $selectList;
    }
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'数据不存在！');
            }else{
                MemberModel::where(['id'=>$post['uid']])->update(['name'=>$post['name']]);
                $data['bankid'] = $post['bankid'] ?$post['bankid']:$info['bankid'];
                $data['bankinfo'] = $post['bankinfo'] ?$post['bankinfo']:$info['bankinfo'];
                $data['update_time'] = time();
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['uid'=>$post['uid']])->find();
            if($info){
               json_exit(401,'用户银行卡已经绑定！'); 
            }
            $data['uid'] = (int)trim($post['uid']);

            $data['bankid'] = (string)trim($post['bankid']);
            $data['bankinfo'] = (string)trim($post['bankinfo']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $data['name'] = (string)trim($post['name']);
            MemberModel::where(['id'=>$data['uid']])->update(['name'=>$data['name']]);
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
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
    public function getList($get){
        $where = [];
        if(!empty($get['username'])){
            $member_model = new MemberModel();
            $member_info = $member_model->where(['username'=>$get['username']])->find();
            // var_dump($member_info['id']);exit;
            
            
            if(!empty($member_info)){
                $where[] = ['uid','=',$member_info['id']];
            }
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }   
        $MemberModel = new MemberModel;
        foreach ($list as $k=>&$v){
            $user_info = Session::get('userinfo');
            if($user_info['player'] == 2){
                unset($list[$k]);
            }
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $v['username'] = $MemberModel->where(['id'=>$v['uid']])->value('username');
            $v['name'] = $MemberModel->where(['id'=>$v['uid']])->value('name');
        }
        return [
            'data'=>$list,
            'count'=>$count
        ];        
    }
    
  // 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
   
}