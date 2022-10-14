<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
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
   
}