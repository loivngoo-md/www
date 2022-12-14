<?php
namespace app\admin\controller;
use think\facade\Request;
use think\facade\Cache;
use think\facade\Session;
use think\Controller;
use app\admin\model\UserModel;
use app\admin\model\RoleModel;
use app\admin\model\VideoclassModel;
use app\admin\model\LotteryclassModel;
use app\admin\model\LotteryModel;
use app\admin\model\OssModel;
use app\admin\model\SystemModel;
use app\admin\model\MemberModel;
use app\admin\model\OpenlotteryModel;
use app\admin\model\YulotteryModel;
use app\admin\model\LotterypeilvModel;
use app\admin\model\GameModel;
use app\admin\model\BannerModel;
use app\admin\model\NoticeModel;
use app\admin\model\VideoModel;
use app\admin\model\Member_registerModel;
use app\admin\model\RechargeModel;
use app\admin\model\BanksModel;
use app\admin\model\WithdrawalModel;
use app\admin\model\LogModel;
use app\admin\model\XuanfeiAddressModel;
use app\admin\model\XuanfeilistModel;
use app\admin\model\IndexModel;

class Base extends Controller
{
    protected function initialize()
    {
        parent::initialize();
        $this->IndexModel = new IndexModel;
        $this->UserModel = new UserModel;
        $this->RoleModel = new RoleModel;
        $this->LotteryModel = new LotteryModel;
        $this->OssModel = new OssModel;
        $this->VideoclassModel = new VideoclassModel;
        $this->LotteryclassModel = new LotteryclassModel;
        $this->SystemModel = new SystemModel;
        $this->MemberModel = new MemberModel;
        $this->OpenlotteryModel = new OpenlotteryModel;
        $this->YulotteryModel = new YulotteryModel;
        $this->LotterypeilvModel = new LotterypeilvModel;
        $this->GameModel = new GameModel;
        $this->BannerModel = new BannerModel;
        $this->NoticeModel = new NoticeModel;
        $this->VideoModel = new VideoModel;
        $this->Member_registerModel = new Member_registerModel;
        $this->RechargeModel = new RechargeModel;
        $this->BanksModel = new BanksModel;
        $this->WithdrawalModel = new WithdrawalModel;
        $this->LogModel = new LogModel;
        $this->XuanfeiAddressModel = new XuanfeiAddressModel;
        $this->XuanfeilistModel = new XuanfeilistModel;

//        if($this->request->method() == 'post'){

            $this->LogModel->saveLogData();
//        }


        $authlist = [
            'Xuanfei',
            'Xuanfeilist'
        ];
        if(Request::controller() != 'Login'){
            if(!Session::get('userinfo')){
                $this->redirect(url('/admin/login/index/'));
            }elseif(!Request::isPost()){
                $this->userinfo = $this->UserModel->where(['id'=>Session::get('userinfo')['id'],'status'=>1])->find();
                //?????????????????????
                $role = $this->RoleModel->where(['id'=>$this->userinfo['rid']])->find()?:"????????????";
                $role['role'] = json_decode($role['role'],true);
                $auth = false;
                if(Request::controller() != "Base"){
                    foreach ($role['role'] as $value) {
                        foreach ($value["children"] as $v){
                            $data = explode(":",$v['field']);
                            $controller = $data[0];
                            $action = $data[1];
                            if(array_search(Request::controller().":".Request::action(),$v) || Request::controller()=="Index" && Request::action() == "index"){
                                $auth = true;
                                Session::set('controller',Request::controller());
                                Session::set('action',Request::action());
                                break;
                            }else{
                                 if(Session::get('controller') == Request::controller()){//????????????????????????????????????
                                    // $banfield = ['Video:index']; //??????????????? 
                                    // if(array_search(Request::controller().":".Request::action(),$banfield) !== false){ //?????????????????????????????????
                                    //     if(Request::controller()==Session::get('controller') && Request::action()==Session::get('action')){
                                    //         $auth = true;
                                    //         break;     
                                    //     }else{
                                    //         $auth = false;
                                    //         break; 
                                    //     }
                                    // }else{//?????????????????????
                                        $auth = true;
                                        break; 
                                    // }   
                                 }
                            }
                        }
                    }
                     if(in_array(Request::controller(),$authlist,true)){
                         $auth = true;       
                     }
                }else{
                    $auth = true;
                }
                
                if(!$auth){
                    $this->error("???????????????");
                }                
                $this->assign('role',$role);
                $this->assign('userinfo',$this->userinfo);
            }
        }else{
            if(Session::get('userinfo')){
                $this->redirect(url('/admin/index/index/'));
            } 
          
        }
    }
    public function Logout(){
        Session::clear();
        $this->redirect(url('/admin/login/index/'));
    }  

}
