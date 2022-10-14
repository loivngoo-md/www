<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;
class SystemModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'system';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getConfig($key){
        $info = $this->where(['key'=>$key])->find();
        if(empty($info)){
            return [];
        }else{
            return json_decode($info['value'],true);
        }
    }    
    public function saveData($post,$key){
        
        $info = $this->where(['key'=>$key])->find();
        if(!empty($info)){
            $data['value'] = json_encode($post);
            $data['update_time'] = time();
            return $this->save($data,['key'=>$key]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
        }else {
            $data['value'] = json_encode($post);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $data['key'] = $key;
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
        }

    }

   
}