<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/20
 * Time: 11:00
 */

class AccountBindModel extends Model{

    public function userquery($openid)
    {
        $user['open_id'] = $openid;
        $date = $this->field('id,account_id')->where($user)->find();
        return $date;
    }

    public function userbind($uid,$opneid,$username)
    {
            $param['open_name'] = $username;
            $param['account_id'] = $uid;
            $param['open_id'] = $opneid;
            $param['connect_time'] = date('Y-m-d H:i:s',time());
            $param['type'] = 'weixin';
            $id =$this->add($param);
            return $id;
    }
}