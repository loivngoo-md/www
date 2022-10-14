<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
// use app\api\model\UserModel;
class VideoModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'video';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getHotList(){
        $list = $this->where(['vod_status'=>1,'vod_hot'=>1])->order('id','asc')->select();
        foreach ($list as $k=>&$v) {
            $v['title'] = $v['vod_name'];
            $v['cover'] = $v['vod_pic'];
            $v['time'] = $v['vod_duration'];
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
        }
        return $list;
    }
    public function getVideoInfo($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$post['id']])->find();
        if($info){
            $info['count'] = $info['vod_score_num'];
            json_exit_Base64(200,"获取信息成功！",$info);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }    
    public function classToID($id){
        $VideoclassModel = new VideoclassModel;
        $data = $VideoclassModel->getItemList();
        return $data[$id];
    }    
    public function getVideoList($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->classToID($post['id']);
        $list = $this->limit(8)->page($post['page'])->where(['vod_class_id'=>$info['id']])->select();
        if($list){
            foreach ($list as $k=>&$v){
                $v['count'] = $v['vod_score_num'];
            }
            json_exit_Base64(200,"获取信息成功！",['data'=>$list,'count'=>$this->where(['vod_class_id'=>$info['id']])->count()]);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }  
    }
    public function getRandHotList($count){
        $list = $this->where(['vod_status'=>1])->orderRaw('rand()')->limit($count)->order('id','asc')->select();
        foreach ($list as $k=>&$v) {
            $v['title'] = $v['vod_name'];
            $v['cover'] = $v['vod_pic'];
            $v['time'] = $v['vod_duration'];
            $v['count'] = $v['vod_score_num'];
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
        }
        return $list;        
    }
}