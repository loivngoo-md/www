<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\UserModel;
class VideoclassModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'video_class';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    



    public function editStatus($post){
        $info = $this->where(['id'=>$post['id']])->find();
        if($info){
            $data['status'] = $post['status'];
            $data['update_time'] = time();
            $this->save($data,['id'=>$info['id']]) ? json_exit(200,'修改成功！'):json_exit(200,'修改失败！');
        }else{
            json_exit(401,'数据不存在！');
        }
    }



    public function getOneData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info;
        }
    }    
    public function getUserRoleTree($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $info['role'];
        } 
    }
    
    
    
    public function selectList(){
        $selectList = $this->order('id','asc')->select();
        return $selectList;
    }
    
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'视频分类不存在！');
            }else{
                $data['name'] = $post['name'] ?(string)trim($post['name']):$info['name'];
                $data['update_time'] = time();
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $data['name'] = (string)trim($post['name']);
            $data['sort'] = $this->max('sort') + 1;
            $data['status'] = 1;
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
        }
    }
    public function getList($get){
        $where = [];
        if(!empty($get['name'])){
            $where[] = ['name','like',"%".$get['name']."%"];
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }   
        $VideoModel = new VideoModel;
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['count'] = $VideoModel->where(['vod_class_id'=>$v['id']])->count();
        }   
  
        return [
            'data'=>$list,
            'count'=>$count
            ];        
    }
    
    
        
    // 删除
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{

           $list = VideoModel::where(['vod_class_id'=>$id])->find();
           if(!empty($list)){
               json_exit(401,'分类存在下级，不能删除！');
           }
            return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }
    }
}