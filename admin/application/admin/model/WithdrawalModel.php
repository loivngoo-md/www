<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
class WithdrawalModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'withdraw';
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
    public function acceptWithdrawal($post,$id){
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if($info['status'] == 1){
                $data['status'] = $post['status'];
                $data['desc'] = $post['desc'];
                $data['uid'] = Session::get('userinfo')['id'];
                $data['update_time'] = time();
                if($data['status'] == 3){
                    $MemberModel = new MemberModel;
                    $memberinfo = $MemberModel->where(['id'=>$info['mid']])->find();
                    if($MemberModel->save(['money'=>$memberinfo['money']+$info['money']],['id'=>$memberinfo['id']])){
                        return $this->save($data,['id'=>$info['id']]) ? json_exit(200,'审核通过成功！') : json_exit(401,'审核通过失败！'); 
                    }else{
                        json_exit(401,'回款失败！'); 
                    }
                }else{
                   return $this->save($data,['id'=>$info['id']]) ? json_exit(200,'审核通过成功！') : json_exit(401,'审核通过失败！'); 
                }       
            }else{
                json_exit(401,'已处理！'); 
            }
        }else{
            json_exit(401,'数据不存在！');
        }
    }
    public function getList($get){
        $where = [];
        if(!empty($get['mid'])){
            $where[] = ['mid','=',$get['mid']];
        }
        if(!empty($get['status'])){
            $where[] = ['status','=',$get['status']];
        }
        if(!empty($get['uid'])){
            $where[] = ['uid','=',$get['uid']];
        }
        if(!empty($get['username'])){
            
            $memberModel = new MemberModel();
            $info = $memberModel->where([['username','like','%'.$get['username'].'%']])->find();
            // var_dump($info['id']);exit;
            $where[] = ['mid','=',$info['id']];
        }


        if(!empty($get['bankname'])){

            $memberModel = new MemberModel();
            $info = $memberModel->where([['name','like','%'.$get['bankname'].'%']])->column('id');

            $where[] = ['mid','in',$info];
        }


        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }   
        $MemberModel = new MemberModel;
        $BanksModel = new BanksModel;
        $UserModel = new UserModel;
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $v['username'] = $MemberModel->where(['id'=>$v['mid']])->value('username');
            $v['name'] = $MemberModel->where(['id'=>$v['mid']])->value('name');
            $v['bankid'] = $BanksModel->where(['uid'=>$v['mid']])->value('bankid');
            $v['bankinfo'] = $BanksModel->where(['uid'=>$v['mid']])->value('bankinfo');
            $v['uid'] = $UserModel->where(['id'=>$v['uid']])->value('username')?:'无';
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