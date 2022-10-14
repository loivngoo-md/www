<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
class RechargeModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'recharge';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getList($get){
         $MemberModel = new MemberModel;
        $where = [];
        if(!empty($get['uid'])){
            $where[] = ['uid','=',$get['uid']];
        }
        if(!empty($get['mid'])){
            $where[] = ['mid','=',$get['mid']];
        }
        
          if(!empty($get['username'])){
            /*$midData = $MemberModel->where('username','like',"%".$get['username']."%")->field('id')->select()->toArray();
            $ids = array_column($midData,'id');
            $where[] = ['mid','IN',$ids];*/
            
            $info = $MemberModel->where('username',$get['username'])->find();
            // var_dump($info['id']);exit;
            $where[] = ['mid','=',$info['id']];
        }
        
        if(!empty($get['type'])){
            $where[] = ['type','=',$get['type']];
        }        
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }
        $MemberModel = new MemberModel;
        $UserModel = new UserModel;
        // $user_info = Session::get('userinfo');
        // if($user_info['player'] == 1){
        //     if(!empty($get['uid'])){
        //         $user_info['id'] = $get['uid'];
        //         $user_info['player'] = 2;
        //     }    
        // }
        foreach ($list as $k=>&$v){
            $member = $MemberModel->where(['id'=>$v['mid']])->find();
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $v['mid'] = $MemberModel->where(['id'=>$v['mid']])->value("username")?:"暂无数据";
            $v['uid'] = $UserModel->where(['id'=>$v['uid']])->value("username")?:"暂无数据";

        }
        return [
            'data'=>$list,
            'count'=>$count
        ];        
    }   
}