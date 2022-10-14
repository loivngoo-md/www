<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
// use app\api\model\UserModel;
class VideoclassModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'video_class';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getItemList(){
       return $this->where(['status'=>1])->order('sort','asc')->select();
    }
}