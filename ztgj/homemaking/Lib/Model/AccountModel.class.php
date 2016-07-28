<?php
class AccountModel extends Model
{
	/**
	 * 是否已绑定手机
	 * @param array $account 通行证信息
	 * @param array $open_id 接口的openid
	 * @param string $type 接口类型
	 * return string
	 */
	public function isBindMobile($aid , $bind=array())
	{
		if(!$aid)
		{
			$m = M('per_user_connect');
			$aid = 	$m->where("type = '{$bind['type']}' AND open_id = '{$bind['open_id']}'")->getField('account_id');
		}
		$m = M('account_info');
		$rs = $m->where("id={$aid}")->getField('user_telphone');
		if ($rs)
		{
			return 200104;
		}
		return 0;
	}
	/**
	 * 接口注册逻辑
	 * @param array $account 通行证信息
	 * @param array $open_id 接口的openid
	 * @param string $type 接口类型
	 * return string
	 */
	public function reg($account , $open = array())
	{
		$m = M('Account_info');
		if ($account['user_type'] == 0)
		{
			if ($this->allowReg($account))
			{
				if (mobileFormat($account['user_account']))
				{
					$account['user_telphone'] = $account['user_account'];
				}
				else if(emailFormat($account['user_account']))
				{
					$account['user_email'] = $account['user_account'];
				}
				$account['last_login_time'] = $account['registered_time'] = date('Y-m-d H:i:s' , time());
				$puser = array();
				$puser['account_id'] = $account['id'] = $m->add($account);
				$puser['account'] = $account['user_account'];
				$puser['password'] = $account['user_password'];
				$puser['ip'] = $account['ip'];
				$puser['login_count'] = 0;
				$puser['account_type'] = 0;
				$puser['login_date'] = $account['last_login_time'];
				$puser['create_date'] = $account['registered_time'];
				$puser['admin_id'] = $account['admin_id'];
				$puser['account_from'] = $account['account_from'];
				$puser['role_type'] = $account['role_type'];
				if ($account['user_telphone'])
				{
					$puser['mobile'] = $account['user_telphone'];
					$puser['mobile_activation'] = 1;
				}
				$m = M('per_user');
				$puserId = $m->add($puser);
				if ($open['type'] && $open['open_id'])
				{
					$bind = array ();
					$bind['type'] = $open['type'];
					$bind['account_id'] = $puser['account_id'];
					$bind['per_user_id'] = $puserId;
					$bind['open_id'] = $open['open_id'];
					$bind['open_name'] = $open['open_name'];
					$bind['connect_time'] = date('Y-m-d H:i:s',time());
					$m = M('per_user_connect');
					if (!($m->where("account_id={$puser['account_id']} AND `type`='{$bind['type']}' AND open_id='{$bind['open_id']}'")->getField('id')))
					{
						$m->add($bind);
					}
				}
				$save = array();
				$save['id'] = $puserId;
				$save['user_name'] = '';
				$save['ip'] = $account['ip'];
				$save['account_id'] = $puser['account_id'];
				$this->_saveLogData($save);
				return $account;
			}
			else
			{
				return false;	
			}
		}
		else if ($account['user_type'] == 1)
		{
			
		}
		else
		{
			return false;	
		}
	}
	/**
	 * 用户登录
	 * @param array $account 通行证账号
	 * return bool
	 */
	public function login ($account , $open=array())
	{
		$retArray = array ();
		if ($account['user_type'] == 0)
		{
			//是否登录
			if ($open['open_id'] && $open['type'] && $account['ip'] && !($account['user_account'] && $account['user_password']))
			{
				$m = M('per_user_connect');
				$account = $m->where("open_id='{$open['open_id']}' AND `type`='{$open['type']}'")->find();
				if (!$account['account_id'])
				{
					return false;
				}
				else
				{
					$m = M('account_info');
					$account = $m->where("id={$account['account_id']}")->find();
					if (!$account)
					{
						return false;
					}
				}
				
				$upd = array ();
				$upd['ip'] = $account['ip'];
				$upd['last_login_time'] = date('Y-m-d H:i:s' , time());
				$upd['user_login_number'] = $account['user_login_number']+1;
				$m = M('account_info');
				$m->where("id={$account['id']}")->save($upd);
				
				$upd = array();
				$upd['ip'] = $account['ip'];
				$upd['login_date'] = date('Y-m-d H:i:s' , time());
				$upd['login_count'] = $account['user_login_number']+1;
				$m = M('per_user');
				$m->where("account_id={$account['id']}")->save($upd);
				
				$m = M('per_user');
				$perUser = $m->where("account_id={$account['id']}")->find();
				$save = array();
				$save['id'] = $perUser['id'];
				$save['user_name'] = $perUser['user_name'];
				$save['ip'] = $account['ip'];
				$save['account_id'] = $account['id'];
				$this->_saveLogData($save);
				return $account;
			}
			//绑定
			else if ($open['open_id'] && $open['type'] && $account['ip'] && $account['user_account'] && $account['user_password'])
			{
				$field = '';
				$m = M('account_info');
				//$where = $this->_catAccountWhere($account['user_account']);
				$rs = $m->where("user_account='{$account['user_account']}' AND user_password='{$account['user_password']}'")->find();
				if (!$rs)
				{
					$m = M('per_user');
					$rs = $m->where("account='{$account['user_account']}' AND `password`='{$account['user_password']}'")->find();
					if (!$rs)
					{
						return false;
					}
					$account['account_from'] = $rs['account_from'];
					$account['user_login_number'] = $rs['login_count'];
					$account['last_login_time'] = $account['registered_time'] = date('Y-m-d H:i:s' , time());
					$m = M('account_info');
					$insert = $account['id'] = $m->add($account);
					$m = M('per_user');
					$upd = array();
					$upd['account_id'] = $insert;
					$upd['login_count'] = $rs['login_count']+1;
					$upd['ip'] = $account['ip'];
					$m->where("id={$rs['id']}")->save($upd);
					$bind = array ();
					$bind['type'] = $open['type'];
					$bind['account_id'] = $insert;
					$bind['per_user_id'] = '';
					$bind['open_id'] = $open['open_id'];
					$bind['open_name'] = $open['open_name'] ? $open['open_name'] : '';
					$bind['connect_time'] = date('Y-m-d H:i:s',time());
					$m = M('per_user_connect');
					if (!($m->where("account_id={$insert} AND `type`='{$bind['type']}' AND open_id='{$bind['open_id']}'")->getField('id')))
					{
						$m->add($bind);
					}
					
					$save = array();
					$save['id'] = $rs['id'];
					$save['user_name'] = $rs['user_name'];
					$save['ip'] = $account['ip'];
					$save['account_id'] = M('Account_info')->where("account_id={$rs['id']}")->getField('id');
					$this->_saveLogData($save);
					return $account;
				}
				else
				{
					$m = M('per_user');
					$perUser = $m->where("account_id={$rs['id']}")->find();
					$save = array();
					$save['id'] = $perUser['id'];
					$save['user_name'] = $perUser['user_name'];
					$save['ip'] = $account['ip'];
					$save['account_id'] = $rs['id'];
					$this->_saveLogData($save);
					return $rs;	
				}
				
			}
			//正常登录
			else if ($account['ip'] && $account['user_account'] && $account['user_password'])
			{	
				$field = '';
				$m = M('account_info');
				$rs = $m->where("user_account='{$account['user_account']}' AND `user_password`='{$account['user_password']}'")->find();
				if (!$rs)
				{
					$m = M('per_user');
					$rs = $m->where("account='{$account['user_account']}' AND `password`='{$account['user_password']}'")->find();
					if (!$rs)
					{
						return false;
					}
					$account['account_from'] = $rs['account_from'];
					$account['user_login_number'] = $rs['login_count'];
					$account['last_login_time'] = $account['registered_time'] = date('Y-m-d H:i:s' , time());
					$m = M('account_info');
					$insert = $account['id'] = $m->add($account);
					$m = M('per_user');
					$upd = array();
					$upd['account_id'] = $insert;
					$upd['login_count'] = $rs['login_count']+1;
					$upd['ip'] = $account['ip'];
					$m->where("id={$rs['id']}")->save($upd);
					
					$save = array();
					$save['id'] = $rs['id'];
					$save['user_name'] = $rs['user_name'];
					$save['ip'] = $account['ip'];
					$save['account_id'] = $account['id'];
					$this->_saveLogData($save);
					return $account;
				}
				else
				{
					$m = M('per_user');
					$perUser = $m->where("account_id={$rs['id']}")->find();
					$save = array();
					$save['id'] = $perUser['id'];
					$save['user_name'] = $perUser['user_name'];
					$save['ip'] = $account['ip'];
					$save['account_id'] = $rs['id'];
					$this->_saveLogData($save);
					return $rs;	
				}
			}
			else 
			{
				return false;	
			}
		}
		else if ($account['user_type'] == 1)
		{
			
		}
		else 
		{
			return false;	
		}
		
		
		
	}
	/**
	 * 是否允许绑定手机
	 * @param array $account 通行证账号
	 * return 无
	 */
    public function allowBindMobile($account)
    {
        $m = M('account_info');
        $assign = $m->where("user_telphone={$account['user_telphone']}")->getField('id');
        //是否已占用
        if ($assign)
        {
            return 200103;
        }
        return 0;
    }
	/**
	 * 接口绑定手机号码
	 * @param array $account 通行证账号
	 * return 无
	 */
	public function portBindMobile ($account , $bind)
	{
		$m = M('per_user_connect');
		$account_id = $m->where("type='{$bind['type']}' AND open_id='{$bind['open_id']}'")->getField('account_id');
		//是否已绑定第三方数据
		if (!$account_id)
		{
			return 200101;
		}
		$m = M('account_info');
		$assign = $m->where("user_telphone={$account['user_telphone']}")->getField('id');
		//是否已占用
		if ($assign)
		{
			return 200103;
		}
		//更新数据
		$m = M('account_info');
		$m->where("id={$account_id}")->setField('user_telphone' , $account['user_telphone']);
		$m = M('per_user');
		$upd = array();
		$upd['mobile'] = $account['user_telphone'];
		$upd['mobile_activation'] = 1;
		$m->where("account_id={$account_id}")->save($upd);
		return 0;	
	}
	/**
	 * 保存登录信息
	 * @param array $account 通行证账号
	 * return 无
	 */
	private function _saveLogData ($account)
	{
		
	}
	/**
	 * 是否允许注册
	 * @param array $account 通行证账号
	 * return bool
	 */
	public function allowReg($account)
	{
		if ($account['user_type'] == 0)
		{
			$where = $this->_catAccountWhere($account['user_account']);
			$m = M('Account_info');
			$rs = $m->where($where)->getField('id');
			$m = M('per_user');
			$old = $m->where("account='{$account['user_account']}'")->getField('id');
			if (!$rs && !$old)
			{
				return true;
			}
			return false;
		}
		
	}
	/**
	 * 构造用户查询条件
	 * @param string $username 录入的用户名
	 * return string
	 */
	private function _catAccountWhere ($account)
	{
		return mobileFormat($account) ? "user_telphone = '{$account}'" : (emailFormat($account) ? "user_email = '{$account}'" : "user_account = '{$account}'");
	}
	
}
?>