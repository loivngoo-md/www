<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;
use app\admin\controller\Base;

class Index extends Base
{
    public function index()
    {

        $list = $this->IndexModel->handleUserData();
        $count_total_user = $list['count_total_user'];
        $count_total_balance = $list['count_total_balance'];
        $count_new_users = $list['count_new_users'];
        $count_first_charge = $list['count_first_charge'];
        $count_second_charge = $list['count_second_charge'];
        $count_third_charge = $list['count_third_charge'];
        $count_recharge = $list['count_recharge'];
        $count_withdraw = $list['count_withdraw'];
        $withdraw_of_profit_and_loss = $list['withdraw_of_profit_and_loss'];
        $amount_recharge = $list['amount_recharge'];
        $amount_withdraw = $list['amount_withdraw'];
        $precise_user = $list['precise_user'];
        $betting_user = $list['betting_user'];

        // $this->assign('total_users', $total_users);
        //  $this->assign('total_balance', $total_balance);
        return $this->fetch('index', [
            'count_total_user' => $count_total_user,
            'count_total_balance' => $count_total_balance,
            'count_new_users' => $count_new_users,
            'count_first_charge' => $count_first_charge,
            'count_second_charge' => $count_second_charge,
            'count_third_charge' => $count_third_charge,
            'count_recharge' =>  $count_recharge,
            'count_withdraw' => $count_withdraw,
            'withdraw_of_profit_and_loss' => $withdraw_of_profit_and_loss,
            'amount_recharge' => $amount_recharge,
            'precise_user' => $precise_user,
            'betting_user' => $betting_user,


            'count_recharge' => $count_recharge,
            'amount_withdraw' => $amount_withdraw,
        ]);
    }
}
