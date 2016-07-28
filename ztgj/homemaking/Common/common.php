<?php
/**  * 系统	/**
 * 检测扩展是否加载
 * @param string $extension	要检测的扩展名称
 * @return boolean 
 */
function extensioned ($extension)
{
	if (!in_array('curl',get_loaded_extensions())) return false;
	return true;
}
/**  * 系统	/**
 * 通用调用API接口函数
 * @param string $url   API接口的地址
 */
function get($url)
{
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
	$res = curl_exec ( $ch);
	curl_close ( $ch);
	$res = json_decode ( $res, true );
	return $res;
}
/**  * 系统	/**
 * 通用调用API接口函数
 * @param string $url   API接口的地址
 * @param string $data  发送的数据
 */
function _get($url,$data)
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

/**  * 系统    /**
 * 通用调用API接口函数
 * @param string $url   API接口的地址
 * @param string $data  发送的数据
 */
function _getDebug($url,$data)
{
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    $res = curl_exec ( $ch );
    
	//容错机制
	if($res === false){
	    var_dump(curl_error($ch));
	}
	//curl_getinfo()获取各种运行中信息，便于调试
	$info = curl_getinfo($ch);
	echo "执行时间".$info['total_time'].PHP_EOL;
	dump($info);
	
    curl_close ( $ch );
    $res = json_decode ( $res, true );
    return $res;
}

/**  * 系统	/**
 * 获取用户openid
 * @param string $code   微信授权返回的序列码，获取openid的凭据
 */
 function _openid($code)
{
	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APPID.'&secret='.APPSECRET.'&code='.$code.'&grant_type=authorization_code';
	$ch1 = curl_init ();
	$timeout = 5;
	curl_setopt ( $ch1, CURLOPT_URL, $url );
	curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
	$openid = curl_exec ( $ch1 );
	curl_close ( $ch1 );
	$openid = json_decode ( $openid, true );
	$openid =   $openid['openid'];
	session('openid',$openid);
	return $openid;
}

function initRedis ()
{
    if (!extensioned('redis')) return false;
    $connect = C('REDIS_CONNECT');
    $redis = new Redis();
    $redis->connect($connect['ip'], $connect['port']);
    if ($connect['auth']) $redis->auth($connect['auth']);
    return $redis;
}

//获取微信access_token
function accesstoken()
{
	//S(APPID.'accesstoken',null);
	if(S(APPID.'accesstoken'))
	{
		$access_token = S(APPID.'accesstoken');
		return  $access_token;
	}
	else
	{
		$filename = 'Runtime/wechatCode.txt';
		$updTime = intval(filemtime($filename));
		if (time()-$updTime <= 7100)
		{
			$fp = fopen($filename , 'r');
			$code = fread($fp , filesize($filename));
			fclose($fp);
			if ($code) return $code;
		}
		$info['appid'] = APPID;
		$info['secret'] = APPSECRET;
		$url_get = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $info ['appid'] . '&secret=' . $info ['secret'];
		$ch1 = curl_init ();
		$timeout = 15;
		curl_setopt ( $ch1, CURLOPT_URL, $url_get );
		curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
		$access_token = curl_exec ( $ch1 );
		curl_close ( $ch1 );
		$access_token = json_decode ( $access_token, true );
		S(APPID.'accesstoken',$access_token ['access_token'],7100);
		$fp = fopen($filename , 'wb');
		fwrite ($fp , $access_token['access_token']);
		fclose($fp);
		return $access_token ['access_token'];
	}

}
//通过微信的API接口获取用户的基本信息
//* @param string $openid	微信用户的唯一id
function userinfo($openid)
{
	$accesstoken = accesstoken();
	$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accesstoken."&openid=".$openid."&lang=zh_CN";
	$ch1 = curl_init ();
	$timeout = 5;
	curl_setopt ( $ch1, CURLOPT_URL, $url );
	curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
	$userinfo = curl_exec ( $ch1 );
	curl_close ( $ch1 );
	$userinfo = json_decode ( $userinfo, true );
	if ($userinfo['errcode'] && $userinfo['errcode'] == 40001) {
		S(APPID.'accesstoken',null);
		$accesstoken = accesstoken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accesstoken."&openid=".$openid."&lang=zh_CN";
		$userinfo = curl_exec ( $ch1 );
		curl_close ( $ch1 );
		$userinfo = json_decode ( $userinfo, true );
	}
	return $userinfo;
}


