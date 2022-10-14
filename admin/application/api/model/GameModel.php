<?php
namespace app\api\model;

use app\api\model\LotteryModel;
use think\Model;
use think\facade\Session;
use app\api\model\MemberModel;
use app\api\model\LotterykjModel;
class GameModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'game';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function placeOrder($post){
        $MemberModel = new MemberModel;
        $LotteryModel = new LotteryModel;
        $member_info = $MemberModel->where(['id'=>$post['mid']])->find();
        $lottery_info = $LotteryModel->where(['id'=>$post['lid']])->find();
        if($lottery_info && $member_info){
            $itemlist = explode(",",$post['item']);
            $allmoney = count($itemlist) * $post['money'];
            if($member_info['money'] - $allmoney < 0){
                json_exit_Base64(401,'余额不足！');
            }else{
                $new_money = $member_info['money'] - $allmoney;
                if(!$MemberModel->save(['money'=>$new_money,'update_time'=>time()],['id'=>$post['mid']])){
                    json_exit_Base64(401,'扣款失败！');
                }
            }
            if ($member_info['amount_code']-$allmoney < 0){
                $allmoney = $member_info['amount_code'];
            }
            $MemberModel->where(['id'=>$post['mid']])->update(['amount_code'=>$member_info['amount_code']-$allmoney]);

            $second = $lottery_info['rule'] * 60;  //获取彩种的每一期时间
            $next_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
            if($next_time + $second >= 86400){
                $next_time = $next_time + $second - 86400 ;
                $next_expect = date('Ymd').$lottery_info['rule'].intval($next_time/$second); //获取下一期期号       
            }else{
                $next_expect = date('Ymd').$lottery_info['rule'].intval(($next_time+$second)/$second); //获取下一期期号       
            }
            foreach ($itemlist as $k=>$v) {
                $a = $k+1;
                $b = $k;
                $time = time();
                $data['type'] = $v;
                $data['mid'] = $member_info['id'];
                $data['lid'] = $lottery_info['id'];
                $data['money'] = $post['money'];
                $data['status'] = 0;
                $data['expect'] = $next_expect;
                $data['peilv'] = $this->getLotteryPeilv($data['lid'],$data['type']);
                $data['is_win'] = 0;
                $data['create_time'] = $time;
                $data['before_betting'] = $member_info['money']-$b*$post['money'];
                $data['after_betting'] = $member_info['money']-$a*$post['money'];

                $info = $this->where(['mid'=>$member_info['id'],'type'=>$v,'expect'=>$next_expect,'create_time'=>$time])->find();

                if(!empty($info) || !$this->insert($data)){
                    json_exit_Base64(401,'下注异常！');
                }
            }
            json_exit_Base64(200,'投注成功！');
        }else {
            json_exit_Base64(401,'参数异常！',$post);
        }
        
    }
    public function getLotteryPeilv($lid,$type){
        $LotteryModel = new LotteryModel;
        $peilv = $LotteryModel->peilvJsonToDataBase($lid);
        foreach ($peilv as $k=>$v){
            if($v['type'] == $type){
                return $v['proportion'];
            }
        }
        return 0;
    }
    public function settle(){
        ini_set('max_execution_time', '0');
        $list = $this->where(['status'=>0])->select();
        $MemberModel = new MemberModel;
        $LotteryModel = new LotteryModel;
        $LotterykjModel = new LotterykjModel;
        if(!$list->isEmpty()){
            foreach($list as $k=>$v){
                $member_info = $MemberModel->where(['id'=>$v['mid']])->find();
                $lottery_info = $LotteryModel->where(['id'=>$v['lid']])->find();
                $kj_info = $LotterykjModel->where(['expect'=>$v['expect'],'key'=>$lottery_info['key']])->find();
                $data=[];

                if($kj_info){
                    if($v['type'] == "大"){
                        $opencode = explode(",",$kj_info['opencode']);
                        $num = 0;
                        foreach ($opencode as $ok=>$ov){
                            $num += $ov;
                        }
                        if($num >= 11 && $num <= 18){
                            $data['is_win'] = 1;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['peilv'] * (int)$v['money'];
                        }else{
                            $data['is_win'] = 2;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['money'];
                        }
                    }elseif ($v['type'] == "小") {
                        $opencode = explode(",",$kj_info['opencode']);
                        $num = 0;
                        foreach ($opencode as $ok=>$ov){
                            $num += $ov;
                        }
                        if($num >= 3 && $num <= 10){
                            $data['is_win'] = 1;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] =$v['peilv'] * (int)$v['money'];
                        }else{
                            $data['is_win'] = 2;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['money'];
                        }
                    }elseif ($v['type'] == "单") {
                        $opencode = explode(",",$kj_info['opencode']);
                        $num = 0;
                        foreach ($opencode as $ok=>$ov){
                            $num += $ov;
                        }
                        $text = $num % 2 == 0 ? "双" : "单";
                        if($text == $v['type']){
                            $data['is_win'] = 1;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['peilv'] * (int)$v['money'];
                        }else{
                            $data['is_win'] = 2;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['money'];
                        }
                    }elseif ($v['type'] == "双") {
                        $opencode = explode(",",$kj_info['opencode']);
                        $num = 0;
                        foreach ($opencode as $ok=>$ov){
                            $num += $ov;
                        }
                        $text = $num % 2 == 0 ? "双" : "单";
                        if($text == $v['type']){
                            $data['is_win'] = 1;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['peilv'] * (int)$v['money'];
                        }else{
                            $data['is_win'] = 2;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['money'];
                        }
                    }elseif ($v['type'] >= "3" && $v['type'] <= "18") {
                        $opencode = explode(",",$kj_info['opencode']);
                        $num = 0;
                        foreach ($opencode as $ok=>$ov){
                            $num += $ov;
                        }
                        if($num == $v['type']){
                            $data['is_win'] = 1;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['peilv'] * (int)$v['money'];
                        }else{
                            $data['is_win'] = 2;
                            $data['status'] = 1;
                            $data['update_time'] = time();
                            $data['profit'] = $v['money'];
                        }
                    }

                    if($data['is_win'] == 1){
                        //$data['after_kj'] = $member_info['money'] + $data['profit'];
                        $this->where(['id'=>$v['id']])->update($data);
                        $win_money = $member_info['money'] + $data['profit'];
                        $MemberModel->where('id',$v['mid'])->setField('money',$win_money);
                        $jieguo = "盈利";
                    }else{
                        //$data['after_kj'] = $member_info['money'];
                        $this->where(['id'=>$v['id']])->update($data);
                        $jieguo = "亏损";
                    }
                    echo("[".date("Y-m-d H:is",time()).
                        "] 用户：".$member_info['username'].
                        " |玩法：".$v['type'].
                        " |下注金额：".$v['money'].
                        " |盈亏：".$jieguo.
                        " |盈亏金额：".$data['profit'].
                        " |期号：".$v['expect'].
                        " |彩种：".$lottery_info['name'].
                        " |赔率：".$v['peilv'].
                        " |下注时间：".date("Y-m-d H:is",$v['create_time']).
                        " |状态："."结算成功".
                        "\n");
                    file_put_contents("../application/api/log/jiesuan.log","[".date("Y-m-d H:is",time()).
                        "] 用户：".$member_info['username'].
                        " |玩法：".$v['type'].
                        " |下注金额：".$v['money'].
                        " |盈亏：".$jieguo.
                        " |盈亏金额：".$data['profit'].
                        " |期号：".$v['expect'].
                        " |彩种：".$lottery_info['name'].
                        " |赔率：".$v['peilv'].
                        " |下注时间：".date("Y-m-d H:is",$v['create_time']).
                        " |状态："."结算成功".
                        "\n", FILE_APPEND);
                }else{
                    echo("[".date("Y-m-d H:is",time()).
                        "] 用户：".$member_info['username'].
                        " |玩法：".$v['type'].
                        " |下注金额：".$v['money'].
                        " |期号：".$v['expect'].
                        " |彩种：".$lottery_info['name'].
                        " |赔率：".$v['peilv'].
                        " |下注时间：".date("Y-m-d H:is",$v['create_time']).
                        " |状态："."等待结算中".
                        "\n");
                    file_put_contents("../application/api/log/jiesuan.log","[".date("Y-m-d H:is",time()).
                        "] 用户：".$member_info['username'].
                        " |玩法：".$v['type'].
                        " |下注金额：".$v['money'].
                        " |期号：".$v['expect'].
                        " |彩种：".$lottery_info['name'].
                        " |赔率：".$v['peilv'].
                        " |下注时间：".date("Y-m-d H:is",$v['create_time']).
                        " |状态："."等待结算中".
                        "\n", FILE_APPEND);
                }
            }
        }else{
            echo("等待结算中......"."\n");
        }
    }


    public function updateKj(){
        ini_set('max_execution_time', '0');
        $lottery = new LotteryModel;
        $YulotteryModel = new YulotteryModel;
        $lottery_list = $lottery->lotteryList('all');
        $updateList = $this->where('status',0)->group('expect')->field('expect')->select();

        foreach ($lottery_list as $k=>$v){ //遍历彩种
            $second = $v['rule'] * 60;  //获取彩种的每一期时间
            $now_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
            $now_expect = date('Ymd').$v['rule'].intval($now_time/$second); //获取当前期号
            $LotterykjModel = new LotterykjModel();

//            $now_info = $LotterykjModel->where(['key'=>$v['key'],'expect'=>$now_expect])->find();  //去数据库寻找当前信息是否存在

            foreach ($updateList as $key=>$val){
               if($now_expect != $val['expect']){
                   $now_expect = $val['expect'];
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
                   
                   $now_info = $LotterykjModel->where(['key'=>$v['key'],'expect'=>$val['expect']])->find();  //去数据库寻找当前信息是否存在
           
                   if(empty($now_info)){
                       if($LotterykjModel->insert($data)){
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

//


        }



    }


    public function updateNewKj(){
        ini_set('max_execution_time', '0');
        $lottery = new LotteryModel;
        $YulotteryModel = new YulotteryModel;
        $lottery_list = $lottery->lotteryList('all');
//        $updateList = $this->where('status',0)->group('expect')->field('expect')->select();

        $exceptArr = [];
        foreach ($lottery_list as $k=>$v){ //遍历彩种
            $second = $v['rule'] * 60;  //获取彩种的每一期时间
            $now_time = (date('H')*60*60 + date('i') * 60);  //获取当前整数时间
            $num = intval($now_time/$second);
            $now_expect = date('Ymd').$v['rule'].$num; //获取当前期号
            $LotterykjModel = new LotterykjModel();

            for($a = 0;$a < $num;$a++){
                $exceptArr[] = $now_expect = date('Ymd').$v['rule'].$a;
            }

            foreach ($exceptArr as $key=>$val){
                if($now_expect != $val){
                    $now_expect = $val;
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

                    $now_info = $LotterykjModel->where(['key'=>$v['key'],'expect'=>$val])->find();  //去数据库寻找当前信息是否存在

                    if(empty($now_info)){
                        if($LotterykjModel->insert($data)){
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

//


        }



    }
}