<?php
namespace app\api\controller;
use think\facade\Request;
use think\facade\Cache;
use think\facade\Session;
use think\Controller;
// use app\api\model\UserModel;
// use app\api\model\RoleModel;
use app\api\model\VideoclassModel;
use app\api\model\LotteryclassModel;
use app\api\model\LotteryModel;
use app\admin\model\SystemModel;
use app\api\model\MemberModel;
use app\api\model\LotterykjModel;
use app\api\model\YulotteryModel;
use app\api\model\GameModel;
use app\api\model\NoticeModel;
use app\api\model\BannerModel;
use app\api\model\VideoModel;
use app\api\model\XuanfeiaddressModel;
use app\api\model\XuanfeiModel;
class Base extends Controller
{
    protected function initialize()
    {
        
        parent::initialize();
        // $this->UserModel = new UserModel;
        // $this->RoleModel = new RoleModel;
        $this->SystemModel = new SystemModel;
        $this->VideoclassModel = new VideoclassModel;
        $this->LotteryclassModel = new LotteryclassModel;
        $this->LotteryModel = new LotteryModel;
        $this->MemberModel = new MemberModel;
        $this->LotterykjModel = new LotterykjModel;
        $this->YulotteryModel = new YulotteryModel;
        $this->GameModel = new GameModel;
        $this->NoticeModel = new NoticeModel;
        $this->BannerModel = new BannerModel;
        $this->VideoModel = new VideoModel;
        $this->XuanfeiaddressModel = new XuanfeiaddressModel;
        $this->XuanfeiModel = new XuanfeiModel;
        
    }


}
