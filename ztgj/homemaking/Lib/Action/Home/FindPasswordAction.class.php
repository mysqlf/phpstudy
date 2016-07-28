<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/22
 * Time: 16:40
 */

class FindPasswordAction extends Action
{
	private $_data = array();//接收对象属性
	public function __set($name , $value)
	{
		$this->_data[$name] = $value;
	}
	public function __get($name)
	{
		if (array_key_exists($name, $this->_data))
		{
			return $this->_data[$name];
		}
		return null;
	}
	public function _initialize ()
	{
		$this->_redirect	= I('request.redirect' , '');
		$this->_account	 = session('FpwdAccount');
		$this->_UserName	 = session('UserName');
		if (ACTION_NAME!=='getpassword' && !$this->_account && ACTION_NAME!=='suremail')
		{
			if(IS_AJAX)
			{
				$this->_ajaxJson(999998,'账号参数错误,请重新输入账号',array('url'=>'/FindPassword-getpassword'));
			}
			$this->error('账号参数错误,请重新输入账号','/FindPassword-getpassword');
			exit();
		}
		$this->assign('redirect' , $this->_redirect);
		$this->assign('account' , $this->_account);
	}

	public function __destruct()
	{
		//echo 4;
	}
	/**
	 * 返回ajax数据
	 * @param  [int]	$status  [状态码]
	 * @param  [string] $msg	 [文字描述]
	 * @param  [array]  $data	[data数据]
	 * @return [null]
	 */
	private function _ajaxJson ($status , $msg , $data = array())
	{
		$info = array();
		$info['status'] = $status;
		$info['msg']	= $msg;
		$info['data']   = $data;
		$this->ajaxReturn ($info);
	}
	public function getpassword()
	{
		if (IS_POST)
		{
			$date['user_account'] = I('post.account' , '');
			if( mobileFormat($date['user_account']) || emailFormat($date['user_account']))
			{
				$date['user_name'] = I('post.resumeName');
				if(empty( $date['user_name']))
				{
					$this->_ajaxJson(900000 , '未验证手机和邮箱' , array());
				}
			}
			$url =APP_DOMIN.'Chitone-Account-accountValidata';
			$res = urlencode(json_encode($date));
			$result = _get($url,$res);
			if ($result['status'] == 900000)
			{
				$this->_ajaxJson($result['status'] , $result['msg'] , array());
			}
			else
			{
				if(count($result['result']) > 1)
				{
					$this->_ajaxJson(800000 , '账号重复' , array());
				}
				if ($result['result'][0]['mobile'])
				{
					session('FpwdAccount',  $date['user_account']);
					if(!empty($date['user_name']))session('UserName',  $date['user_name']);
					$this->_ajaxJson($result['status'] , $result['msg'] , array('url'=>'/FindPassword-phoneFindPwd?redirect='.$this->_redirect));
				}
				else if ($result['result'][0]['email'])
				{
					session('FpwdAccount' , $date['user_account']);
					if(!empty($date['user_name']))session('UserName',  $date['user_name']);
					$this->_ajaxJson($result['status'] , $result['msg'] , array('url'=>'/FindPassword-emailFindPwd?redirect='.$this->_redirect));
				}
				else
				{
					$this->_ajaxJson(999998 , '未验证手机和邮箱' , array());
				}
			}
		}
		else
		{
			$this->display('Account/find_username');
		}

	}
	/**
	 * 找回密码
	 */
	public function emailFindPwd()
	{
		if (!$this->_account)
		{
			$this->error('参数错误','/FindPassword-getpassword');
			exit();
		}
		$date['user_account'] = $this->_account;
		if(session('UserName'))  $date['user_name']  =  $this->_UserName ;
		$url =APP_DOMIN.'Chitone-Account-accountValidata';
		$res = urlencode(json_encode($date));
		$result = _get($url,$res);
		$str = $result['result'][0]['email'];
		if (!$str)
		{
			$this->error('您未绑定邮箱，无法用邮箱找回。','/FindPassword-getpassword');
			exit();
		}
		if(IS_AJAX)
		{
			if ($str) $content =  $this->sendmail($str);
			$this->_ajaxJson(0 , '' , $content);

		}
		list($name, $server) = explode('@', $str);
		$emailurl = 'http://mail.'.$server;
		$this->assign('emailurl' , $emailurl);
		$this->assign('MobileFind' ,  $result['result'][0]['mobile']);
		$this->assign('EmailFind' ,  $result['result'][0]['email']);
		$this->display('Account/email');
	}
	public function phoneFindPwd()
	{
		if (!$this->_account)
		{
			$this->error('参数错误','/FindPassword-getpassword');
			exit();
		}
		//倒计时
		if (session('time'))
		{
			$time = session('time');
			$newtime = time();
			$losetime = $newtime - $time;
			$losetime = 60 - $losetime;
			if ($losetime > 1 && $losetime < 60) {
				$this->assign('time', $losetime);
			}
		}
		$date['user_account'] = $this->_account;
		if(session('UserName'))   $date['user_name']   =  $this->_UserName ;
		$url =APP_DOMIN.'Chitone-Account-accountValidata';
		$res = urlencode(json_encode($date));
		$result = _get($url,$res);
		$str = $result['result'][0]['mobile'];
		if (!$str)
		{
			$this->error('您未绑定手机，无法用手机找回。','/FindPassword-getpassword');
			exit();
		}
		$str = substr($str,0,3).'****'.substr($str,7,4);
		$this->assign('str',$str);
		$this->assign('MobileFind' , $result['result'][0]['mobile']);
		$this->assign('EmailFind' , $result['result'][0]['email']);
		$this->display('Account/find_verify');
	}
	public function sureaccount()
	{
		$code = I('post.verifytext');
		$codeed = session('code');
		if($code == $codeed )
		{
//			$data['user_account'] = $this->_account;
//			$data['ip'] = get_client_ip();
//			$data['user_type'] = 0;
//			$res = urlencode(json_encode($data));
//			$result = _get(APP_DOMIN.'Chitone-Account-accountValidata',$res);
//			if ($result['result']['per_user_id'])
//			{
//				$accountData['per_user_id'] = $result['result']['per_user_id'];
//				$accountData['field'] = 'user_name';
//				$res = urlencode(json_encode($accountData));
//				$result = _get(APP_DOMIN.'Chitone-AccountUser-userInfo',$res);
//				if ($result['result']['result'][0]['user_name'] && $result['result']['result'][0]['user_name']==$uname)
//				{
//					$this->_ajaxJson(0,'成功',array('url'=>'/FindPassword-set_pw?redirect='.$this->_redirect));
//				}
//				else if(!$result['result']['result'][0]['user_name'])
//				{
//					$this->_ajaxJson(900000,'未找到数据',array());
//				}
//				else
//				{
//					$this->_ajaxJson(999998,'简历姓名错误',array());
//				}
//			}
			if(session('openid'))
			{
				$phone['open_id'] = session('openid');
				if( session('mobile'))
				{
					$phone['user_telphone'] = session('mobile');
					$phone['type'] ='weixin';
					$url =APP_DOMIN;
					$url = $url.'Chitone-Account-bindMobile';
					$data =  urlencode(json_encode($phone));
					$res = _get($url,$data);
				}
			}
			$this->_ajaxJson(0,'成功',array('url'=>'/FindPassword-set_pw?redirect='.$this->_redirect));
		}
		else
		{
			$this->_ajaxJson(999998,'验证码错误',array());
		}
	}
	public function set_pw()
	{
		session('code',null);
		$this->display('Account/set_pw');
	}
	//发送邮件
	private function sendmail($email)
	{
		$usermail = $email;
		$urlAccount = $this->_account;
		$account = $urlAccount.'email';
		if (S($account))
		{
			$emailsign = 1;
			$content = "一个小时内只能发送一次重置密码邮件，请到";
			$this->assign('content',$content);
			$this->assign('emailsign',$emailsign);
		}
		else {
			$code = substr(uniqid(rand()), -6);
			$token = md5($account . ':' . $code);
			S($account, $code, 3600);
			$time = time();
			$appurl = DOMAIN.'FindPassword-suremail-code-' . $urlAccount . '-token-' . $token.'-user-'. $this->_UserName;
			$prompt = '尊敬的用户您好，请您在一个小时内点击链接，如有超时或者错误请重新获取'."\t";
			$url = $prompt.'<a href='.DOMAIN.'FindPassword-suremail-code-' . $urlAccount . '-token-' . $token .'-user-'. $this->_UserName.'>' . $appurl . '</a>';
			
			think_send_mail($usermail, '智通人才', '找回密码', $url);
			$content = "重置密码邮件已发送到";
			$this->assign('content',$content);
		}
		return array('emailsign'=>$emailsign,'content'=>$content);
	}

