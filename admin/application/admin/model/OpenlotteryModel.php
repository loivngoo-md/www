<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\LotteryModel;
use app\admin\model\LotteryclassModel;
use app\admin\model\YulotteryModel;
use app\admin\model\UserModel;
class OpenlotteryModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'lottery_kj';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
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
        if(!empty($get['expect'])){
            $where[] = ['expect','like',"%".$get['expect']."%"];
        }        
        if(!empty($get['key'])){
            $where[] = ['key','=',$get['key']];
        }        
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }
        $LotteryModel = new LotteryModel;
        $LotteryclassModel = new LotteryclassModel;
        $YulotteryModel = new YulotteryModel;
        $UserModel = new UserModel;
        foreach ($list as $k=>&$v){
            $_opentime = $v['create_time'];
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $lottery = $LotteryModel->where(['key'=>$v['key']])->find();
            $lotteryclass = $LotteryclassModel->where(['id'=>$LotteryModel->where(['key'=>$v['key']])->value("cid")])->find();
            $yukaijiang_info = $YulotteryModel->where(['key'=>$v['key'],'expect'=>$v['expect']])->find();
            if($v['is_yukaijiang'] && $yukaijiang_info){
                $userinfo = $UserModel->where(['player'=>$yukaijiang_info['player'],'id'=>$yukaijiang_info['pid']])->find();
                $v['yukiangjiang'] = "欲开奖|".$userinfo['player']==1 ? "超管|": "代理|" .$userinfo['username']."|id:".$userinfo['id']; 
            }else{
               $v['yukiangjiang'] = "自动开奖"; 
            }
            
            $v['next_opentime'] = date("Y-m-d H:i:s",$_opentime + $lottery['rule'] * 60);
            $v['name'] = $lottery['name'];
            $v['cid'] = $lotteryclass["name"];
            $v['rule'] = $lottery['rule']."分钟1期";
        }   
  
        return [
            'data'=>$list,
            'count'=>$count
            ];        
    }
    public function selectList(){
        $selectList = $this->order('id','asc')->select();
        return $selectList;
    }
}