<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;
use app\api\model\Member_registerModel;
use think\Request;

class LogModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'user_log';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    
    // 添加或编辑管理员账号
    
        public function saveLogData(){

        $request = \request();
        $userinfo = Session::get('userinfo');
        $data['url'] = $request->baseUrl();

     if(!empty($request->param()) && !empty($userinfo)){
            $data = [
                'url'=>$request->baseUrl(),
                'param' => json_encode($request->param()),
                'ip' => $request->ip(),
                'uid'=>$userinfo['id'],
                'uname'=>$userinfo['username'],
            ];

            return $this->save($data);
        }
    }
    
    
    
    /*
     * 获取列表信息
     * */
    public function getList($get){
        $where = [];
        if(!empty($get['username'])){
            $where[] = ['uname','like',"%".$get['username']."%"];
        }
        if(Session::get('userinfo')['id'] > 1){
            $where[] = ['uid','=',Session::get('userinfo')['id']];
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_at', 'between', [($get['start_time']), ($get['end_time'])])->count();
           $userlist = $this->where($where)->whereTime('create_at', 'between', [($get['start_time']), ($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $userlist = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }
        return [
            'data'=>$userlist,
            'count'=>$count
            ];        
    }

}