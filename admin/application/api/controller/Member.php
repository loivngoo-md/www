<?php
namespace app\api\controller;
use think\Request;
use think\Controller;
use app\api\controller\Base;
class Member extends Base
{
    public function uploadHeaderImg(){
        $post = $this->request->param();
        $this->MemberModel->uploadHeaderImg($post);
    }
    public function setUserWirhdraw(){
        $post = $this->request->param();
        $this->MemberModel->setUserWirhdraw($post);

    }
    public function setName(){
        $post = $this->request->param();
        $this->MemberModel->setName($post);        
    }
    public function setLoginPassword(){
        $post = $this->request->param();
        $this->MemberModel->setLoginPassword($post);        
    }  
    public function setBank(){
        $post = $this->request->param();
        $this->MemberModel->setBank($post);        
    }      
    public function setPayPassword(){
        $post = $this->request->param();
        $this->MemberModel->setPayPassword($post);        
    }    
    public function setSex(){
        $post = $this->request->param();
        $this->MemberModel->setSex($post);        
    }    
    public function getUserInfo(){
        $this->MemberModel->getUserInfo();
    }
    public function getUserIsOnline(){
        $this->MemberModel->getUserIsOnline();
    }
    public function getUserBankInfo(){
        $this->MemberModel->getUsesBankInfo();
    }
    public function getUserWithdrawList(){
        $this->MemberModel->getUserWithdrawList();
    }
    public function getPersonalreport(){
        $this->MemberModel->getPersonalreport();
    }
    public function getUserWithdrawRole(){
        $this->MemberModel->getUserWithdrawRole();
    }
    public function getUserGameList(){
        $this->MemberModel->getUserGameList();
    }    
    public function doLogin()
    {
        $post = $this->request->param();
        $this->MemberModel->login($post);
    }
    public function doRegister(){
        $post = $this->request->param();
        $this->MemberModel->register($post);
    }

}
