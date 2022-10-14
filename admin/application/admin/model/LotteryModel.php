<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\UserModel;
use app\admin\model\LotteryclassModel;
use app\admin\model\LotterypeilvModel;
class LotteryModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'lottery';
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
    public function doEditPeilvState($post,$id){
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $LotterypeilvModel = new LotterypeilvModel;
            $peilvinfo = $LotterypeilvModel->where(['type'=>$post['type'],'lid'=>$id])->find();
            
            if(!$peilvinfo){
                $data['type'] = $post['type'];
                $data['lid'] = (int)$id;
                $data['name'] = $post['name'];
                $data['proportion'] = $post['proportion'];
                $data['status'] = (int)$post['status'];
                $data['create_time'] = time();
                $data['update_time'] = time();
                $LotterypeilvModel->save($data)? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
            }else{
                $data['status'] = (int)$post['status'];
                $data['update_time'] = time();                
                return $LotterypeilvModel->save($data,['type'=>$post['type'],'lid'=>$id]) ? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
            }
        }else{
            json_exit(401,'数据不存在！');
        }        
    }
    public function editPeilv($post,$id){
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $LotterypeilvModel = new LotterypeilvModel;
            $peilvinfo = $LotterypeilvModel->where(['type'=>$post['type'],'lid'=>$id])->find();
            if(!$peilvinfo){
                $data['type'] = $post['type'];
                $data['lid'] = $id;
                $data['name'] = $post['name'];
                $data['proportion'] = $post['proportion'];
                $data['status'] = 1;
                $data['create_time'] = time();
                $data['update_time'] = time();
                $LotterypeilvModel->save($data)? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
            }else{
                $data['name'] = $post['name'];
                $data['proportion'] = $post['proportion'];
                $data['update_time'] = time();                
                return $LotterypeilvModel->save($data,['type'=>$post['type'],'lid'=>$id]) ? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
            }
        }else{
            json_exit(401,'数据不存在！');
        }
    }
    public function peilvJsonToDataBase($id){
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $json = file_get_contents('../application/admin/data/peilv.json'); 
            $data = json_decode($json, true);
            $LotterypeilvModel = new LotterypeilvModel;
            $lottery_peilv = $LotterypeilvModel->where(['lid'=>$info['id']])->select();
            $arr=[];
            foreach ($data as $k=>$v){
                if(count($lottery_peilv) == 0){ //判断是否没有任何自定义数组
                    $arr = $data;  //
                }else{
                    foreach($lottery_peilv as $key=>$value){
                        $is_ = false;
                        if($v['type'] == $value['type']){
                            $arr[] = $value;
                            $is_ = true;
                            break;
                        }
                    }
                    if($is_ === false){
                        $arr[] = $v;
                    }
                }
            }
            return $arr;
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
    public function delData($id){
        $info = $this->where(['id'=>$id])->find();
        if(empty($info)){
            json_exit(401,'数据不存在！');
        }else{
            return $this->where(['id'=>$id])->delete() ? json_exit(200,'删除成功！') : json_exit(401,'删除失败！');
        }  
    }    
    public function saveData($post){
        if(!empty($post['id'])){
            $info = $this->where(['id'=>$post['id']])->find();
            if(empty($info)){
                json_exit(401,'彩票数据不存在！');
            }else{
                $hot_count = $this->where(['hot'=>1,'id'=>['neq',$post['id']]])->count();
                if($post['hot'] == 1){
                    if($hot_count >= 4){
                        json_exit(401,'热门彩种最多只能设置4个！');
                    }  
                }
                $data['name'] = $post['name'] ?(string)trim($post['name']):$info['name'];
                $data['hot'] = $post['hot'];
                $data['ico'] = $post['ico'] ?(string)trim($post['ico']):$info['ico'];
                $data['condition'] = $post['condition'] ?trim($post['condition']):$info['condition'];
                $data['cid'] = $post['cid'] ?(int)trim($post['cid']):$info['cid'];
                $data['desc'] = $post['desc'] ?(string)trim($post['desc']):$info['desc'];
                $data['rule'] = $post['rule'] ?(int)trim($post['rule']):$info['rule'];
                $data['update_time'] = time();
                return $this->save($data,['id'=>$post['id']]) ? json_exit(200,'更新成功！',$data) : json_exit(401,'更新失败！');
            }            
        }else{
            $info = $this->where(['key'=>$post['key']])->find();
            if(!empty($info)){
               json_exit(401,'彩票标识重复！'); 
            }
            $hot_count = $this->where(['hot'=>1])->count();
            if($post['hot'] == 1){
                if($hot_count >= 4){
                    json_exit(401,'热门彩种最多只能设置4个！');
                }
            }
            $data['condition'] = trim($post['condition']);
            $data['name'] = (string)trim($post['name']);
            $data['hot'] = (int)trim($post['hot']);
            $data['key'] = (string)trim($post['key']);
            $data['ico'] = (string)trim($post['ico']);
            $data['cid'] = (int)trim($post['cid']);
            $data['desc'] = (string)trim($post['desc']);
            $data['rule'] = (int)trim($post['rule']);
            $data['status'] = 1;
            $data['create_time'] = time();
            $data['update_time'] = time();
            return $this->save($data) ? json_exit(200,'提交成功！') : json_exit(401,'提交失败！');
            
        }
    }
    public function selectList(){
        $selectList = $this->order('id','asc')->select();
        return $selectList;
    }    
    public function getList($get){
        $where = [];
        if(!empty($get['name'])){
            $where[] = ['name','like',"%".$get['name']."%"];
        }
        if(!empty($get['cid'])){
            $where[] = ['cid','=',$get['cid']];
        }    
        if(!empty($get['key'])){
            $where[] = ['key','like',"%".$get['key']."%"];
        }           
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','asc')->select();
        }   
        $LotteryclassModel = new LotteryclassModel;
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i",$v['create_time']);
            $v['cid'] = $LotteryclassModel->where(['id'=>$v['cid']])->value("name");
        }   
  
        return [
            'data'=>$list,
            'count'=>$count
            ];        
    }
}