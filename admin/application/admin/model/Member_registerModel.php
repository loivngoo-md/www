<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
class Member_registerModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'member_register';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    } 
    public function getList($get){
        $where = [];
        $user_info = Session::get('userinfo');
        if($user_info['player'] == 2){
            $where[] = ['uid','=',$user_info['id']];
        }
        if(!empty($get['id'])){
            $where[] = ['id','=',$get['id']];
        }
        if(!empty($get['mid'])){
            $where[] = ['mid','=',$get['mid']];
        }   
        if(!empty($get['uid'])){
            $where[] = ['uid','=',$get['uid']];
        }      
        if(!empty($get['code'])){
            $where[] = ['code','like',"%".$get['code']."%"];
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
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $v['username'] = $MemberModel->where(['id'=>$v['mid']])->value('username')?:"数据丢失";
            $v['uid'] = $UserModel->where(['id'=>$v['uid']])->value('username')?:"数据丢失";
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
        }
        return [
            'data'=>$list,
            'count'=>$count
        ];        
    }      

}