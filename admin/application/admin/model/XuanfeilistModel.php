<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
class XuanfeilistModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'xuanfei_list';
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
                json_exit(401,'数据不存在！');
            }else{
                $data['xuanfei_name'] = $post['xuanfei_name'] ?(string)trim($post['xuanfei_name']):$info['xuanfei_name'];
                $data['img_url'] =  json_encode($post['pc_src']);
                // $data['vod_play_url'] = $post['vod_play_url'] ?(string)trim($post['vod_play_url']):$info['vod_play_url'];
                $data['class_id'] = $post['class_id'] ?(int)trim($post['class_id']):$info['class_id'];
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['xuanfei_name'=>$post['xuanfei_name']])->find();
            if(!empty($info)){
               json_exit(401,'名称重复！');
            }
            $data['xuanfei_name'] = (string)trim($post['xuanfei_name']);
//            $data['vod_time'] = (string)trim($post['vod_time']);
            $data['img_url'] = json_encode($post['pc_src']);
            // $data['vod_play_url'] = (string)trim($post['vod_play_url']);
            // $data['vod_score_num'] = (int)trim($post['vod_score_num']??0);
            $data['class_id'] = (int)trim($post['class_id']);
            $data['create_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    public function selectList(){
        $XuanfeiAddressModel = new XuanfeiAddressModel;
        $selectList = $XuanfeiAddressModel->selectList();
        return $selectList;
    }


    public function getxuanfeilist($get){
        $where = [];
        if(!empty($get['xuanfei_name'])){
            $where[] = ['a.xuanfei_name','like',"%".$get['xuanfei_name']."%"];
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $list = $this->alias('a')->join('xuanfei_address b','a.class_id = b.id')->where($where)->whereTime('a.create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('a.id','desc')->field('a.*,b.name')->select();
        }else {
           $list = $this->alias('a')->join('xuanfei_address b','a.class_id = b.id')->where($where)->limit($get['limit'])->page($get['page'])->order('a.id','desc')->field('a.*,b.name')->select();
        }   
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['vod_pic'] = '../../../'. json_decode($v['img_url'],true)[0];
        }  
        return [
            'data'=>$list,
            'count'=>10
            ];        
    }

//  获取单条数据
    public function getOneData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            $info['vod_pic'] = json_decode($info['img_url'],true);
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