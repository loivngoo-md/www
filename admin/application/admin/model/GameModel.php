<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\MemberModel;
use app\admin\model\LotteryModel;

class GameModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'game';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getList($get){
        $where = [];
        if(!empty($get['username'])){

            $memberModel = new MemberModel();
            $info = $memberModel->where([['username','=',$get['username']]])->find();
            // var_dump($info['id']);exit;
            $where[] = ['mid','=',$info['id']];
        }
        // if(!empty($get['status']) || $get['status'] === "0"){
        //   $where[] = ['status','=',$get['status']];
        // }
        if(!empty($get['lid'])){
            $where[] = ['lid', '=', $get['lid']];
        }
        if(!empty($get['start_time']) && !empty($get['end_time'])){
           $count = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->count();
           $list = $this->where($where)->whereTime('create_time', 'between', [strtotime($get['start_time']), strtotime($get['end_time'])])->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }else {
           $count = $this->where($where)->count();
           $list = $this->where($where)->limit($get['limit'])->page($get['page'])->order('id','desc')->select();
        }

        $MemberModel = new MemberModel;
        $LotteryModel = new LotteryModel;
        foreach ($list as $k=>&$v){
            $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            $v['mid'] = $MemberModel->where(['id'=>$v['mid']])->value('username');
            $v['lid'] = $LotteryModel->where(['id'=>$v['lid']])->value('name');
            if($v['status'] === 0){
                $v['update_time'] = "未结算";
                $v['profit'] = "未结算";
            }else{
                $v['update_time'] = date("Y-m-d H:i:s",$v['update_time']);
                
            }
            
        }
        return [
            'data'=>$list,
            'count'=>$count
        ];        
    }    
    public function editBuyLottery($post,$id){
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if($info['status'] == 1){
                json_exit(401,'已开奖的投注记录无法修改！');
            }else{
                $data['money'] = $post['money'] ?trim($post['money']):$info['money'];
                $data['peilv'] = $post['peilv'] ?trim($post['peilv']):$info['peilv'];
                $data['type'] = $post['type'] ?trim($post['type']):$info['type'];
                $data['update_time'] = time();
                return $this->save($data,['id'=>$info['id']]) ? json_exit(200,'修改成功！') : json_exit(401,'修改失败！');
            }
        }else{
            json_exit(401,'数据不存在！');
        }
    }
   
}