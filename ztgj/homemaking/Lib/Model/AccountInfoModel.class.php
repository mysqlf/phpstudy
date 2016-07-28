<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/6
 * Time: 16:07
 */

class AccountInfoModel extends Model
{
	public function logining()
	{
		if(session('uid'))
		{
			return session('uid');
		}
		else
		{
			$code = cookie('per');
			if ($code)
			{
				$redis = new Redis();
				//$redis->connect('192.168.8.197', '6379');
				$redis->connect('192.168.2.183', '6379');
				$redis->auth('job5156RedisMaster183');
				$res = $redis->HGetAll($code);
				session('per_user_id',$res['id']);
				$uid = $res['account_id'];
				session('uid',$uid);
				if (!$uid)
				{
					$url = APP_DOMIN.'Chitone-Account-login';
					$openid = session('openid');
					$data = urlencode(json_encode(array('appid'=>APPID,'open_id'=>$openid,'type'=>'weixin','ip'=>get_client_ip())));
					$res = _get($url,$data);
					$code = $res['result']['code'];
					cookie('per',$code,86400);
					$uid = $res['result']['account_id'];
					session('uid',$uid);
					$redis = new Redis();
					//$redis->connect('192.168.8.197', '6379');
					$redis->connect('192.168.2.183', '6379');
					$redis->auth('job5156RedisMaster183');
					$res = $redis->HGetAll($code);
					session('per_user_id',$res['id']);
					return $uid;
				}
				return $uid;
			}
			else
			{
				$url = APP_DOMIN.'Chitone-Account-login';
				$openid = session('openid');
				$data = urlencode(json_encode(array('appid'=>APPID,'open_id'=>$openid,'type'=>'weixin','ip'=>get_client_ip())));
				$res = _get($url,$data);
				$code = $res['result']['code'];
				$uid = $res['result']['account_id'];
				session('uid',$uid);
				cookie('per',$code,86400);
				$redis = new Redis();
				//$redis->connect('192.168.8.197', '6379');
				$redis->connect('192.168.2.183', '6379');
				$redis->auth('job5156RedisMaster183');
				$res = $redis->HGetAll($code);
				session('per_user_id',$res['id']);
				return $uid;
			}
		}
	}
	
	/**
	 * 用户是否已存在
	 * @param string $where 查询条件
	 * return boolean
	 */
	public function accountAssign($where)
	{
		if (!$where) return false;
		$data = $this->field('id AS uid,user_telphone,user_email,user_account,md5_string,user_password')->where($where)->find();
		return $data;
	}
	
	public function getZoning ()
	{
		$zoning = S('ptimeZoning');
		if (!$zoning)
		{
			$zoning = file_get_contents('zoning.php');
			$zoning = json_decode($zoning,true);
			S('ptimeZoning',$zoning,0);
		}
		return $zoning;
	}



}