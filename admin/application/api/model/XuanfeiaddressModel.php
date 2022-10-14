<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
// use app\api\model\UserModel;
class XuanfeiaddressModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xuanfei_address';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getaddressList(){
        $list = $this->order('id','asc')->select()->toArray();
        $data = [];

        for ($i = 0; $i < count($list); $i++) {
             $data[] = array_slice($list, $i * 4 ,4); 
        }
        $data = array_filter($data);
        json_exit_Base64(200,'数据获取成功！',$data);
    }
}