/**  * 系统	/**
 * 发送短信通道
 * @param string $mobile	发送短信的手机号码
 * @param string $msg  发送的短信内容
 * @return boolean 
 */
function sendPhoneMsg($mobile,$msg)
{
	$url = SMS_DOMAIN.'open/api/parttime/dynamic/code.json';
	$hands = 'hands='.md5('chitone:job5156:shake:hands:mesg');
	$chl = curl_init();
	curl_setopt($chl, CURLOPT_POST, 1);
	curl_setopt($chl, CURLOPT_URL,$url);
	curl_setopt($chl, CURLOPT_POSTFIELDS,$hands);
	ob_start();
	curl_exec($chl);
	$json = json_decode(ob_get_contents(),true);
	ob_end_clean();
	$token = $json['token'];

	if(!mobileFormat($mobile)||!$msg || !extensioned('curl')) return false;
	$url = SMS_DOMAIN.'open/api/parttime/send/mesg.json';
	$post_data = array();
	$post_data = 'token='.$token.'&mobile='.$mobile.'&content='.$msg;
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_POST, 1);  
	curl_setopt($ch, CURLOPT_URL,$url);  
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  
	ob_start();  
	curl_exec($ch);  
	$json = json_decode(ob_get_contents(),true);
	ob_end_clean();

	if (!$json['state'])
	{
		return false;
	}
	return true;
}
/**  * 系统	/**
 * 系统邮件发送函数
 * @param string $to	接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body	邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null)
{
	$config = C('THINK_EMAIL');
	vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
	$mail			 = new PHPMailer(); //PHPMailer对象
	$mail->CharSet	= 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP();  // 设定使用SMTP服务
	$mail->IsHTML(true); 
	$mail->SMTPDebug  = 0;					 // 关闭SMTP调试功能
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;				  // 启用 SMTP 验证功能
	//$mail->SMTPSecure = 'ssl';  			   // 使用安全协议
	$mail->Port	   = $config['SMTP_PORT'];  // SMTP服务器的端口号
	$mail->Host	   = $config['SMTP_HOST'];  // SMTP 服务器
	$mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
	$mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
	$mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
	$replyEmail	   = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
	$replyName		= $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
	$mail->AddReplyTo($replyEmail, $replyName);
	$mail->Subject	= $subject;
	//邮件内容
	$mail->MsgHTML($body);
	//收件邮箱、姓名
	$mail->AddAddress($to, $name);
	if(is_array($attachment))// 添加附件
	{ 
		foreach ($attachment as $file)
		{
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	return $mail->Send() ? true : $mail->ErrorInfo;   
}
/**  * 系统	/**
 * 得到用户上传路径，路径不存在则创建路径($classfile不主动创建)
 * @param int $id	用户唯一id
 * @param string $prefixpath  目录的修正
 * @param string $classfile 目录的补充
 * @return string 
 */
function mkFilePath($id, $prefixpath = '', $classfile = '')
{//{{{
	$id = strval($id);
	$path = substr($id, 0, (strlen($id)-4));
	!$path && $path = 0;
	$path = $prefixpath . $path;
	//echo "path=$path<br>";
	if(!file_exists($path))
	{
		@mkdir($path,0777,true);
		touch($path.'/index.html');
	}
	$path .= "/" . $id;
	//echo "path=$path<br>";
	if(!file_exists($path))
	{
		@mkdir($path);
		touch($path.'/index.html');
	}
	if (strlen($classfile) > 0)
	{
		$path .= "/" . $classfile;
		if(!file_exists($path))
		{
			@mkdir($path,0777);
			touch($path.'/index.html');
		}
	}
	$path .= "/";
	return $path;
}//}}}	
/**  * 系统	/**
 * 手机格式验证
 * @param string $mobile  手机号码
 * @return boolean 
 */
function mobileFormat($mobile)
{
	 if (preg_match("/^0?(13[0-9]|15[0-9]|17[0-9]|18[0-9]|14[57])[0-9]{8}$/",$mobile))
	{
		return true;
	}
	else
	{

		return false;
   }
}
/**  * 系统	/**
 * 邮箱格式验证
 * @param string $mobile  手机号码
 * @return boolean 
 */
