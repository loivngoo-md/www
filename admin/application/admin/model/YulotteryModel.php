<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use app\admin\model\LotteryModel;
use app\admin\model\OpenlotteryModel;
use app\admin\model\UserModel;
class YulotteryModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'lottery_yukaijiang';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function cancel($post){
        $info = $this->where(['key'=>$post['key'],'expect'=>$post['expect']])->find();
        if(empty($info)){
            json_exit(401,"数据不存在");  
        }else{
            $this->where(['id'=>$info['id']])->delete() ? json_exit(200,"取消成功！") : json_exit(200,"取消失败！");
        }
    }
    public function saveYuKaiJiang($post){
        $info = $this->where(['key'=>$post['key'],'expect'=>$post['expect']])->find();
        $data['key'] = $post['key'];
        $data['expect'] = $post['expect'];
        $data['opencode'] = $post['opencode'];
        $data['player'] = Session::get('userinfo')['player'];
        $data['pid'] = Session::get('userinfo')['id'];
        $data['create_time'] = time();
        $data['update_time'] = time();
        if(empty($info)){
            return $this->save($data) ? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
        }else {
            return $this->save($data,['id'=>$info['id']]) ? json_exit(200,'保存成功！') : json_exit(401,'保存失败！');
        }
    }
    public function saveAllYuKaiJiang($post){
        
        foreach ($post['data'] as $k=>$v){
            $info = $this->where(['key'=>$v['key'],'expect'=>$v['expect']])->find();
            if(empty($info)){
                $data['key'] = $v['key'];
                $data['expect'] = $v['expect'];
                $data['opencode'] = $v['opencode'];
                $data['player'] = Session::get('userinfo')['player'];
                $data['pid'] = Session::get('userinfo')['id'];
                $data['create_time'] = time();
                $data['update_time'] = time();
                $this->insert($data);
            }
        }
        json_exit(200,'保存成功！');

    }    
    public function getList($get){
         $lottery = new LotteryModel;
         $UserModel = new UserModel;
         $where = [];
         if(!empty($get['key'])){
             $where[] = ['key','=',$get['key']];
         }   
         $where[] = ['status','=',1];
         $Lottery_list = $lottery->where($where)->select();
         $arr = [];
         $OpenlotteryModel = new OpenlotteryModel;
         foreach ($Lottery_list as $k=>&$v){
             $second = $v['rule'] * 60;
             $now_time = (date('H')*60*60 + date('i') * 60);
             $now_expect = date('Ymd').$v['rule'].intval($now_time/$second);
             $now_info = $OpenlotteryModel->where(['key'=>$v['key'],'expect'=>$now_expect])->find();
             for ($i = 1; $i < 50; $i++) {

                  $now_yukaijiang_info = $this->where(['key'=>$v['key'],'expect'=>$now_expect + $i])->find();
                    $second = ($v['rule'] * 60);  //获取彩种的每一期时间
                    $next_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
                    if($next_time + $second >= 86400){
                        $next_time =86400 - $next_time + $second;
                        $next_expect = date('Ymd').$v['rule'].intval($next_time/($second* $i)); //获取下一期期号       
                    }else{
                        $next_expect = date('Ymd').$v['rule'].intval(($next_time+$second* $i)/$second); //获取下一期期号       
                    }
                  if(empty($now_yukaijiang_info)){
                      $arr[] = [
                          'name'=>$v['name'],
                          'key'=>$v['key'],
                          'opencode'=>rand(1, 6).",".rand(1, 6).",".rand(1, 6),
                          'expect'=>$next_expect,
                          'create_time'=>date("Y-m-d H:i:s",$now_info['create_time'] + $second * $i)
                         ];
                  }else{
                        $role = $now_yukaijiang_info['player'] == 1 ? "超管|" : "代理|";
                        $player = $role . $UserModel->where(['player'=>$now_yukaijiang_info['player'],'id'=>$now_yukaijiang_info['pid']])->value("username")."|id:".$UserModel->where(['player'=>$now_yukaijiang_info['player'],'id'=>$now_yukaijiang_info['pid']])->value("id");
                        $arr[] = [
                          'name'=>$v['name'],
                          'key'=>$v['key'],
                          'player'=>$player,
                          'opencode'=>$now_yukaijiang_info['opencode'],
                          'expect'=>$now_yukaijiang_info['expect'],
                          'create_time'=>date("Y-m-d H:i:s",$now_info['create_time'] + $second * $i),
                          'update_time'=>date("Y-m-d H:i:s",$now_yukaijiang_info['update_time'])
                         ];
                  }

             }
         }
         return [
            'data'=>$arr,
            'count'=>0
            ];        
    }

}