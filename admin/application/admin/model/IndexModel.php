<?php

namespace app\admin\model;

use think\Db;
use think\Model;
use think\facade\Session;
use app\admin\model\RoleModel;
use NumberFormatter;

class IndexModel extends Model
{
    protected $table = 'fa_user';

    protected static function init()
    {
    }

    private function countTotalUser($list)
    {
        return count($list);
    }

    private function getTotalBalance($list)
    {
        $ls = array_column($list, 'money');
        $total = array_sum($ls);

        $fmt = new NumberFormatter('zh-CN', NumberFormatter::CURRENCY);
        $response =  $fmt->formatCurrency($total, "CNY");

        return $response;
    }

    private function countNewUsers($list)
    {
        $ls = array_column($list, 'money');
        return count($ls);
    }

    private function countFirstCharge($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['status'] === 0) {
                $count += $index;
            }
        }
        return $count;
    }

    private function countSecondCharge($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['status'] === 1) {
                $count += $index;
            }
        }
        return $count;
    }

    private function countThirdCharge($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['status'] == 2) {
                $count += $index;
            }
        }
        return $count;
    }

    private function countRecharge($list)
    {
        return count($list);
    }

    private function amountRecharge($list)
    {
        $ls = array_column($list, 'money');
        $total = array_sum($ls);
        $fmt = new NumberFormatter('zh-CN', NumberFormatter::CURRENCY);
        $response =  $fmt->formatCurrency($total, "CNY");
        return $response;
    }

    private function countPreciseUser($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['status'] === 'normal') {
                $count += $index;
            }
        }
        return $count;
    }

    private function countBettingUser($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['total_payment'] > 0) {
                $count += $index;
            }
        }
        return $count;
    }

    private function amountWithdrawal($list)
    {
        $amount = 0;
        foreach ($list as $value) {
            if ($value['type'] = 'Withdarwal') {
                $amount += $value['money'];
            }
        }
        $fmt = new NumberFormatter('zh-CN', NumberFormatter::CURRENCY);
        $response =  $fmt->formatCurrency($amount, "CNY");
        return $response;
    }

    private function countWithdraw($list)
    {
        $count = 0;
        $index = 1;
        foreach ($list as $value) {
            if ($value['money'] > 0) {
                $count += $index;
            }
        }
        return $count;
    }

    private function retrieveUsers()
    {
        $sql = "SELECT * FROM fa_user;";
        return (array) $this->buildQuery()->query($sql);
    }

    private function retrieveWithdraws()
    {
        $sql = "SELECT * FROM fa_user;";
        return (array) $this->buildQuery()->query($sql);
    }

    private function retrieveRecharges()
    {
        $sql = "SELECT * FROM fa_user_recharge;";
        return (array) $this->buildQuery()->query($sql);
    }

    private function getFaUserMoneyLogTable()
    {
        $sql = "SELECT * FROM fa_user_money_log;";
        return (array) $this->buildQuery()->query($sql);
    }


    private function getWithdrawOfProfitAndLoss($list_deposit, $list_withdraw)
    {
        $deposit = array_column($list_deposit, 'money');
        $withdraw = array_column($list_withdraw, 'money');

        $l1 = array_sum($deposit);
        $l2 = array_sum($withdraw);
        $total = $l1 - $l2;


        $fmt = new NumberFormatter('zh-CN', NumberFormatter::CURRENCY);
        $response =  $fmt->formatCurrency($total, "CNY");
        return $response;
    }

    private function getOfficalChannelDepositAndWithdraw()
    {
    }

    private function getThirdPartyChannelDepositAndWithdraw()
    {
    }

    private function getManualRecharge()
    {
    }

    public function handleUserData()
    {

        /**
         * Get data from database
         */
        $table_fa_user = $this->retrieveUsers();
        $table_fa_user_withdraw = $this->retrieveWithdraws();
        $table_fa_user_recharge = $this->retrieveRecharges();
        $table_fa_user_money_log = $this->getFaUserMoneyLogTable();

        /**
         * Analysis User data
         */
        $count_total_balance = $this->getTotalBalance($table_fa_user);
        $count_total_user = $this->countTotalUser($table_fa_user);
        $count_new_users = $this->countNewUsers($table_fa_user);

        $count_first_charge = $this->countFirstCharge($table_fa_user_recharge);
        $count_second_charge = $this->countSecondCharge($table_fa_user_recharge);
        $count_third_charge = $this->countThirdCharge($table_fa_user_recharge);
        $count_recharge = $this->countRecharge($table_fa_user_recharge);

        $precise_user = $this->countPreciseUser($table_fa_user);
        $betting_user = $this->countBettingUser($table_fa_user);
        $recharge_count = $this->countRecharge($table_fa_user_recharge);
        $count_withdraw = $this->countWithdraw($table_fa_user_withdraw);


        /**
         * Deposit and withdrawal data
         */



        $withdraw_of_profit_and_loss = $this->getWithdrawOfProfitAndLoss($table_fa_user_recharge, $table_fa_user_withdraw);
        $amount_recharge = $this->amountRecharge($table_fa_user_recharge);
        $amount_withdraw = $this->amountWithdrawal($table_fa_user_money_log);
        $manual_recharge = $this->getManualRecharge();
        $offical_channel_deposit_withdraw = $this->getOfficalChannelDepositAndWithdraw();
        $third_party_channel_deposit_withdraw = $this->getThirdPartyChannelDepositAndWithdraw();

        return [
            'count_total_user' => $count_total_user,
            'count_total_balance' => $count_total_balance,
            'count_new_users' => $count_new_users,
            'count_first_charge' => $count_first_charge,
            'count_second_charge' => $count_second_charge,
            'count_third_charge' => $count_third_charge,
            'count_recharge' =>  $count_recharge,
            'amount_recharge' => $amount_recharge,
            'precise_user' => $precise_user,
            'betting_user' => $betting_user,
            'count_withdraw' => $count_withdraw,

            'withdraw_of_profit_and_loss' => $withdraw_of_profit_and_loss,
            'amount_withdraw' => $amount_withdraw,
            'count_recharge' => $count_recharge,

        ];
    }
}
