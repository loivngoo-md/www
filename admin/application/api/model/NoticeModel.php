<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
// use app\api\model\UserModel;
class NoticeModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'notice';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getNoticeList($post){
        $list = $this->where(['status'=>1])->order('id','asc')->select();
        foreach ($list as $k=>&$v) {
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
        }
        json_exit_Base64(200,'数据获取成功！',$list);
    }
}