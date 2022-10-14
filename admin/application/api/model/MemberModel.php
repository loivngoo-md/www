<?php
namespace app\api\model;

use think\Model;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;
use app\admin\model\UserModel;
use think\Db;
class MemberModel extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'member';
    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    public function uploadHeaderImg($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $data['update_time'] = time();
            $data['header_img'] = $post['header_img'];
            $this->save($data,['id'=>$id]) ? json_exit_Base64(200,'更换头像成功！') : json_exit_Base64(401,'更新头像失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }
    public function setBank($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if(empty($info['name'])){
                json_exit_Base64(401,'请输入真实姓名后再填写银行卡！');
            }
            if(empty($info['paypassword'])){
                json_exit_Base64(401,'请输入提现密码后再填写银行卡！');
            }    
            $BankModel = new BankModel;
            if($BankModel->where(['uid'=>$info['id']])->find()){
                json_exit_Base64(401,'已设置过银行卡！');
            }
            $data['uid'] = $info['id'];
            $data['bankid'] = $post['bankid'];
            $data['bankinfo'] = $post['bank'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            $BankModel->insert($data) ? json_exit_Base64(200,'设置银行卡成功！') : json_exit_Base64(401,'设置银行卡失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        }  
    }
    public function setPayPassword($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $data['update_time'] = time();
            $data['paypassword'] = md5($post['paypassword']);
            $this->save($data,['id'=>$id]) ? json_exit_Base64(200,'设置提现密码成功！') : json_exit_Base64(401,'设置提现密码失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }  
    public function setLoginPassword($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if(md5($post['old_password']) !== $info['password']){
            json_exit_Base64(401,"旧密码错误");
        }
        if($info){
            $data['update_time'] = time();
            $data['password'] = md5($post['new_password']);
            $this->save($data,['id'=>$id]) ? json_exit_Base64(200,'修改密码成功！') : json_exit_Base64(401,'修改密码失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }     
    public function setName($post){
        
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        
        if(!isChineseName($post['name'])){
            
             json_exit_Base64(401,'请输入真实姓名！');
        }
        if($info){
            $data['update_time'] = time();
            $data['name'] = $post['name'];
            $this->save($data,['id'=>$id]) ? json_exit_Base64(200,'设置姓名成功！') : json_exit_Base64(401,'设置姓名失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }
    public function setSex($post){
        
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $data['update_time'] = time();
            $data['sex'] = $post['sex'];
            $this->save($data,['id'=>$id]) ? json_exit_Base64(200,'设置性别成功！') : json_exit_Base64(401,'设置性别失败！');
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }  
    public function setUserWirhdraw($post){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if ($info['amount_code'] > 0){
                json_exit_Base64(401,'打码量不足');
            }

            $role = $this->funGetUserWithdrawRole($info['id']);
            if($post['money'] < $role['min']){
                json_exit_Base64(401,'提现金额不能少于：'. $role['min']);
            }
            if($post['money'] > $role['max']){
                json_exit_Base64(401,'提现金额不能大于：'. $role['max']);
            }
            $WithdrawModel = new WithdrawModel;
            $count = $WithdrawModel->where(['mid'=>$info['id']])->whereTime('create_time', 'between', [strtotime(date("Y-m-d")), strtotime(date("Y-m-d",strtotime("+1 day")))])->count();
            if($count == $role['num']){
                json_exit_Base64(401,'当日提现次数已用完');
            }
            $usermoney = $info['money'] - $post['money'];
            if($usermoney < 0){
                json_exit_Base64(401,'余额不足！');
            }
            $data['mid'] = $info['id'];
            $data['status'] = 1;
            $data['money'] = $post['money'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            if($this->save(['money'=>$usermoney],['id'=>$info['id']])){
                $WithdrawModel->save($data) ? json_exit_Base64(200,'提现成功！') : json_exit_Base64(401,'提现失败！');
            }else{
                json_exit_Base64(401,'扣款失败！');
            }
            
        }else{
            json_exit_Base64(401,"鉴权错误");
        } 
    }    
    public function funGetUserWithdrawRole($mid){
        $info = $this->where(['id'=>$mid])->find();
        if($info['num'] && $info['min'] && $info['max'] ){
            $data = [
                'num'=>$info['num'],
                'min'=>$info['min'],
                'max'=>$info['max'],
                ];
        }else{
            $SystemModel = new SystemModel;
            $sys_info = $SystemModel->getConfig("base");
            $data = [
                'num'=>$sys_info['withraw_num'],
                'min'=>$sys_info['withraw_min'],
                'max'=>$sys_info['withraw_max'],
                ];
        }
        return $data;
    }
    public function getUserGameList(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $GameModel= new GameModel;
            $LotteryModel= new LotteryModel;
            $LotterykjModel = new LotterykjModel;
            $list = $GameModel->order('id','desc')->where(['mid'=>$info['id']])->select();

            foreach ($list as $k=>$v){
                $v['lottery'] = $LotteryModel->where(['id'=>$v['lid']])->find();
                $data = $LotterykjModel->where(['expect'=>$v['expect'],'key'=>$v['lottery']['key']])->find();
                if(!$data && $v['status']==1){
                    unset($list[$k]);
                }
            }
            foreach ($list as $k=>&$v){
                $v['lottery'] = $LotteryModel->where(['id'=>$v['lid']])->find();
                if($v['status'] === 0){
                    $v['status_text'] = "结算中";
                    $v['isAdopt'] = false;
                }elseif($v['status'] === 1){
                    $v['status_text'] = "已结算";
                    $v['isAdopt'] = true;
                    $data = $LotterykjModel->where(['expect'=>$v['expect'],'key'=>$v['lottery']['key']])->find();
                    $opencode = explode(",",$data['opencode']);
                    $opencode[0] = (int)$opencode[0];
                    $opencode[1] = (int)$opencode[1];
                    $opencode[2] = (int)$opencode[2];
                    $v['opencode'] = $opencode;
                }
                if($v['is_win'] === 0){
                    $v['win_text'] = "结算中";
                }else if($v['is_win'] === 1){
                    $v['win_text'] = "盈利";
                }else if($v['is_win'] === 2){
                    $v['win_text'] = "亏损";
                }
                if($v['update_time'] == 0){
                    $v['update_time'] = "待结算";
                }else{
                    $v['update_time'] = date("Y-m-d H:i:s",$v['update_time']);
                }
                $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            }
                       
            $data = [];
            $i=0;
            foreach($list->toArray() as $key=>$value){
                if(isset($data[$value['expect']])){
                    $data[$value['expect']]['id'] = $value['id'];
                    $data[$value['expect']]['count'] +=1;
                    $data[$value['expect']]['money'] +=$value['money'];
                    $data[$value['expect']]['data'][]  = $value;
                }else{
                    $data[$value['expect']]['id'] = $value['id'];
                    $data[$value['expect']]['status']=1;
                    $data[$value['expect']]['ico'] =  $value['lottery']['ico'];
                    $data[$value['expect']]['name'] =  $value['lottery']['name'];
                    $data[$value['expect']]['expect']  = $value['expect'];
                    $data[$value['expect']]['count'] =1;
                    $data[$value['expect']]['money'] =$value['money'];
                    $data[$value['expect']]['data'][]  = $value;
                    if(isset($value['opencode'])) {
                        $data[$value['expect']]['opencode'] = $value['opencode'];
                    }else{
                        $data[$value['expect']]['status']=0;
                    }
                }
                $data[$value['expect']]['update_time']  = $value['update_time'];
                $data[$value['expect']]['create_time']  = $value['create_time'];
            }
// var_dump($data);die;
            $data = $this->arraySort($data, 'id', SORT_DESC);
            //echo json_encode(['code'=>200,"message"=>"获取信息成功！","data"=>$data]);die;
            json_exit_Base64(200,"获取信息成功！",$data);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }
    function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
    public function getUserWithdrawList(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $WithdrawModel = new WithdrawModel;
            $list = $WithdrawModel->order('id','desc')->where(['mid'=>$info['id']])->select();
            foreach ($list as $k=>&$v){
                if($v['status'] === 1){
                    $v['status_text'] = "待审核";
                    $v['isAdopt'] = false;
                }elseif($v['status'] === 2){
                    $v['status_text'] = "审核成功";
                    $v['isAdopt'] = true;
                }elseif($v['status'] === 3){
                    $v['status_text'] = "审核退回";
                    $v['isAdopt'] = false;
                }
                if($v['update_time'] == $v['create_time']){
                    $v['update_time'] = "待审核";
                }else{
                    $v['update_time'] = date("Y-m-d H:i:s",$v['update_time']);
                }
                
                $v['create_time'] = date("Y-m-d H:i:s",$v['create_time']);
            }
            json_exit_Base64(200,"获取信息成功！",$list);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }        
    }    
    public function getUserWithdrawRole(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if($info['num'] && $info['min'] && $info['max'] ){
                $data = [
                    'num'=>$info['num'],
                    'min'=>$info['min'],
                    'max'=>$info['max'],
                    ];
            }else{
                $SystemModel = new SystemModel;
                $sys_info = $SystemModel->getConfig("base");
                $data = [
                    'num'=>$sys_info['withraw_num'],
                    'min'=>$sys_info['withraw_min'],
                    'max'=>$sys_info['withraw_max'],
                    ];
            }
            json_exit_Base64(200,"获取信息成功！",$data);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }        
    }
    public function getPersonalreport(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $GameModel = new GameModel;
            $WithdrawModel = new WithdrawModel;
            $RechargeModel = new RechargeModel;
            $win_money = $GameModel->where(['mid'=>$info['id'],'is_win'=>1])->sum('profit');
            $play_money = $GameModel->where(['mid'=>$info['id']])->sum('money');
            $Withdraw = $WithdrawModel->where(['mid'=>$info['id'],'status'=>2])->sum('money');
            $recharge = $RechargeModel->where(['mid'=>$info['id']])->sum('money');
            $data = [
                'win_money'=>$win_money,
                'play_money'=>$play_money,
                'withdrawal'=>$Withdraw,
                'recharge'=>$recharge
                ];
            json_exit_Base64(200,"获取信息成功！",$data);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }        
    }
    public function getUsesBankInfo(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $BankModel = new BankModel;
            $bankinfo = $BankModel->where(['uid'=>$info['id']])->find();
            if($bankinfo){
                $bankdata =[
                    'is_bank'=>true,
                    'info'=>$bankinfo
                    ];
            }else{
                $bankdata =[
                    'is_bank'=>false
                ];
            }
            json_exit_Base64(200,"获取信息成功！",$bankdata);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }    
    public function getUserInfo(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            $info['sex'] = (string)$info['sex'];
            if ($info['status'] != 1){
                $info['statusMessage'] = '网络异常，请重新登录';
            }
            json_exit_Base64(200,"获取信息成功！",$info);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }

    public function getUserIsOnline(){
        $id = base64_decode(Request::header('token'));
        $info = $this->where(['id'=>$id])->find();
        if($info){
            if ($info['is_online'] != 1){
                $info['onlineMessage'] = '网络异常，请重新登录';
            }
            json_exit_Base64(200,"获取信息成功！",$info);
        }else{
            json_exit_Base64(401,"鉴权错误");
        }
    }

    public function login($post){
        $info = $this->where(['username'=>$post['username']])->find();
        if(empty($info)){
            json_exit_Base64(401,"用户不存在！");
        }else{
            if($info['password'] != md5($post['password'])){
                json_exit_Base64(401,"密码错误！");
            }else{
                if($info['status'] != 1){
                    json_exit_Base64(401, "账号被禁用！"); 
                }
                $data['last_time'] = time();
                $data['ip'] = getIP();
                $data['is_online'] = 1;
                $this->save($data,['id'=>$info['id']]);
                json_exit_Base64(200,"登录成功！",$info);
            }
        }
    }

    public function register($post){
       if (preg_match("/[\x7f-\xff]/", $post['username'])) {
            json_exit_Base64(401,"用户名不能存在中文！");
       }  
       if(mb_strlen($post['username'],'UTF8') < 6 || mb_strlen($post['username'],'UTF8') > 12){
            json_exit_Base64(401,"用户名位数错误！");
       }
       if(mb_strlen($post['password'],'UTF8') < 6 || mb_strlen($post['password'],'UTF8') > 12){
            json_exit_Base64(401,"密码位数错误！");
       }       
       if(empty($post['code'])){
          json_exit_Base64(401,"邀请码不能为空！"); 
       }else{
           $UserModel = new UserModel;
           $code_info = $UserModel->where(['code'=>$post['code']])->find();
           if(empty($code_info) || intval($post['code']) == 0){
               json_exit_Base64(401,"邀请码不存在！"); 
           }else{
               
           }
       }
        $post['username'] = trim($post['username']);
        $post['password'] = trim($post['password']);
       $info = $this->where(['username'=>$post['username']])->find();
       Db::startTrans();
       if(empty($info)){
            $data['username'] = $post['username'];
            $data['password'] = md5($post['password']);
            $data['money'] = 0;
            $data['status'] = 1;
            $data['uid'] = $code_info['id'];
            $data['header_img'] = "https://zxbuk.oss-cn-hongkong.aliyuncs.com/images/avatar/avatar".rand(1,185).".png";
            $data['ip'] = getIP();
            $data['create_time'] = time();
            $data['last_time'] = time();
            $data['update_time'] = time();

            if($this->insert($data)){
               $newinfo = $this->where(['username'=>$post['username']])->find();
                $Member_registerModel = new Member_registerModel;
                $register_data['mid'] = $newinfo['id'];
                $register_data['code'] = $post['code'];
                $register_data['uid'] = $code_info['id'];
                $register_data['ip'] = getIP();
                $register_data['create_time'] = time();
                $register_data['update_time'] = time();
                
                
                $status = $Member_registerModel->save($register_data) ;
                if($status){
                     Db::commit();  
                    json_exit_Base64(200,'注册成功！',$newinfo['id']);
                }else{
                    Db::rollback();
                    json_exit_Base64(401,'注册失败！');
                }
                
             
                
            }else{
                Db::rollback();
                json_exit_Base64(401,'注册失败！');
            }
       }else {
                Db::rollback();
            json_exit_Base64(401,"用户已存在");
       }
    }
}