	public function suremail()
	{
		$account = I('get.code');
		$tokened = I('get.token');
		$userName = I('get.user');
		$code = S($account.'email');
		if (!empty($code))
		{
			$tokening = md5($account.'email:'.$code);
			if ($tokened == $tokening)
			{
				session('UserName', $userName);
				session('FpwdAccount',$account);
				$this->display('Account/set_pw');
			}
			else
			{
				$content = '对不起，您的链接超过有效时间或已失效，请重新获取！';
				$this->assign('content',$content);
				$this->display('Account/error');
			}
		}
		else
		{
			//$content = '对不起您已超时！！！';
			$content = '对不起，您的链接超过有效时间或已失效，请重新获取！';			
			$this->assign('content',$content);
			$this->display('Account/error');
		}
	}

	public function setpassword()
	{
		$url = APP_DOMIN.'Chitone-Account-accountValidata';
		if ($this->_account)
		{
			$date['user_account'] = $this->_account;
		}
		if($this->_UserName)
		{
			$date['user_name'] = urldecode($this->_UserName);
		}
		$res = urlencode(json_encode($date));
		$result = _get($url,$res);
		if(count($result['result']) > 1)
		{
			$this->_ajaxJson(-1 , '失败' , array());
		}
		$date = array();
		$url = APP_DOMIN.'Chitone-Account-verifyAccount';
		if ($result['result'][0]['account'])
		{
			$date['user_account'] = $result['result'][0]['account'];
		}
		$date['ip'] = get_client_ip();
		$date['user_type'] = 0;
		$res = urlencode(json_encode($date));
		$result = _get($url,$res);

		$account_id = $result['result']['account_id'];
		$per_user_id = $result['result']['per_user_id'];
		$password = I('post.password');
		if (!empty($account_id))
		{
			$arr['account_id'] = (int)$account_id;
		}
		if (!empty($per_user_id))
		{
			$arr['per_user_id'] = (int)$per_user_id;
		}
		if (!empty($password))
		{
			$arr['password'] = $password;
		}
		$jsondate = urlencode(json_encode($arr));
		$appurl = APP_DOMIN.'Chitone-AccountUser-modifyUser';
		$appresult = _get($appurl,$jsondate);
		if($appresult['status'] == 0)
		{
			S($this->_account.'email',null);
			session('UserName', null);
			session('FpwdAccount',null);
			$this->_ajaxJson(0,'成功',array('url'=>$this->_redirect ? 'Account-login?redirect='.$this->_redirect : 'Account-login'));
		}
		else
		{
			$this->_ajaxJson(-1 , '失败' , array());
		}
	}
	/**
	  * changePassword
	  * 更改密码
	  * @access public
	  * @return void
	  * @date 2015-05-21
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function changePassword(){
		if(IS_POST){
			$userinfo['user_account'] = I('post.account');
			$oldPassword = I('post.oldPassword');
			$newPassword = I('post.newPassword');
			$ensurePassword = I('post.ensurePassword');

			$userinfo['user_password'] = $oldPassword;

			if($newPassword != $ensurePassword){
				$this->ajaxReturn('','新密码输入不一致！',10004);
			}

			$userinfo['ip'] = get_client_ip();
			if (session('openid')) {
				$userinfo['open_id'] = session('openid');
				$userinfo['type'] = 'weixin';
				//$userinfo['account_from'] = '3001';
			}
			$data = urlencode(json_encode($userinfo));
			$url = APP_DOMIN.'Chitone-Account-login';
			$res = _get($url, $data);

			$uid = $res['result']['account_id'];

			$code = $res['result']['code'];
			$redis = initRedis();
			$redisres = $redis->HGetAll($code);

			$per_user_id = $redisres['id'];

			if($uid && $per_user_id){
				$arr['account_id'] = (int)$uid;
				$arr['per_user_id'] = (int)$per_user_id;
				$arr['password'] = $newPassword;

				$zt = D('ZtApi');
				$result = $zt->modifyUser($arr);
				if($result['status'] == 0){
					$this->ajaxReturn(array(),'修改成功！',10001);
				}else{
					$this->ajaxReturn('','修改失败！',10002);
				}
			}else{
				$this->ajaxReturn('','旧密码错误！',10003);
			}
		}
		$u = I('get.u');
		$p = I('get.p');
		if($u && $p){
			$this->assign('u',$u);
			$this->assign('p',$p);
		}
		$this->display('Account/changePassword');
	}
}