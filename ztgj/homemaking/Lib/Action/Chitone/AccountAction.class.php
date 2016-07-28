<?php
/**
 * Created by PhpStorm.
 * User: Zhsx
 * Date: 2015/4/23
 * Time: 9:27
 */
class AccountAction extends Action 
{
	private $_json = array();
	/**
     * 构造函数
     */
	public function _initialize()
	{
		$this->_setJson();
	}
	/**
     * 设置json属性，返回错误状态
     */
	private function _setJson()
	{
		$this->_json = json_decode(urldecode(file_get_contents('php://input')) , true);
		if (!$this->_json)
		{
			$this->_respJson(100000);
		}
	}
	/**
     * 设置json属性
     * @param  [int] $status 返回的状态码
     * @return string
     */
	private function _respJson($status,$result=array())
	{
		$array = array(
			0      => '操作成功',//操作相关
			100000 => '数据格式错误',//格式及参数相关
			100001 => '参数错误',
			100002 => '手机号码格式错误',
			200000 => '用户已存在',//账号相关
			200100 => '绑定失败',//绑定相关
			200101 => '还未绑定第三方账号',
			200102 => '绑定手机失败',
			200103 => '手机号码已被占用',
			200104 => '该用户已绑定了手机',
			999999 => '系统错误，请稍后重试'
		);
		die(json_encode(array('status'=>$status,'msg'=>($array[$status] ? $array[$status] : ''),'result'=>$result)));
	}
	/**
     * 根据用户平面坐标
     * @param  [array] $array 经纬度数组
     * @return string 
     */
	public function index()
	{
		//$array = array();
		//D('Account')->normalReg(array());
		//$redis = new Redis();    
		//$redis->connect('192.168.2.183', '6379');
             //$redis->auth('job5156RedisMaster183');
		//$redis->set('test',null);
	}
	/**
     * 用户登录
     * @param  [array] $array 经纬度数组
     * @return string 
     */
	public function login()
	{
		$array = array();
		$array['user_account'] = $this->_json['user_account'];
		$array['user_password'] = $this->_json['user_password'];//组合加密后的密码
		$array['user_type'] = intval($this->_json['user_type']);
		$array['ip'] = $this->_json['ip'];
		$bind = array ();
		$bind['open_id'] = $this->_json['open_id'];
		$bind['type'] = $this->_json['type'];
        $bind['open_name'] = $this->_json['open_name'];
		//登录方式
		$normal = $array['user_account'] && $array['user_password'] && $array['ip'];
		$port = $bind['open_id'] && $bind['type'] && $array['ip'];
		$result = array();
		if ($port && $normal)//接口注册逻辑
		{
			$result = D('Account')->login($array , $bind);
		}
		else if ($normal)//一般注册逻辑
		{
			$result = D('Account')->login($array);
		}
		else if ($port)
		{
			$result = D('Account')->login($array , $bind);
		}
		else
		{
			$this->_respJson(100001);	
		}
        if (count($result) > 0)
        {
            $this->_respJson(0,array('code'=>md5("PER2013:{$result['ip']}:{$result['id']}"),'account_id'=>$result['id']));
        }
        $this->_respJson(999999);
		
	}
    /**
     * 通过第三方接口登录
     * @param
     * @return string
     */
    public function allowBindMobile()
    {
        $account = array();
        $account['user_telphone'] = $this->_json['user_telphone'];
        $result = D('Account')->allowBindMobile($account);
        $this->_respJson($result);
    }
	/**
     * 通过第三方接口登录
     * @param
     * @return string 
	 */
	public function bindMobile()
	{
		$account = array();
		$account['user_telphone'] = $this->_json['user_telphone'];
		$account['user_type'] = intval($this->_json['user_type']);
		$bind = array();
		$bind['open_id'] = $this->_json['open_id'];
		$bind['type'] = $this->_json['type'];
		if (!mobileFormat($account['user_telphone']))
		{
			$this->_respJson(100002);
		}
		else
		{
			$result = 999999;
			if ($bind['open_id'] && $bind['type'] && $account['user_telphone'])
			{
				$result = D('Account')->portBindMobile($account , $bind);
			}
			else
			{
				$result = D('Account')->normalBindMobile();
			}
			$this->_respJson($result);
			
		}
	}
	/**
     * 是否已绑定手机
     * @param
     * @return string 
    */
	public function isBindMobile ()
	{
		$bind = array();
		$bind['open_id'] = $this->_json['open_id'];
		$bind['type']    = $this->_json['type'];
		$aid = intval($this->_json['account_id']);
		$result = 999999;
		$result = D('Account')->isBindMobile($aid , $bind);
		$this->_respJson($result);
	}
	/**
     * 是否允许注册
     * @param  [array] $array 经纬度数组
     * @return string 
     */
	public function allowReg()
	{
        $account = array();
		$account['user_account'] = $this->_json['user_account'];
        $account['user_type'] = intval($this->_json['user_type']);
		$rs = D('Account')->allowReg($account);
		if ($rs)
		{
			$this->_respJson(0);
		}
		else
		{
			$this->_respJson(200000);	
		}

	}
	/**
     * 用户注册
     */
	public function reg ()
	{
		$array = array();
		$array['user_account'] = $this->_json['user_account'];
		$array['user_password'] = $this->_json['user_password'];//组合加密后的密码
		$array['user_type'] = intval($this->_json['user_type']);
		$array['ip'] = $this->_json['ip'];
		$array['admin_id'] = intval($this->_json['admin_id']) ? intval($this->_json['admin_id']) : null;
		$array['user_login_number'] = 0;
		$array['account_from'] = intval($this->_json['account_from']) ? intval($this->_json['account_from']) : null;
		$array['role_type'] = intval($this->_json['role_type']);
		$bind = array();
		$bind['open_id'] = $this->_json['open_id'];
		$bind['type'] = $this->_json['type'];
		$bind['open_name'] = $this->_json['open_name'];
		//注册方式
		$normal = $array['user_account'] && $array['user_password'] && $array['ip'];
		$port = $bind['open_id'] && $bind['type'];
		$result = array();
		if ($port && $normal)//接口注册逻辑
		{
			$result = D('Account')->reg($array , $bind);
		}
		else if ($normal)//一般注册逻辑
		{
			$result = D('Account')->reg($array);
		}
		else
		{
			$this->_respJson(100001);
		}
		if (count($result) > 0)
		{
			$this->_respJson(0,array('code'=>md5("PER2013:{$result['ip']}:{$result['id']}"),'account_id'=>$result['id']));
		}
		$this->_respJson(999999,array('code'=>$code));
		//$array['user_type'] = intval(I('post.'))
		
	}
}