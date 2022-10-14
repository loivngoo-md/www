<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
use app\api\model\LotteryModel;
use app\api\model\YulotteryModel;
class LotterykjModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'lottery_kj';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function kj(){
        ini_set('max_execution_time', '0');
        $lottery = new LotteryModel;
        $YulotteryModel = new YulotteryModel;
        $lottery_list = $lottery->lotteryList('all');
        foreach ($lottery_list as $k=>$v){ //遍历彩种
            $second = $v['rule'] * 60;  //获取彩种的每一期时间
            $now_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
            $now_expect = date('Ymd').$v['rule'].intval($now_time/$second); //获取当前期号
            $now_info = $this->where(['key'=>$v['key'],'expect'=>$now_expect])->find();  //去数据库寻找当前信息是否存在
            $is_yukaijiang = $YulotteryModel->where(['key'=>$v['key'],'expect'=>$now_expect])->find();  //获取是否有欲开奖
            if(empty($is_yukaijiang)){  //不存在欲开奖走第一个分支
                $data['key'] = $v['key'];  //获取key
                $data['expect'] = $now_expect;  //获取期号
                $data['opencode'] = rand(1, 6).",".rand(1, 6).",".rand(1, 6); //随机生成开奖
                $data['is_yukaijiang'] = 0;  //自动开奖标识符 
                $data['create_time'] = time(); //入库时间
                $data['update_time'] = time(); //更新时间
            }else{//存在走第二个分支
                $data['key'] = $is_yukaijiang['key'];  //获取欲开奖的key
                $data['expect'] = $is_yukaijiang['expect'];  //欲开奖的期号
                $data['opencode'] = $is_yukaijiang['opencode'];//欲开奖的号码
                $data['is_yukaijiang'] = 1;  //欲开奖标识符
                $data['create_time'] = time();  //欲开奖时间
                $data['update_time'] = time();  //欲开奖更新时间
            }
            $yukaijiangtext = $data['is_yukaijiang'] ?"欲开奖":"自动开奖";
            if(empty($now_info)){
                if($this->insert($data)){
                    echo("标识符:".$v['key']."---彩种名称:".$v['name']."---欲开奖:".$yukaijiangtext."---开奖成功--- 期号:".$now_expect."---开奖号码:".$data['opencode']."---开奖时间:".date("Y-m-d H:i",time())."---下一期开奖时间:".date("Y-m-d H:i",time()+$second)."\n");
                    file_put_contents("../application/api/log/kj.log","标识符:".$v['key']."---彩种名称:".$v['name'].$yukaijiangtext."---开奖成功--- 期号:".$now_expect."---开奖号码:".$data['opencode']."---开奖时间:".date("Y-m-d H:i",time())."---下一期开奖时间：".date("Y-m-d H:i",time()+$second)."\n", FILE_APPEND);
                    continue;
                }else {
                    echo("标识符:".$v['key']."---彩种名称：".$v['name']."---欲开奖:".$yukaijiangtext."---开奖失败--- 期号:".$now_expect."---开奖号码:".$data['opencode']."---开奖时间:".date("Y-m-d H:i",time())."---下一期开奖时间：".date("Y-m-d H:i",time()+$second)."\n");
                    file_put_contents("../application/api/log/kj.log","标识符:".$v['key']."---彩种名称:".$v['name'].$yukaijiangtext."---开奖失败--- 期号:".$now_expect."---开奖号码:".$data['opencode']."---开奖时间:".date("Y-m-d H:i",time())."---下一期开奖时间:".date("Y-m-d H:i",time()+$second)."\n", FILE_APPEND);
                    continue;
                }
            }else{
                echo("等待开奖中......"."\n");
                continue;
            }             
            
        }
    }
}