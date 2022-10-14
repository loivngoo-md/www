<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use app\api\model\LotterykjModel;
use app\api\model\LotterypeilvModel;
class LotteryModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'lottery';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function getLotteryPeilv($post){
        $list = $this->peilvJsonToDataBase($post['id']);
        foreach ($list as $k=>$v){
            if($v['status'] != 1){
                unset($list[$k]);
            }
        }
        
        json_exit_Base64(200,'数据获取成功！',$list);
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
    public function getLotteryOneList($post){
        $id = base64_decode(Request::header('token'));
        $member = new MemberModel;
        $LotterykjModel = new LotterykjModel;
        $info = $member->where(['id'=>$id])->find();
        if($info){
            $lottery_info = $this->where(['key'=>$post['key']])->find();
            if(!$lottery_info){
                json_exit_Base64(401,'数据获取失败');
            }else{
                $data = $LotterykjModel->where(['key'=>$lottery_info['key']])->limit(30)->order('id','desc')->select();
                foreach ($data as $k=>&$v) {
                    $opencode = explode(",",$v['opencode']);
                    $opencode[0] = (int)$opencode[0];
                    $opencode[1] = (int)$opencode[1];
                    $opencode[2] = (int)$opencode[2];                    
                    $v['opencode'] = $opencode;
                }
                json_exit_Base64(200,"数据获取成功",$data);
            }
        }else {
            json_exit_Base64(401,"鉴权错误");
        }
    }
    public function getLotteryInfo($post){
        $id = base64_decode(Request::header('token'));
        $member = new MemberModel;
        $info = $member->where(['id'=>$id])->find();
        if($info){
            $lottery_info = $this->where(['key'=>$post['key']])->find();
            if(empty($lottery_info)){
                json_exit_Base64(401,'数据获取失败');
            }else{
                $lottery_info['second'] = $lottery_info['rule'] * 60;
                $now_time = (date('H')*60*60 + date('i') * 60);
                $lottery_info['now_expect'] = date('Ymd').$lottery_info['rule'].intval($now_time/$lottery_info['second']);
                $second = $lottery_info['rule'] * 60;  //获取彩种的每一期时间
                $next_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
                if($next_time + $second >= 86400){
                    $next_time = $next_time + $second - 86400 ;
                    $next_expect = date('Ymd').$lottery_info['rule'].intval($next_time/$second); //获取下一期期号       
                }else{
                    $next_expect = date('Ymd').$lottery_info['rule'].intval(($next_time+$second)/$second); //获取下一期期号       
                }
                $lottery_info['next_expect'] = $next_expect;
                $LotterykjModel = new LotterykjModel;
                $now_info = $LotterykjModel->where(['key'=>$lottery_info['key'],'expect'=>$lottery_info['now_expect']])->find();
                if($now_info){
                    $lottery_info['second'] = $now_info['create_time'] + $lottery_info['second'] - time();
                    $opencode = explode(",",$now_info['opencode']);
                    $opencode[0] = (int)$opencode[0];
                    $opencode[1] = (int)$opencode[1];
                    $opencode[2] = (int)$opencode[2];
                    $lottery_info['opencode'] = $opencode;
                }
                json_exit_Base64(200,'数据获取成功',$lottery_info);
            }
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }    
    public function hotLottery(){
       return $this->where(['status'=>1,'hot'=>1])->order('id','asc')->select();
    }
    public function lotteryList($info){
        $fields = "id,name,status,rule,cid,condition,hot,key,ico,create_time,update_time";  //desc三分钟一期
        if($info =="all"){
            return $this->field($fields)->where(['status'=>1])->order('id','asc')->select();
        }else {
            return $this->field($fields)->where(['status'=>1,'cid'=>$info['id']])->order('id','asc')->select();
        }
    }
}