<?php
class AccountInfoModel extends Model
{
	protected $_validate = array(
			array('password' ,'require','密码不能为空'),
			array('password' ,'/^[a-zA-Z\w]{6,16}$/','密码格式错误'),
			array('email','require','邮箱不能为空'),
			array('email','','邮箱已存在'  ,2,'unique',1),
			array('email','/^([A-Za-z0-9])([\w\-\.])*@(vip\.)?([\w\-])+(\.)(com|com\.cn|net|cn|net\.cn|org|biz|info|gov|gov\.cn|edu|edu\.cn|biz|cc|tv|me|co|so|tel|mobi|asia|pw|la|tm)$/','邮箱格式错误'),
	);
	
	/**
	 * 生成密码
	 * @param string $password 输入密码字符串
	 * @param string $salt 生成的随机字串
	 * return string
	 */
	private function _mkPwd($password,$salt)
	{
		if (!$password || !$salt) return false;
		$password = md5(md5($password).$salt);
		return $password;
	}
	/**
	 * 密码是否正确
	 * return boolean
	 */
	private function _verifyPwd($password,$salt,$input)
	{
		if (!$password || !$salt || !$input) return false;
		$pwd = $this->_mkPwd($input,$salt);
		if ($pwd == $password)
		{
			return true;
		}
		return false;
	}
	/**
	 * 生成六位随机字串
	 * return string
	 */
	private function _mkSalt()
	{
		return substr(uniqid(rand()), -6);
	}
	/**
	 * 用户注册入库
	 * return boolean
	 */
	public function reg ($phone,$password)
	{
            $param['account_source'] = "微信公共号家政服务";
            $param['user_telphone'] = $phone;
			$param['md5_string'] = $this->_mkSalt();
			$param['user_password'] = $this->_mkPwd($password,$param['md5_string']);
			$param['ip'] = get_client_ip();//获取客户端ip
			$param['last_login_time'] = $param['registered_time'] = date('Y-m-d H:i:s',time());
			$uid = $this->add($param);
			return $uid;
	}


	/**
	 * 构造用户查询条件
	 * @param string $username 录入的用户名
	 * return string
	 */
	private function _catAccountWhere ($username)
	{
		return mobileFormat($username) ? "user_telphone = '{$username}'" : (emailFormat($username) ? "user_email = '{$username}'" : "user_account = '{$username}'");	
	}
	/**
	 * 登录状态改变
	 * @param array $data 保存的数据
	 * return string
	 */
    /*
	private function _loginStatus ($data)
	{
		if (count($data) > 0)
		{
			$param = array();
			$param['ip'] = get_client_ip();//获取客户端ip
			$param['last_login_time'] = date('Y-m-d H:i:s',time());
			$m->where("id={$uid}")->save($param);
			$this->_saveAccount($data);
			return true;
		}
		else
		{
			return false;	
		}
	}
    */
	/**
	 * 用户登录入库
	 * return boolean
	 */
	public function login($data)
	{
		$m = M('Account_info');	
		$username = I('post.username','');
		$password = I('post.password','');
		if (!$username || !$password) return false;
		$where = $this->_catAccountWhere($username);
		$data = $this->accountAssign($where);
		if (!$this->_verifyPwd($data['user_password'],$data['md5_string'],$password))
		{
			return false;
		}
		else if (in_array($data['account_status'],array(1,2)))
		{
			$array = array (1=>'已停用',2=>'已冻结');
			return $array[$data['account_status']];
		}
		else if ($data['account_status'] == 0)
		{
			return $this->_loginStatus($data);
		}
		else 
		{
			return false;	
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
	
	/**
	 * 保存登陆数据
	 * @param array $data 存储的数据
	 * return boolean
	 */
	private function _saveAccount($data)
	{
		if (!is_array($data) || count($data) <=0 ) return false;
		unset($data['md5_string']);
		foreach ($data as $k=>$v)
		{
			session($k,$v);
			cookie($k,$v);
		}
	}
	/**
	 * 是否登陆
	 * return boolean
	 */
	public function logined ()
	{
		$uid = intval(session('uid'));
		if (!$uid)
		{
			$mobile = I('cookie.user_telphone');
			$email = I('cookie.user_email');
			$account = I('cookie.user_account');
			$password = I('cookie.user_password');
			$username = $mobile ? $mobile : ($email ? $email : $account); 
			if (!$username) return false;
			$where = $this->_catAccountWhere($username);
			$data = $this->accountAssign($where);
			if ($data['account_status'] == 0 && $this->_verifyPwd($data['user_password'],$data['md5_string'],$password))
			{
				$this->_loginStatus($data);
				return $data['uid'];
			}
			else 
			{
				return false;	
			}
		}
		else
		{
			return $uid;
}
}

    public function logining()
    {
        if(session('uid'))
        {
        	if(cookie('per')){
            	return session('uid');
            }else{
            	$url = APP_DOMIN.'Chitone-Account-login';
                $openid = session('openid');
                $data = urlencode(json_encode(array('open_id'=>$openid,'type'=>'weixin','ip'=>get_client_ip())));
                $res = $this->_get($url,$data);
                $code = $res['result']['code'];
                cookie('per',$code,86400);
                $uid = $res['result']['account_id'];
                session('uid',$uid);
                return $uid;
            }
        }
        else
        {
            $code = cookie('per');
            if ($code)
                {
                    $redis = new Redis();
                    $redis->connect('192.168.2.183', '6379');
                $redis->auth('job5156RedisMaster183');
                    $res = $redis->HGetAll($code);
                    $uid = $res['account_id'];
                if (!$uid)
                {
                    $url = APP_DOMIN.'Chitone-Account-login';
                    $openid = session('openid');
                    $data = urlencode(json_encode(array('open_id'=>$openid,'type'=>'weixin','ip'=>get_client_ip())));
                    $res = $this->_get($url,$data);
                    $code = $res['result']['code'];
                    cookie('per',$code,86400);
                    $uid = $res['result']['account_id'];
                    session('uid',$uid);
                    return $uid;
                }
                      return $uid;
               }
            else
            {
                $url = APP_DOMIN.'Chitone-Account-login';
                $openid = session('openid');
                $data = urlencode(json_encode(array('open_id'=>$openid,'type'=>'weixin','ip'=>get_client_ip())));
                $res = $this->_get($url,$data);
                $code = $res['result']['code'];
                cookie('per',$code,86400);
                $uid = $res['result']['account_id'];
                session('uid',$uid);
                return $uid;
            }
        }
    }

    private function _get($url,$data)
    {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $res = curl_exec ( $ch );
        curl_close ( $ch );
        $res = json_decode ( $res, true );
        return $res;
    }
}
?>