function emailFormat($mobile)
{
	 if (preg_match("/^([A-Za-z0-9])([\w\-\.])*@(vip\.)?([\w\-])+(\.)(com|com\.cn|net|cn|net\.cn|org|biz|info|gov|gov\.cn|edu|edu\.cn|biz|cc|tv|me|co|so|tel|mobi|asia|pw|la|tm)$/",$mobile))
	{
		return true;
	}
	else
	{
		return false;
   }
}
/**  * 系统	/**
 * 得到用户上传路径
 * @param int $id	用户唯一id
 * @param string $prefixpath  目录的修正
 * @param string $classfile 目录的补充
 * @return string 
 */
function getFilePath($id, $prefixpath = '', $classfile = '')
{//{{{
	$id = strval($id);
	$path = substr($id, 0, (strlen($id)-4));
	!$path && $path = 0;
	$path = $prefixpath . $path;
	$path .= "/" . $id;
	if (strlen($classfile) > 0)
	{
		$path .= "/" . $classfile;
	}
	$path .= "/";
	return $path;
}//}}}


/**
 * 获取缓存中的区划
 * @return [type]
 */
function getZoning ()
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


/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

/**
 * 生成GUID
 */
function getGUID(){
	if (function_exists('com_create_guid')){
		return com_create_guid();
	}else{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = chr(123)// "{"
			.substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12)
			.chr(125);// "}"
		return $uuid;
	}
}

/**
 * get_chinese_zodiac function
 * 根据出生年份计算属相
 * @param int $year 出生年份
 * @return string
 * @author rohochan<rohochan@gmail.com>
 **/
function get_chinese_zodiac($year = 0){
	$chineseZodiacArray = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪' );
	return $chineseZodiacArray[($year+8)%12];
}

/**
 * get_chinese_zodiac function
 * 根据出生月日计算星座
 * @param int $month 出生月份
 * @param int $day 出生日期
 * @return string
 * @author rohochan<rohochan@gmail.com>
 **/
function get_constellation($month, $day){
	$constellationArray = array(
		array('20'=>'水瓶座'), array('19'=>'双鱼座'),
		array('21'=>'白羊座'), array('20'=>'金牛座'),
		array('21'=>'双子座'), array('22'=>'巨蟹座'),
		array('23'=>'狮子座'), array('23'=>'处女座'),
		array('23'=>'天秤座'), array('24'=>'天蝎座'),
		array('22'=>'射手座'), array('22'=>'摩羯座')
	);
	$key = (int)$month - 1;
	list($startConstellation, $constellation) = each($constellationArray[$key]);
	if( $day < $startConstellation ){
		$key = $month - 2 < 0 ? $month = 11 : $month -= 2;
		list($startConstellation, $constellation) = each($constellationArray[$key]);
	}
	return $constellation;
}

/**
 * 生成验证码
 * @return void
 * @author rohochan <rohochan@gmail.com>
 */
function create_verify(){
	import('ORG.Util.Image');
	Image::buildImageVerify($length=4, $mode=5, $type='png', $width=105, $height=46);
}

/**
 * 检测验证码
 * @param  string $code 验证码
 * @return boolean 检测结果
 * @author rohochan <rohochan@gmail.com>
 */
function check_verify($code){
	if($_SESSION['verify'] == md5($code)) {
		return true;
	}else {
		return false;
	}
}

/**
  * systemLog
  * 记录日志
  * @access default
  * @param string $str
  * @param string $filepath 文件路径
  * @return void
  * @date 2015-06-16
  * @author RohoChan<[email]rohochan@gmail.com[/email]>
  **/
function systemLog($str,$filepath = ''){
	if (is_array($str)) {
		$temp = '';
		foreach ($str as $k => $v) {
			if(is_array($v)) {
				$temp .= "$k:\n";
				foreach ($v as $kk => $vv) {
					$temp .= "\t$kk:$vv\n";
				}
			}else {
				$temp .= "$k:$v\n";
			}
		}
		$str =$temp;
	}
	if (''==$filepath) {
		$filepath = RUNTIME_PATH.'/Logs'.__MODULE__.'/log_'.date('Y-m-d',time()).'.log';
	}
	$fp = fopen($filepath,'a+');
	fwrite($fp, "".date('Y-m-d H:i:s',time()).":\n".$str."\n\n");
	fclose($fp);
}

?>