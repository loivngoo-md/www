<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
class RechargeModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'recharge';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
}