<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\UserModel;
use app\admin\model\LotteryclassModel;
use app\admin\model\LotterypeilvModel;
class VideoModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'video';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }

    public function doEditHot($post){
        $info = $this->where(['id'=>$post['id']])->find();
        if($info){
             $data['vod_hot'] = $post['status'];
             $data['update_time'] = time();
             $this->save($data,['id'=>$info['id']]) ? json_exit(200,'修改成功！'):json_exit(200,'修改失败！');
        }else{
             json_exit(401,'数据不存在！');
        }
    }  
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'视频数据不存在！');
            }else{
                $data['vod_name'] = $post['vod_name'] ?(string)trim($post['vod_name']):$info['vod_name'];
                $data['vod_pic'] =  $post['vod_pic'] ?(string)trim($post['vod_pic']):$info['vod_pic'];
                $data['vod_play_url'] = $post['vod_play_url'] ?(string)trim($post['vod_play_url']):$info['vod_play_url'];
                $data['vod_class_id'] = $post['vod_class_id'] ?(int)trim($post['vod_class_id']):$info['vod_class_id'];
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['vod_name'=>$post['vod_name']])->find();
            if(!empty($info)){
               json_exit(401,'视频名称重复！');
            }
            $data['vod_name'] = (string)trim($post['vod_name']);
//            $data['vod_time'] = (string)trim($post['vod_time']);
            $data['vod_pic'] = (string)trim($post['vod_pic']);
            $data['vod_play_url'] = (string)trim($post['vod_play_url']);
            $data['vod_score_num'] = (int)trim($post['vod_score_num']??0);
            $data['vod_class_id'] = (int)trim($post['vod_class_id']);
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    public function selectList(){
        $selectList = $this->order('id','asc')->select();
        return $selectList;
    }


    public function getList($get){
        $where = [];
        if(!empty($get['vod_name'])){
            $where[] = ['vod_name','like',"%".$get['vod_name']."%"];
        }
        if(!empty($get['status']) || $get['status'] === "0"){
          $where[] = ['status','=',$get['status']];
        }        
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }   
        $VideoclassModel = new VideoclassModel();
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['vod_class_name'] = $VideoclassModel->where(['id'=>$v['vod_class_id']])->value("name");
        }   
        return [
            'data'=>$list,
            'count'=>$count
            ];        
    }

//  获取单条数据
    public function getOneData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info;
        }
    }

// 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
}