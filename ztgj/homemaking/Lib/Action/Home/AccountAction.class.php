<?php
class AccountAction extends Action
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
//		session(null);
//		cookie('per',null);
		if(session('openid'))
		{
			$this->_openid = session('openid');
		}
		else
		{
			$code = I('get.code',0);
			$this->_openid = _openid($code);
			session('openid',$this->_openid);
		}
		//$this->_redirect	= I('request.redirect' , '')?I('request.redirect' , ''):I('request.str' , '');
		$this->_redirect = session('requirement')?'Homemaking-requirementSubmit4Login':((I('request.redirect' , '')?I('request.redirect' , ''):I('request.str' , ''))?:'Homemaking-index-t-motherBabyQA-v-'.time());
		$this->_aid		 = D('AccountInfo')->logining();
		$this->_per_user_id = session('per_user_id');
		$this->_code		= cookie('per');
		$this->_account	 = I('post.account' , '');
		$this->assign('redirect' , $this->_redirect);//dump($this->_aid);
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
	//用户注册
	public function reg()
	{
		if ($this->_aid)
		{
			if (IS_AJAX)
			{
				$this->_ajaxJson(0,'',array('url'=>$this->_redirect));
			}
			else
			{
				//$this->redirect($this->_redirect);
				redirect($this->_redirect);
			}
		}
		if (IS_POST)
		{
			$user = array ();
			$mothod = I('post.keepReg' , 0);
			$userinfo = userinfo($this->_openid);
			$username = $userinfo['nickname'];
			$headimgurl = $userinfo['headimgurl'];
			$user['open_id'] = $this->_openid;
			$user['user_account']  = I('post.phoneVal' , '');
			$user['user_password'] = I('post.password' , '');
			$user['ip'] = get_client_ip();
			$user['open_name'] = $username;
			$user['type'] ='weixin';
			$user['role_type'] = '0';
			$user['account_from'] = 3001;
			$user['appid'] = APPID;
			$data = urlencode(json_encode($user));
			if ($mothod === '1')
			{
				$url = APP_DOMIN.'Chitone-Account-reReg';
			}
			else
			{
				$url = APP_DOMIN.'Chitone-Account-reg';
			}
			$res =_get($url,$data);
			if($res['result']['account_id'])
			{
				$this->_saveLoginData($res);
				$this->getphoto($this->_aid,$headimgurl);
				$this->_regCount();
				$resume['per_user_id'] = $this->_per_user_id;
				$resume['resume_type'] = 4;
				$resume['resume_name'] = "我的微名片";
				$resumedata = urlencode(json_encode($resume));
				$resumeurl = APP_DOMIN.'Chitone-AccountUser-modifyResume';
				$resumeresult = _get($resumeurl,$resumedata);

				//$this->_ajaxJson ($res['status'] , $res['msg'] , array('url'=>$this->_redirect ? $this->_redirect : 'Ptime-card'));
				//$this->_ajaxJson ($res['status'] , $res['msg'] , array('url'=>'Ptime-card?redirect=' .$this->_redirect));
				$this->_ajaxJson ($res['status'] , $res['msg'] , array('url'=>$this->_redirect));
			}
			else
			{
				$this->_ajaxJson($res['status'] , $res['msg'] , array());
			}
		}
		else
		{
			$time = session('time');
			$newtime = time();
			$losetime = $newtime - $time;
			$losetime = 60 - $losetime;
			if ($losetime > 1 && $losetime < 60)
			{
				$this->assign('time', $losetime);
			}
			$this->display('Account:register');
		}
	}
	public function login ()
	{
		if ($this->_aid)
		{
			if (IS_AJAX)
			{
				$this->_ajaxJson(0,'',array('url'=>$this->_redirect));
			}
			else
			{
				//$this->redirect($this->_redirect);
				redirect($this->_redirect);
			}
		}
		//接收数据
		if (IS_POST)
		{
			$userinfo = array();
			$userinfo['user_account'] =I('post.account');
			$userinfo['user_password'] = I('post.password');
			$userinfo['ip'] = get_client_ip();
			$userinfo['open_id'] = session('openid');
			$userinfo['type'] = 'weixin';
			$userinfo['appid'] = APPID;
			$user = userinfo($userinfo['open_id']);
			$username = $user['nickname'];
			$headimgurl = $user['headimgurl'];
			$userinfo['open_name'] = $username;
			$data = urlencode(json_encode($userinfo));
			$url = APP_DOMIN.'Chitone-Account-login';
			$res = _get($url,$data);
			if ($res['result']['account_id'])
			{
				$this->_saveLoginData($res);
				$this->getphoto($this->_aid,$headimgurl);
				if ($this->_redirect&&strpos($this->_redirect,'Ucenter')===false)
				{
					$url = APP_DOMIN.'Chitone-Account-isBindMobile';
					$userinfo['account_id'] = $this->_aid;
					$userinfo['type'] = 'weixin';
					$data = urlencode(json_encode($userinfo));
					$res = _get($url,$data);
					$status = $res['status'];
					if ($status)
					{
						/*$archives_person = D('ArchivesPerson');
						$result = $archives_person->where('account_id = '.$this->_aid)->find();
						$archives_person_resume = D('ArchivesPersonResume');
						$res = $archives_person_resume->where('account_id = '.$result['id'].' and id='.$result['res_id'])->find();
						$result1 = json_decode ( $res['education_info'], true );
						if(isset($result) && $result['user_name']&&$result['gender']&&$result['birthday']&&$result['degree']&&$result['location'])
							{
							if(isset($result1)&& $result1[0]['begin']&&$result1[0]['schoolName'])
							{
								if($result['degree']!= 1&&$result['degree']!= 2){
									if(!$result1[0]['speciality']){
										$this->_ajaxJson(0 ,'成功',array('url'=>'Ptime-card?xianshi=1&redirect=' . $this->_redirect));
									}
								}
								$this->_ajaxJson(0,'成功',array('url'=>$this->_redirect));
							}
							else
							{
								$this->_ajaxJson(0,'成功',array('url'=>'Ptime-card?xianshi=1&redirect=' . $this->_redirect));
							}
						}
						else
						{
							$this->_ajaxJson(0,'成功',array('url'=>'Ptime-card?xianshi=1&redirect=' . $this->_redirect));
						}*/
						$this->_ajaxJson(0,'成功',array('url'=>$this->_redirect));
					}
					else
					{
						$this->_ajaxJson(0,'成功',array('url'=>'Account-bind?redirect=' . $this->_redirect));
					}
				}
				else
				{
					$this->_ajaxJson(0,'成功',array('url'=>'Homemaking-index-t-motherBabyQA-v-'.time()));
				}
			}
			else
			{
				$this->_ajaxJson($res['status'] , $res['msg'] , array());
			}
		}
		else
		{
			$this->display('Account:login');
		}
	}
	private function _saveLoginData ($res)
	{
		$this->_aid = $res['result']['account_id'];
		$this->_code = $res['result']['code'];
		cookie('per',$this->_code,86400);
		session('uid',$this->_aid);
		session('uid');
		$redis = initRedis();
		$redisres = $redis->HGetAll($this->_code);
		$this->_per_user_id = $redisres['id'];
		session('per_user_id', $this->_per_user_id);
	}
	/**
	 * 获取微信用户头像
	 * @param  [int]	$uid		[用户aid]
	 * @param  [string] $headimgurl [图片url]
	 * @return [null]
	 */
	private function getphoto($uid,$headimgurl)
	{
		$url = $headimgurl;
		$filename = mkFilePath($uid,'Uploads/Users/','photo');
		$filename = $filename.'userphoto.jpg';
		$hander = curl_init();
		$fp = fopen($filename,'wb');
		curl_setopt($hander,CURLOPT_URL,$url);
		curl_setopt($hander,CURLOPT_FILE,$fp);
		curl_setopt($hander,CURLOPT_HEADER,0);
		curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
		//curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
		curl_setopt($hander,CURLOPT_TIMEOUT,60);
		curl_exec($hander);
		curl_close($hander);
		fclose($fp);
	}
	//验证账号是否存在
	public function telphone()
	{
		$phone['user_account'] = I('post.phoneVal');
		//$account_info = D('AccountInfo');
		//$res = $account_info->accountAssign($phone);
		//$res =  $account_info->where($phone)->find();
		$url =APP_DOMIN;
		$url = $url.'Chitone-Account-allowReg';
		$data =  urlencode(json_encode($phone));
		$res = _get($url,$data);
		$status = $res['status'];
		if(IS_AJAX)
		{
			if ($status)
			{
				$res = _get(APP_DOMIN.'Chitone-Account-allowReReg',$data);
				if ($res['status'] === 0)
				{
					exit("true");
				}
				echo "false";
				exit();
			}
			else
			{
				echo "true";
				exit();
			}
		}
	}
	
	/**
	  * verify
	  * 获取验证码
	  * @access public
	  * @return void
	  * @date 2016-4-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function verify(){
		create_verify();
	}
	
	/**
	  * verify
	  * 获取验证码
	  * @access public
	  * @return void
	  * @date 2016-4-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function checkVerify(){
		if (IS_POST) {
			$verify = I('post.verify');
			$result = check_verify($verify);
			if ($result) {
				//$this->ajaxReturn('true');
				echo 'true';
			}else {
				//$this->ajaxReturn('false');
				echo 'false';
			}
		}else {
			$this->error('非法操作!');
		}
	}
	
	//验证code及手机验证码是不是和session中的一样
	public function code()
	{
		$getcode = I('post.codeVal');
		$code = session('code');
		$phoneVal= I('post.phoneVal');
		$phone = session('phone');
		if(IS_AJAX)
		{
			$flag = true;
			if (session('time')){
				$time = session('time');
				$newtime = time();
				$losetime = $newtime - $time;
				$losetime = 10*60 - $losetime;
				if ($losetime > 1 && $losetime < 10*60) {
					$flag = true;
				}else{
					$flag = false;
					session('code',null);
				}
			}
			if(($getcode==$code)&&($phoneVal==$phone)&&$flag)
			{
				echo "true";
				exit();
			}
			else
			{
				echo "false";
				exit();
			}
		}
	}
	//获取手机验证码,并发送到用户手机上
	/*public function getcode()
	{
		$phone = I('post.phoneVal');
		$verify = I('post.verifyCode');
		$ip = get_client_ip(1);
		if (S('sms_ip_'.$ip)) {
			$temp = S('sms_ip_'.$ip);
			S('sms_ip_'.$ip,$temp+1,84600);
		}else {
			S('sms_ip_'.$ip,1,84600);
		}
		if (S('sms_ip_'.$ip) > 50) {
			$this->ajaxReturn(array('status'=>0,'info'=>'请勿恶意发送验证码！'));die;
		}else {
			//检测验证码
			if(!check_verify($verify)){
				$this->ajaxReturn(array('status'=>0,'info'=>'图片验证码错误！'));
			}else {
				if(session('time') && (session('time') + 60) > time()){
					$this->ajaxReturn(array('status'=>0,'info'=>'一次验证码一分钟内只能发送！'));die;
				}
				session('phone',$phone);
				$m = new Memcache();
				$m->addServer('localhost', 11211);
				$numbers = $m->get($phone);
				
				$log['ip'] = $ip;
				$log['realIp'] = get_client_ip();
				$log['mobile'] = $phone;
				$log['numbers'] = $numbers?$numbers:0;
				$log['time'] = date('Y-m-d H:i:s');
				//清除校验码session
				session('verify',null);
				if ($numbers){
					if ($numbers < 5){
						$code =  rand(100000,999999);
						session('code',$code);
						session('codeLifeTime',time());
						$msg = "【".WECHAT_NAME."】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
						echo "true";
						$time = time();
						session('time',$time);
						sendPhoneMsg($phone,$msg);
						$m->increment($phone,1);
						$log['isSend'] = true;
						systemLog(json_encode($log));
						//$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！'));
					}else{
						$content = '1';
						echo $content;
						$log['isSend'] = false;
						systemLog(json_encode($log));
						//$this->ajaxReturn(array('status'=>0,'info'=>'发送失败，每天只能发送5条短信！'));
					}
				}else{
					$m->set($phone, 1, 0, 3600*24);
					$code =  rand(100000,999999);
					session('code',$code);
					session('codeLifeTime',time());
					$msg = "【".WECHAT_NAME."】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
					echo "true";
					$time = time();
					session('time',$time);
					sendPhoneMsg($phone,$msg);
					$log['isSend'] = true;
					systemLog(json_encode($log));
					//$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！'));
				}
			}
		}
	}*/
	
	//获取手机验证码,并发送到用户手机上
	public function getcode()
	{
		$state = $this->verifyCode();
		if($state == false)
		{
			echo "false";
			exit();
		}
		session('verify',null);
		$phone = I('post.phoneVal');
		session('phone',$phone);
		$m = new Memcache();
		$m->addServer('localhost', 11211);
		$numbers = $m->get($phone);
		if ($numbers)
		{
			if ($numbers < 5)
			{
				$code =  rand(100000,999999);
				session('code',$code);
				session('codeLifeTime',time());
				$msg = "尊敬的用户，您的手机验证码是".$code."，如果超过时间请重新获取，谢谢合作。";
				echo "true";
				$time = time();
				session('time',$time);
				sendPhoneMsg($phone,$msg);
				$m->increment($phone,1);
			}
			else
			{
				$content = '1';
				echo $content;
				//$this->assign('content'.$content);
				//$this->display('feasy/error');
			}
		}
		else
		{
			$m->set($phone, 1, 0, 3600*24);
			$code =  rand(100000,999999);
			session('code',$code);
			session('codeLifeTime',time());
			$msg = "尊敬的用户，您的手机验证码是".$code."，如果超过时间请重新获取，谢谢合作。";
			echo "true";
			$time = time();
			session('time',$time);
			sendPhoneMsg($phone,$msg);
		}
	}
	
	public function verifyCode()
	{
		$oldverify = session('verify');
		$verify = I('post.verifyCode');
		$verify = strtolower($verify);
		$newverify = md5($verify);
		if(IS_AJAX && ACTION_NAME == 'verifyCode')
		{
			if($oldverify == $newverify)
			{
				echo "true";
				exit();
			}
			else
			{
				echo "false";
				exit();
			}
		}
		else
		{
			if($oldverify == $newverify)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	public function bind()
	{
		$lasturl = I('get.str') ? I('get.str') : I('get.redirect');
		$redirect = $lasturl;
		$this->assign('redirect', $redirect);
		if (session('time'))
		{
			$time = session('time');
			$newtime = time();
			$losetime = $newtime - $time;
			$losetime = 60 - $losetime;
			if ($losetime > 1 && $losetime < 60) {
				$this->assign('time', $losetime);
			}
			$this->display('Account:bind');
		}
		else
		{
			$this->display('Account:bind');
		}
	}
	//验证手机号码
	public function mobile()
	{
		$phone['user_telphone'] = I('post.phoneVal');
		//$account_info = D('AccountInfo');
		//$res = $account_info->accountAssign($phone);
		//$res =  $account_info->where($phone)->find();
		$url =APP_DOMIN;
		$url = $url.'Chitone-Account-allowBindMobile';
		$data =  urlencode(json_encode($phone));
		$res = _get($url,$data);
		//$status为200013时候是手机用了，0表示手机没有被占用。
		$status = $res['status'];
		if(IS_AJAX)
		{
			if ($status)
			{
				echo "false";
				exit();
			}
			else
			{
				echo "true";
				exit();
			}
		}
	}

//提交手机到数据库
	public function bindphone()
	{
		$redirect = I('post.redirect');
		$phone['open_id'] = session('openid');
		$phone['user_telphone'] = I('post.phoneVal');
		$phone['type'] ='weixin';
		$url =APP_DOMIN;
		$url = $url.'Chitone-Account-bindMobile';
		//$url = 'localhost/Chitone-Account-bindMobile';
		$data =  urlencode(json_encode($phone));
		$res = _get($url,$data);
		$status = $res['status'];
		if($status)
		{
			//提交预约
			if (session('requirement')) {
				redirect(U('Homemaking-requirementSubmit4Login','',''));
			}else {
				redirect('Account-bind', 0, '页面跳转中...');
			}
		}
		else
		{
			$account_info = D('AccountInfo');
			$is_login = $account_info->logining();
			$archives_person = D('ArchivesPerson');
			$result = $archives_person->where('account_id = '.$is_login)->find();


			$archives_person_resume = D('ArchivesPersonResume');
			$res = $archives_person_resume->where('account_id = '.$result['id'])->find();
			$result1 = json_decode ( $res['education_info'], true );


			if(isset($result) && $result['user_name']&&$result['gender']&&$result['birthday']&&$result['degree']&&isset($result1)&& $result1[0]['begin']&&$result1[0]['schoolName'])
			{
				/*if ($redirect)
				{
					redirect($redirect);
				}
				else
				{
					redirect('Ptime-nearJob', 0, '页面跳转中...');
				}*/
				if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else if ($redirect) {
					redirect($redirect);
				}else {
					//redirect(U('Homemaking-index-t-motherBabyQA',array('v'=>time()),''));
					redirect(U('Homemaking-index',array('t'=>'motherBabyQA','v'=>time()),''));
				}
			}
			else
			{
				//redirect('Ptime-card?redirect='.$redirect, 0, '页面跳转中...');
				//提交预约
				if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else if ($redirect) {
					redirect($redirect);
				}else {
					//redirect(U('Homemaking-index-t-motherBabyQA',array('v'=>time()),''));
					redirect(U('Homemaking-index',array('t'=>'motherBabyQA','v'=>time()),''));
				}
			}
		}
	}
	
	/**
	 * 二维码推广 注册数统计
	 * @return [type] [description]
	 */
	private function _regCount(){
		$openid = session('openid');
		$record = M('ProjPtimeWechatRecord','',DB_CONFIG2);
		$where['openid'] = $openid;
		$where['active_type'] = 2;
		$res = $record->where($where)->find();
		
		if(isset($res['barcode_id']) && $res['barcode_id']){
			$data['barcode_id'] = $res['barcode_id'];
			$m = M('ProjPtimeWechatCount','',DB_CONFIG2);
			$array = array();
			$array['openid'] = $openid;
			$array['barcode_id'] = $res['barcode_id'];
			$array['active_type'] = 2;
			$record->add($array);
			$m->where($data)->setInc('reg_count' , 1);
		}
	}
	
	/**
	 * 二维码推广微名片生成统计
	 * @return [type] [description]
	 */
	private function _regResumeCount(){
		$openid = session('openid');
		$record = M('ProjPtimeWechatRecord','',DB_CONFIG2);
		$where['openid'] = $openid;
		$res = $record->where($where)->find();

		if(isset($res['barcode_id']) && $res['barcode_id']){
			$data['barcode_id'] = $res['barcode_id'];
			$m = M('ProjPtimeWechatCount','',DB_CONFIG2);

			$m->where($data)->setInc('reg_resume_count' , 1);
		}  
	}

	/**
	 * 获取accesstoken接口
	 * @return void
	 */
	public function getaccesstoken()
	{
		$accesstoken = accesstoken();
		$accesstoken = json_encode($accesstoken);
		die($accesstoken);
	}
}
?>