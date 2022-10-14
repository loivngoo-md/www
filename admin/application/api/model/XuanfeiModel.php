<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
// use app\api\model\UserModel;
class XuanfeiModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xuanfei_list';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getList($id){
        if (empty($id)) {
            json_exit_Base64(401,"id为空");
        }
        $list = $this->where(['class_id'=>$id])->order('id','asc')->select()->toArray();
        foreach ($list as $k=>&$v){
            $v['img_url'] = 'http://'.$_SERVER['SERVER_NAME'] .'/'. json_decode($v['img_url'],true)[0];
        }
        json_exit_Base64(200,'数据获取成功！',$list);
    }
    
    public function xuanfeidata($id)
    {
        if (empty($id)) {
            json_exit_Base64(401,"id为空");
        }
        $data = $this->where(['id'=>$id])->find();
        $data['img_url'] = json_decode($data['img_url'],true);
        // var_dump($data['img_url']);die;
        $a = [];
        foreach ($data['img_url'] as $k => $v){
            $a[$k] = 'http://'.$_SERVER['SERVER_NAME'] .'/'. $v;
        }
        $data['img_url'] = $a;
        json_exit_Base64(200,'数据获取成功！',$data);
    }
}