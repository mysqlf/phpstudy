<?php
define('APP_DEBUG', true);
//测试公众号
//wxb453a86480fa8b64
define('APPID','wxb453a86480fa8b64');
define('APPSECRET','21206ec019cac3f91932d206b006cb18');

/*define('APPID','wx53ac322cae217788');
define('APPSECRET','bd6956ce0ea03a4a54a06bc5d9a25966');*/
//国民妈妈
//define('APPID','wx3bb26d7caff9e9ac');
//define('APPSECRET','b4bd6a29f05cad95ecd956f30fbd8ebb');
//智通到家
//define('APPID','wxb8236fe7eeba9c38');
//define('APPSECRET','e7ac56592ffbf2a35027d17349e2f574');
define('WECHAT_NAME','智通到家');

//define('APPID','wxac7d96f71582d6d0');
//define('APPSECRET','94ecd166c6e6b5db21d8126a5d5add56');

//define('DOMAIN','http://ariaasia.gicp.net/');
define('DOMAIN','http://151808qj20.iask.in/');
//define('DOMAIN','http://rohochan.wicp.net/');
//define('DOMAIN','http://jiaz.hr5156.com/');
//define('IMG_DOMAIN','img.job5156.com/');
define('IMG_DOMAIN','test.job5156.com/');
//define('APP_DOMIN','weixin.hr5156.com/');
//define('APP_DOMIN','test.hr5156.com/');
define('APP_DOMIN','192.168.2.26/');
//define('APP_DOMIN','pt.chitone.cc/');
//define('APP_DOMIN','test2.hr5156.com/');
define('APP_URL','test.job5156.com/');

//define('SMS_DOMAIN','http://192.168.8.166/');
//efine('SMS_DOMAIN','http://192.168.2.120/');
define('SMS_DOMAIN','http://test.job5156.com/');
//define('SMS_DOMAIN','http://api.job5156.com/');

define('BUILD_DIR_SECURE',true);
define('DIR_SECURE_FILENAME', 'index.html,default.html');
define('DIR_SECURE_CONTENT', 'deney Access!');
function filter_vars(&$value)
{
	$value = preg_replace("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|reset|resize|submit)/i","",$value);
	$value = preg_replace("/(.*?)<\/script>/si","",$value);
	$value = preg_replace("/(.*?)<\/iframe>/si","",$value);
	$value = preg_replace ("//iesU", '', $value);	
	/*$value = preg_replace_callback("/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|reset|resize|submit)/i","",$value);
	$value = preg_replace_callback("/(.*?)<\/script>/si","",$value);
	$value = preg_replace_callback("/(.*?)<\/iframe>/si","",$value);
	$value = preg_replace_callback ("//isU", '', $value);*/
	if (!get_magic_quotes_gpc())
	{
		$value = addslashes($value);
	}
}
require '../ThinkPHP/ThinkPHP.php';
?>