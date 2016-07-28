<?php
/**
 * 家政服务
 *
 * @author RohoChan<[email]rohochan@gmail.com[/email]>
 * @version $Id$
 * @copyright `echo TM_ORGANIZATION_NAME`, `date +"%e %B, %Y" | sed 's/^ //'`
 * @package default
 **/

class HomemakingAction extends Action {
	private $fileUrl = '';
	
	/**
	  * _empty
	  * 访问不存在的function时进行404跳转
	  * @access default
	  * @return void
	  * @date 2015-04-28
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	function _empty(){ 
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
		$this->display("Public:404"); 
	} 
	
	/**
	  * _wlog
	  * 记录日志
	  * @access private
	  * @param string $str
	  * @return void
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _wlog($str)
	{
		$fp = fopen('Runtime/Logs/log.txt','a+');
		fwrite($fp, $str);
		fclose($fp);
	}
	
	/**
	 * 根据用户平面坐标
	 * @param  [array] $array 经纬度数组
	 * @return string
	 */
	private function _planeCrood ($array)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , "http://api.map.baidu.com/geoconv/v1/?coords={$array['lng'][0]},{$array['lat'][0]}&from=5&to=6&ak=RvufqHb1h9WY4qwhBmGWs2Wv");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$json = json_decode(curl_exec($ch),true);
		curl_close($ch);
		$array = array('x'=>0,'y'=>0);
		if ($json['result'])
		{
			$array['x'] = $json['result'][0]['x'];
			$array['y'] = $json['result'][0]['y'];
		}
		return $array;
	}
	
	
	/**
	  * getphoto 
	  * 保存微信头像
	  * @access private
	  * @return void
	  * @date 2015-04-29
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function getphoto($uid,$headimgurl){
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
	
	/**
	  * _getphoto2openid 
	  * 以OPENID为文件名保存微信头像
	  * @access private
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _getphoto2openid($openid,$headimgurl){
		//dump($openid);
		//dump($headimgurl);
		if(!empty($openid)){
			$url = $headimgurl;
			//$filename = mkFilePath($uid,'Uploads/Users/','photo');
			//$filename = $filename.'userphoto.jpg';
			$filename = 'Uploads/Weixin/'.$openid.'.jpg';
			clearstatcache();
			//if (empty($url) || !file_exists($url) || filesize($url)<= 1024){
			if(false == empty($url)){
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
			}elseif (false == is_file($filename)){
				$defaultfile = 'Uploads/Weixin/userphoto.jpg';
				if (!copy($defaultfile, $filename)) {
					echo "failed to copy $defaultfile to $filename\n";
				}
			}
		}
	}
	
	/**
	  * _randString 
	  * 根据给定的关键字生成特定长度随机字符串
	  * @access private
	  * @param string $len 指定字符串长度
	  * @param string $keyword 指定字符串关键字
	  * @return string
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _randString($len = 16, $keyword = '') {
		if (strlen($keyword) > $len) {//关键字不能比总长度长
			return false;
		}
		$str = '';
		$chars = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKMNPQRSTUVWXYZ'; //去掉1跟字母l防混淆
		if ($len > strlen($chars)) {//位数过长重复字符串一定次数
			$chars = str_repeat($chars, ceil($len / strlen($chars)));
		}
		$chars = str_shuffle($chars); //打乱字符串
		$str = substr($chars, 0, $len);
		if (!empty($keyword)) {
			$start = $len - strlen($keyword);
			$str = substr_replace($str, $keyword, mt_rand(0, $start), strlen($keyword)); //从随机位置插入关键字
		}
		return $str;
	}
	
	/**
	  * _objectToArray
	  * 将对象转换成数组
	  * @access private
	  * @param object $obj
	  * @return array
	  * @date 2015-06-01
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _objectToArray($obj){
		$_arr = is_object($obj) ? get_object_vars($obj) :$obj;
		foreach ($_arr as $key=>$val){
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val):$val;
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	/**
	  * _xml2array
	  * 将XML转换成数组
	  * @access private
	  * @param string $str
	  * @return array
	  * @date 2015-06-4
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _xml2array($str ='')
	{
		$res = @simplexml_load_string($str,NULL,LIBXML_NOCDATA);
		$res = json_decode(json_encode($res),true);
		return $res;
	}
	
	/**
	  * _getBetween
	  * 根据头尾字符串查找中间字符串
	  * @access private
	  * @param string $input 源字符串
	  * @param string $start 要查找的字符串头部
	  * @param string $end 要查找的字符串尾部
	  * @return string
	  * @date 2015-06-15
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _getBetween($input, $start, $end) {
	  $substr = substr($input, strlen($start)+strpos($input, $start),
	  			(strlen($input) - strpos($input, $end))*(-1));
	  return $substr;
	}
	
	/**
	  * _timeFormat
	  * 时间格式转换
	  * @access private
	  * @param string $input 源字符串
	  * @param string $start 要查找的字符串头部
	  * @param string $end 要查找的字符串尾部
	  * @return string
	  * @date 2015-06-02
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function _timeFormat($time){
		//当前时间-发布时间
		$now = time();
		$time = strtotime($time);
		//当天时间
		$today = strtotime(date('Y-m-d')); 
		//昨天时间
		$yesterday = strtotime('-1 day',$today);
		//前天时间
		$byesterday = strtotime('-2 day',$today);
		//当前时间-发布时间
		$diff = $now - $time;
		$str = '';
		switch (true) {
			//当前时间-发布时间小于60秒时
			case $diff < 60:
				$str = '刚刚';
				break;
			//当前时间-发布时间小于1小时
			case $diff < 3600:
				$str = floor($diff/60).'分钟前';
				break;
			//当前时间-发布时间小于8小时
			case $diff < (3600*8):
				$str = floor($diff/3600).'小时前';
				break;
			//发布时间大于今天的0时0分
			case $time >= $today:
				$str = '今天 '.date('H:i:s',$time);
				break;
			//发布时间大于昨天的0时0分
			case $time >= $yesterday:
				$str = '昨天 '.date('H:i:s',$time);
				break;
			//发布时间大于昨天的0时0分
			case $time >= $byesterday:
				$str = '前天 '.date('H:i:s',$time);
				break;
			//否则显示发布时间
			default:
				$str = date('Y-m-d H:i:s',$time);
				break;
		}
		return $str;
	}
	
	//测试url编码到底是gbk 还是utf8编码
	function check_type1($url){
		//这是思路1
		$url=urldecode($url);
		$temp1=iconv("GBK","UTF-8",$url);
		$temp2=iconv("UTF-8","GBK",$temp1);
		//var_dump($temp1,$temp2);
		if($temp2==$url){
			//echo 'it is gbk';
			echo $temp1;
			echo $temp2;
		}
		else{
			//echo 'it is utf8';
			echo urldecode($url);
		}
	}

	function check_type2($url){
		//这是思路2
		$url=urldecode($url);
		@trigger_error('error', E_USER_NOTICE);
		$temp1=@iconv("GBK","UTF-8",$url);
		$error=error_get_last();
		if($error['message']!='error')
			echo "it is utf8";
		else
			echo 'it is gbk';
	}
	
	/**
	  * _checkUrlcode
	  * 识别URL格式并转换
	  * @access private
	  * @param string $url 源字符串
	  * @return string
	  * @date 2015-06-13
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	function _checkUrlcode($url){
		//这是思路1
		$url=urldecode($url);
		$temp1=iconv("GBK","UTF-8",$url);
		$temp2=iconv("UTF-8","GBK",$temp1);
		//var_dump($temp1,$temp2);
		if($temp2==$url){
			//echo 'it is gbk';
			return $temp2;
		}
		else{
			//echo 'it is utf8';
			return urldecode($url);
		}
	}
	
	/**
	 * _substrCut
	 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
	 * @param string $user_name 姓名
	 * @return string 格式化后的姓名
	 * @date 2015-06-19
	 * @author RohoChan<[email]rohochan@gmail.com[/email]>
	 */
	function _substrCut($user_name){
		$strLength	= mb_strlen($user_name, 'utf-8');
		$firstStr	= mb_substr($user_name, 0, 1, 'utf-8');
		$lastStr	= mb_substr($user_name, -1, 1, 'utf-8');
		//return $strLength == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strLength - 2) . $lastStr;
		return $strLength == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strLength - 1);
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
	
	//获取手机验证码,并发送到用户手机上
	public function getcode(){
		$phone = I('post.phoneVal');
		$verify = I('post.verify');
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
			/* 检测验证码 */
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
						//echo "true";
						$time = time();
						session('time',$time);
						sendPhoneMsg($phone,$msg);
						$m->increment($phone,1);
						$log['isSend'] = true;
						systemLog(json_encode($log));
						$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！'));
					}else{
						//echo '1';
						$log['isSend'] = false;
						systemLog(json_encode($log));
						$this->ajaxReturn(array('status'=>0,'info'=>'发送失败，每天只能发送5条短信！'));
					}
				}else{
					$m->set($phone, 1, 0, 3600*24);
					$code =  rand(100000,999999);
					session('code',$code);
					session('codeLifeTime',time());
					$msg = "【".WECHAT_NAME."】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
					//echo "true";
					$time = time();
					session('time',$time);
					sendPhoneMsg($phone,$msg);
					$log['isSend'] = true;
					systemLog(json_encode($log));
					$this->ajaxReturn(array('status'=>1,'info'=>'发送成功！'));
				}
			}
		}
	}
	
	/**
	  * _isBindMobile
	  * 使用接口判断手机是否允许注册
	  * @access private
	  * @param string $phone 手机号
	  * @return boolean
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _isBindMobile($phone){
		$url = APP_DOMIN.'Chitone-Account-allowBindMobile';
		$data = urlencode(json_encode(array('user_telphone'=>$phone)));
		$res = _get($url,$data);
		if ($res && $res['status'] == 200103) {//手机号码已被占用
			//$this->ajaxReturn('false');
			return false;
		}else if($res && $res['status'] == 0) {//手机号码未被占用
			//$this->ajaxReturn('true');
			return true;
		}else {//其他情况
			//$this->ajaxReturn('false');
			return false;
		}
	}
	
	/**
	  * isBindMobile
	  * 使用接口判断手机是否允许注册
	  * @access public
	  * @return json
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function isBindMobile(){
		if (IS_AJAX) {
			if (session('uid')) {
				$uid = session('uid');
			}else {
				$account_info = D('AccountInfo');
				$uid = $account_info->logining();
			}
			if ($uid) {
				$this->ajaxReturn('true');
			}else {
				$phone = I('post.phone');
				if ($this->_isBindMobile($phone)) {
					$this->ajaxReturn('true');
				}else {
					$this->ajaxReturn('false');
				}
			}
		}else {
			$this->error('非法操作！');
		}
	}
	
	/**
	  * isAllowReg
	  * 使用接口判断是否允许注册
	  * @access public
	  * @return json
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function isAllowReg(){
		if(IS_AJAX){
			if (session('uid')) {
				$uid = session('uid');
			}else {
				$account_info = D('AccountInfo');
				$uid = $account_info->logining();
			}
			if ($uid) {
				$this->ajaxReturn('logged');
			}else {
				$phone['user_account'] = I('post.phone');
				//$account_info = D('AccountInfo');
				//$res = $account_info->accountAssign($phone);
				//$res =  $account_info->where($phone)->find();
				$url = APP_DOMIN.'Chitone-Account-allowReg';
				$data =  urlencode(json_encode($phone));
				$res = _get($url,$data);
				$status = $res['status'];
				if ($status){
					$res = _get(APP_DOMIN.'Chitone-Account-allowReReg',$data);
					if ($res['status'] === 0){
						$this->ajaxReturn('allowReReg');
						//exit('true');
					}else {
						$this->ajaxReturn('forbidReReg');
						//exit('false');
					}
				}else{
					$this->ajaxReturn('allowReg');
					//exit('true');
				}
			}
		}else{
			$this->error('非法操作！');
		}
	}
	
	/**
	  * _isBind
	  * 根据openid查找绑定表是否已绑定
	  * @access private
	  * @return void
	  * @date 2015-06-01
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _isBind(){
		$homemakingUserBind = M('homemaking_user_bind');
		$result = $homemakingUserBind->field('fldId,fldOpenId,fldOpenName,fldOpenType,fldType')->where(array('fldOpenId'=>session('openid'),'fldStatus'=>1))->find();
		if(!$result){
			$data['fldOpenId'] = session('openid');
			$data['fldOpenName'] = session('openname');
			$data['fldOpenType'] = 'weixin';
			$data['fldType'] = 1;
			$data['fldStatus'] = 1;
			$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
			$data['fldOwner'] = 'admin';
			$data['lastEditdt'] = date('Y-m-d H:i:s',time());
			$data['lastEditby'] = 'admin';
			$result = $homemakingUserBind->add($data);
			if($result){
				session('bindid',$result);
			}
		}elseif($result['fldOpenName'] != session('openname')){
			session('bindid',$result['fldId']);
			$result = $homemakingUserBind->where(array('fldId'=>$result['fldId']))->save(array('fldOpenName'=>session('openname')));
		}else{
			session('bindid',$result['fldId']);
		}
	}
	
	/**
	 *  cleanCache function
	 *  清除缓存
	 * @return void
	 * @author rohochan@gmail.com
	 **/
	function cleanCache(){
		session('bindid',null);
		session('openid',null);
		session('uid',null);
		session('per_user_id',null);
		cookie('per',null);
		S('gradePriceArray',null);
		S('gradeCertificateMap',null);
		S('workyearsMap',null);
		S('wy',null);
		S('locationsMap',null);
		header("content-type:text/html;charset='UTF-8'");
		import('ORG.Util.Jssdk');
			$jssdk = new JSSDK(APPID, APPSECRET);
			$signPackage = $jssdk->GetSignPackage();
			exit("<Meta http-equiv='Content-Type' Content='text/html; Charset=utf-8'>
				<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
				<script>
					wx.config({
						debug: false,
						appId: '{$signPackage['appId']}',
						timestamp: {$signPackage['timestamp']},
						nonceStr: '{$signPackage['nonceStr']}',
						signature: '{$signPackage['signature']}',
						jsApiList: [
						  // 所有要调用的 API 都要加到这个列表中
						  'getLocation','openLocation','closeWindow'
						  //'getLocations,openLocation'
						  
						]
					  });
					wx.ready(function () {
						 alert('清理完毕！');
					 	 wx.closeWindow();
					});
				 </script>");
		//echo "清理完毕！";
	}
	
	/**
	  * index
	  * 程序入口
	  * @access public
	  * @return void
	  * @date 2015-04-28
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function index(){
		/*session('bindid',null);
		session('openid',null);
		session('uid',null);
		session('per_user_id',null);
		cookie('per',null);*/
		//session('openid','oRaVSuKw47upvPuyZmIOu9tj1gf0');session('bindid',1);
		//session('openid','oRaVSuOnAYErp1IVrhv2AhyihRlc');session('bindid',3);
		//session('openid','oRaVSuFVW5hTI_F-YljsNXQ0Q6BI');session('bindid',4);
		//session('openid','oeRcKt5KFJwkhApXJyrrACBh_if8');session('bindid',34);
		if(session('openid')){
			$openid = session('openid');
		}
		else{
			$code = I('get.code',0);
			$openid = _openid($code);
			session('openid',$openid);
			session('weixin_code',$code);
		}
		/*if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}*/
		$userinfo = userinfo($openid);
		session('openname',$userinfo['nickname']);
		if(!session('bindid')){
			$this->_isBind();
		}
		$this->_getphoto2openid($openid,$userinfo['headimgurl']);
		$type = I('get.t');
		if($type == "requirement"){
			//我要预约
			//$this->requirement();
			redirect(U('Homemaking-requirement',array('v'=>time(),'type'=>I('param.type')),''));
		}elseif($type == "customerCenter"){
			//客户中心
			//$this->customerCenter();
			redirect(U('Homemaking-customerCenter',array('v'=>time()),''));
		}elseif($type == "motherBabyQA"){
			//母婴问答
			//$this->motherBabyQA();
			redirect(U('Homemaking-motherBabyQA',array('v'=>time()),''));
		}elseif($type == "parentingInformation"){
			//育儿资讯
			//$this->parentingInformation();
			redirect(U('Homemaking-parentingInformation',array('v'=>time()),''));
		}elseif($type == "introduceYuesao"){
			//月嫂介绍
			//redirect('Homemaking-introduceYuesao');
			redirect(U('Homemaking-introduceYuesao',array('v'=>time()),''));
		}elseif($type == "introduceYuyingsao"){
			//育婴嫂介绍
			//redirect('Homemaking-introduceYuyingsao');
			redirect(U('Homemaking-introduceYuyingsao',array('v'=>time()),''));
		}elseif($type == "login"){
			//redirect('Account-login');
			redirect(U('Account-login',array('v'=>time()),''));
		}elseif ($type=="customizeYuesao") {
			//定制月嫂
			redirect(U('Homemaking-customizeYuesao',array('v'=>time()),''));
		}elseif ($type=='customizeYuyingshi') {
			//定制育婴师
			redirect(U('Homemaking-customizeYuyingshi',array('v'=>time()),''));
		}elseif ($type=='customizeYueguanjia') {
			//定制粤管家
			redirect(U('Homemaking-customizeYueguanjia',array('v'=>time()),''));# code...
		}elseif ($type=='impression') {
			//企业文化
			redirect(U('Homemaking-impression',array('v'=>time()),''));# code...
		}elseif ($type=="customize") {
			//关于私享家
			redirect(U('Homemaking-customize',array('v'=>time()),''));
		}else {
			//redirect(U('Homemaking-_empty',array('v'=>time()),''));
			$this->_empty();
		}
	}
	
	/**
	 * [impression 企业文化]
	 * @return [type] 
	 */
	public function impression(){
		$this->display('impression');
	}
	
	/**
	 * [customizeYuesao 定制月嫂]
	 * @access public
	 * @return [void] 
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function customizeYuesao(){
		header("content-type:text/html;charset='UTF-8'");
		/*if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{*/
			$this->assign('type',1);
			$this->display('customizeYuesao');
		//}
	}
	
	/**
	 * [customizeYuyingshi 定制育婴师]
	 * @access public
	 * @return [void] 
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function customizeYuyingshi(){
		header("content-type:text/html;charset='UTF-8'");
		/*if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{*/
			$this->assign('type',2);
			$this->display('customizeYuyingshi');
		//}
	}
	
	/**
	 * [customizeYueguanjia 定制粤管家]
	 * @access public
	 * @return [void] 
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function customizeYueguanjia(){
		header("content-type:text/html;charset='UTF-8'");
		/*if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{*/
			$this->assign('type',3);
			$this->display('customizeYueguanjia');
		//}
	}
	
	/**
	 * [confirmcustommade 提交定制]
	 * @access public
	 * @return [Boolean] 
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function confirmcustommade(){
		/*if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{*/
			$type=I('param.type');//服务类型
			$tel=I('param.tel');//电话
			if (!mobileFormat($tel)) {
				$this->ajaxReturn('3');exit('false');
			}else{
				$data['fldTel']=substr($tel,strlen($tel)-11);
			}
			$data['fldClaim']=I('param.claim');//要求
			$data['fldCreatetime']=date('Y-m-d H:i:s',time());//提交时间
			$custommade=M('homemaking_custommade');
			switch ($type) {
				case '1':
					$data['fldType']=1;
					$Promisetime=strtotime(I('param.promisetime'));
					if (!$Promisetime) {
						$this->ajaxReturn('2');exit('false');//时间过滤
					}
					$data['fldPromisetime']=I('param.promisetime');//估计时间
					break;
				case '2':
					$data['fldType']=2;
					$Promisetime=strtotime(I('param.promisetime'));
					if (!$Promisetime) {
						$this->ajaxReturn('2');exit('false');//时间过滤
					}
					$data['fldPromisetime']=I('param.promisetime');//估计时间
					break;
				case '3':
					$data['fldType']=3;
					$data['fldHousekeeper']=I('param.housekeeper');//粤管家类别0单品,1套餐
					break;
			}
	
			$check=true;
			$check=self::checkcustommade($data);//检测用户是否在短期内已经定制服务
			if (!$check) {
				$this->ajaxReturn('1');
			}else{
				$result=$custommade->add($data);
				if($result){
					$this->ajaxReturn('true');
				}else {
					$this->ajaxReturn('false');
				}
			}
			
		//}
	}
	
	/**
	 * [checkcustommade 检查用户短期内是否已经定制过该类服务]
	 * @access public
	 * @return [Boolean] 
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function checkcustommade($data){
			$custommade=M('homemaking_custommade');
			$where=array(
						 'fldTel'			=>	$data['fldTel'],
						 'fldType'			=>	$data['fldType']
					);
			$result=$custommade->field('fldCreatetime')->where($where)->select();
			if (strtotime('-1 month')<strtotime($result['0']['fldCreatetime'])){
				return false;
			}else{
				return true;
			}
		
	}
	
	/**
	 * [customize 关于私享家]
	 * @access public
	 * @return void
	 * @date 2016-06-01
	 * @author zuolang
	 */
	public function customize(){
		$this->display('customize');
	}
	
	/**
	  * introduceYuesao
	  * 月嫂介绍
	  * @access public
	  * @return void
	  * @date 2015-06-02
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function introduceYuesao()
	{
		$this->display('introduceYuesao');
	}
	
	/**
	  * introduceYuyingsao
	  * 育婴嫂介绍
	  * @access public
	  * @return void
	  * @date 2015-06-02
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function introduceYuyingsao()
	{
		$this->display('introduceYuyingsao');
	}
	
	/**
	  * motherBabyQA
	  * 母婴问答首页
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQA(){
		header("content-type:text/html;charset='UTF-8'");
		if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{
			$datestamp = time();
			//获取用户点赞帖子ID
			if(!session('communityLike')){
				$homemakingCommunityLike = M('homemaking_community_like');
				$homemakingCommunityLikeResult = $homemakingCommunityLike->field('fldCommunityId')->where(array('fldBindId'=>session('bindid'),'fldStatus'=>1))->select();
				$communityLike = array();
				foreach ($homemakingCommunityLikeResult as $key => $value) {
					$communityLike[$value['fldCommunityId']] = $value['fldCommunityId'];
				}
				session('communityLike',$communityLike);
			}
			$homemakingCommunity = M('HomemakingCommunity','',DB_CONFIG3);
			$page = I('param.page',1)-1;
			$map = array(' fldClass = 1 and fldAuditStatus in (0,1) and homemaking_community.fldType = 1 ');
			$list = $homemakingCommunity->field('homemaking_community.fldId,homemaking_community.fldContent,homemaking_community.fldViewNum,homemaking_community.fldReplyNum,homemaking_community.fldLikeNum,homemaking_community.fldLocation,CONVERT(varchar(100), homemaking_community.fldCreateDate, 120) as fldCreateDate,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where($map)->join('left join homemaking_user_bind on homemaking_community.fldBindId = homemaking_user_bind.fldId')->order('fldCreateDate desc')->limit($page*10,10)->select();
			
			foreach ($list as $key => $value) {
				//判断是否已经点赞
				if (in_array($value['fldId'],session('communityLike'))) {
					$list[$key]['isLike'] = 1;
				}else{
					$list[$key]['isLike'] = 0;
				}
				
				//查询评论
				$homemakingCommunityReply = M('homemaking_community_reply','',DB_CONFIG3);
				$homemakingCommunityReplyResult = $homemakingCommunityReply->field('homemaking_community_reply.fldContent,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where(array('fldCommunityId'=>$value['fldId'],'fldShielding'=>0))->join('left join homemaking_user_bind on homemaking_community_reply.fldBindId = homemaking_user_bind.fldId')->limit(3)->select();
				//echo $homemakingCommunityReply->getDbError();
				$list[$key]['reply'] = $homemakingCommunityReplyResult;
				
				//图片Url
				if (!empty($value['fldOpenId'])) {
					$list[$key]['PhotoUrl'] = "Uploads/Weixin/{$value['fldOpenId']}.jpg";
				}else {
					$list[$key]['PhotoUrl'] = "Uploads/Weixin/userphoto.jpg";
				}
				
				//处理时间
				$list[$key]['fldCreateDate'] = $this->_timeFormat($value['fldCreateDate']);
				$list[$key]['v'] = $datestamp;
			}
			if (IS_AJAX) {
				if (empty($list)) {
					$this->ajaxReturn('');
				}else {
					$this->ajaxReturn($list);
				}
			}else {
				import('ORG.Util.Jssdk');
				$jssdk = new JSSDK(APPID, APPSECRET);
				$signPackage = $jssdk->GetSignPackage();
				$this->assign('signPackage',$signPackage);
				$this->assign('list',$list);
			}
			$this->display('motherBabyQA');
		}
	}
	
	/**
	  * motherBabyQALike
	  * 母婴问答点赞
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQALike(){
		if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{
			$homemakingCommunityLike = M('homemaking_community_like');
			$homemakingCommunity = M('HomemakingCommunity');
			//不存在点赞记录点赞
			if(!in_array(I('param.id'),session('communityLike'))){
				$result = $homemakingCommunityLike->field('fldStatus')->where(array('fldCommunityId'=>I('param.id'),'fldBindId'=>session('bindid')))->find();
				//存在记录则更新记录，否则插入记录
				if ($result) {
					$data['fldCommunityId'] = I('param.id');
					$data['fldBindId'] = session('bindid');
					$data['fldStatus'] = 1;
					$data['lastEditdt'] = date('Y-m-d H:i:s',time());
					//$data['lastEditby'] = 'admin';
					$changeResult = $homemakingCommunityLike->where(array('fldCommunityId'=>I('param.id'),'fldBindId'=>session('bindid')))->save($data);
				}else{
					$data['fldCommunityId'] = I('param.id');
					$data['fldBindId'] = session('bindid');
					$data['fldStatus'] = 1;
					$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$data['fldOwner'] = 'admin';
					$data['lastEditdt'] = date('Y-m-d H:i:s',time());
					$data['lastEditby'] = 'admin';
					$changeResult = $homemakingCommunityLike->add($data);
				}
				if($changeResult){
					$communityLike = session('communityLike');
					$communityLike[$data['fldCommunityId']] = $data['fldCommunityId'];
					session('communityLike',$communityLike);
					$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->setInc('fldLikeNum');
					if($updateResult){
						$this->ajaxReturn('true');
						//$this->success('点赞成功',U('Homemaking-motherBabyQA','',''));
					}else {
						$this->ajaxReturn('false');
						//$this->error('点赞失败',U('Homemaking-motherBabyQA','',''));
					}
				}else{
					$this->ajaxReturn('false');
					//$this->error('点赞失败!',U('Homemaking-motherBabyQA','',''));
				}
			}else{
				//已经点赞,取消点赞
				//echo "已经点赞,取消点赞";
				$data['fldCommunityId'] = I('param.id');
				$data['fldBindId'] = session('bindid');
				$data['fldStatus'] = 0;
				$data['lastEditdt'] = date('Y-m-d H:i:s',time());
				//$data['lastEditby'] = 'admin';
				$updateResult = $homemakingCommunityLike->where(array('fldCommunityId'=>I('param.id'),'fldBindId'=>session('bindid')))->save($data);
				if($updateResult){
					$communityLike = session('communityLike');
					unset($communityLike[I('param.id')]);
					session('communityLike',$communityLike);
					$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->setDec('fldLikeNum');
					if($updateResult){
						$this->ajaxReturn('true');
						//$this->success('取消点赞成功',U('Homemaking-motherBabyQA','',''));
					}else {
						$this->ajaxReturn('false');
						//$this->success('取消点赞失败',U('Homemaking-motherBabyQA','',''));
					}
				}else{
					$this->ajaxReturn('false');
					//$this->success('取消点赞失败',U('Homemaking-motherBabyQA','',''));
				}
			}
		}
	}
	
	/**
	  * motherBabyQADetail
	  * 母婴问答详情页
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQADetail(){
		if(IS_POST && !IS_AJAX){
			if(!session('bindid')){
				$this->error('请在微信上打开，或者重新进入页面');
			}else{
				$data['fldCommunityId'] = I('param.id');
				$data['fldBindId'] = session('bindid');
				$data['fldContent'] = I('param.shuoshuo');
				$data['fldReplyType'] = 1;
				$data['fldReplyUserId'] = 0;
				//$data['fldUploadFile'] = trim(com_create_guid(),'{}');
				$data['fldUploadFile'] = trim(getGUID(),'{}');
				$data['fldShielding'] = 0;
				$data['fldOwner'] = 'admin';
				$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homemakingCommunityReply = M('homemaking_community_reply');
				$result = $homemakingCommunityReplyResult = $homemakingCommunityReply->add($data);
				if ($result) {
					$homemakingCommunity = M('HomemakingCommunity');
					$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->setInc('fldReplyNum');
					//$this->success('回复成功');
					redirect(U('Homemaking-motherBabyQADetail',array('v'=>time(),'id'=>I('param.id')),''));
				}else {
					$this->error('回复失败');
				}
			}
		}else{
			if(!I('param.id')){
					$this->error('非法操作',U('Homemaking-motherBabyQA','',''));
			}else{
				$homemakingCommunity = M('HomemakingCommunity');
				$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->setInc('fldViewNum');
				$homemakingCommunityContent = $homemakingCommunity->field('homemaking_community.fldId,homemaking_community.fldBindId,homemaking_community.fldContent,homemaking_community.fldViewNum,homemaking_community.fldReplyNum,homemaking_community.fldLikeNum,homemaking_community.fldLocation,CONVERT(varchar(100), homemaking_community.fldCreateDate, 120) as fldCreateDate,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where(array('homemaking_community.fldId'=>I('param.id'),'_string'=>'fldAuditStatus in (0,1)'))->join('left join homemaking_user_bind on homemaking_community.fldBindId = homemaking_user_bind.fldId')->find();
				if (empty($homemakingCommunityContent)) {
					$this->error('非法操作',U('Homemaking-motherBabyQA','',''));
				}else{
					//图片Url
					if (!empty($homemakingCommunityContent['fldOpenId'])) {
						$homemakingCommunityContent['PhotoUrl'] = "Uploads/Weixin/{$homemakingCommunityContent['fldOpenId']}.jpg";
					}else {
						$homemakingCommunityContent['PhotoUrl'] = "Uploads/Weixin/userphoto.jpg";
					}
					//处理时间
					$homemakingCommunityContent['fldCreateDate'] = $this->_timeFormat($homemakingCommunityContent['fldCreateDate']);
					//echo $homemakingCommunity->getDbError();
					
					//读取评论
					$page = I('param.page',1)-1;
					$homemakingCommunityReply = M('homemaking_community_reply','',DB_CONFIG3);
					$homemakingCommunityReplyResult = $homemakingCommunityReply->field('homemaking_community_reply.fldId,homemaking_community_reply.fldBindId,homemaking_community_reply.fldContent,CONVERT(varchar(100), homemaking_community_reply.fldCreateDate, 120) as fldCreateDate,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where(array('fldCommunityId'=>I('param.id'),'fldShielding'=>0))->join('left join homemaking_user_bind on homemaking_community_reply.fldBindId = homemaking_user_bind.fldId')->limit($page*10,10)->select();
					foreach ($homemakingCommunityReplyResult as $key => $value) {
						//图片Url
						if (!empty($value['fldOpenId'])) {
							$homemakingCommunityReplyResult[$key]['PhotoUrl'] = "Uploads/Weixin/{$value['fldOpenId']}.jpg";
						}else {
							$homemakingCommunityReplyResult[$key]['PhotoUrl'] = "Uploads/Weixin/userphoto.jpg";
						}
						
						//处理时间
						$homemakingCommunityReplyResult[$key]['fldCreateDate'] = $this->_timeFormat($value['fldCreateDate']);
						
						//是否楼主
						if ($value['fldBindId'] == $homemakingCommunityContent['fldBindId']) {
							$homemakingCommunityReplyResult[$key]['isAuthor'] = 1;
						}else {
							$homemakingCommunityReplyResult[$key]['isAuthor'] = 0;
						}
					}
					//获取用户点赞帖子ID
					if(!session('communityLike')){
						$homemakingCommunityLike = M('homemaking_community_like');
						$homemakingCommunityLikeResult = $homemakingCommunityLike->field('fldCommunityId')->where(array('fldBindId'=>session('bindid'),'fldStatus'=>1))->select();
						$communityLike = array();
						foreach ($homemakingCommunityLikeResult as $key => $value) {
							$communityLike[$value['fldCommunityId']] = $value['fldCommunityId'];
						}
						session('communityLike',$communityLike);
					}
					//判断是否已经点赞
					if (in_array($homemakingCommunityContent['fldId'],session('communityLike'))) {
						$homemakingCommunityContent['isLike'] = 1;
					}else{
						$homemakingCommunityContent['isLike'] = 0;
					}	
					//echo $homemakingCommunityReply->getDbError();
					if (IS_AJAX) {
						if (empty($homemakingCommunityReplyResult)) {
							$this->ajaxReturn('');
						}else {
							$this->ajaxReturn($homemakingCommunityReplyResult);
						}
					}else {
						import('ORG.Util.Jssdk');
						$jssdk = new JSSDK(APPID, APPSECRET);
						$signPackage = $jssdk->GetSignPackage();
						$this->assign('signPackage',$signPackage);
						$this->assign('homemakingCommunityContent',$homemakingCommunityContent);
						$this->assign('homemakingCommunityReplyResult',$homemakingCommunityReplyResult);
					}
					$this->display();
				}
			}
		}
		
	}
	
	/**
	  * motherBabyQADelete
	  * 删除母婴问答
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQADelete(){
		if (IS_AJAX) {
			$homemakingCommunity = M('HomemakingCommunity');
			$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->save(array('fldAuditStatus'=>3));
			if ($updateResult) {
				$this->ajaxReturn('true');
				//echo json_encode('true');
				//$this->success('删除成功','/Homemaking-motherBabyQA');
			}else {
				$this->ajaxReturn('false');
				//$this->error('删除失败');
			}
		}
	}
	
	/**
	  * motherBabyQAIssue
	  * 发布问题
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQAIssue(){
		//没有openid报错
		//if(!session('openid') || !session('bindid')){
		if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{
			$community = M('homemaking_community');
			$data['fldType'] = 1;
			$data['fldPersonId'] = 0;
			$data['fldClass'] = 1;
			$data['fldTitle'] = '母婴问答';
			$data['fldContent'] = I('post.shuoshuo');
			$data['fldLocation'] = I('post.location');
			$data['fldViewNum'] = 0;
			$data['fldReplyNum'] = 0;
			$data['fldBindId'] = session('bindid');
			$data['fldLikeNum'] = 0;
			//$data['fldUploadFile'] = trim(com_create_guid(),'{}');
			$data['fldUploadFile'] = trim(getGUID(),'{}');
			$data['fldOwner'] = 'admin';
			$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
			$result = $community->add($data);
			if($result){
				//redirect(U('Homemaking-motherBabyQA'));
				redirect(U('Homemaking-motherBabyQAIssueSuccess',array('id'=>$result,'v'=>time())));
			}else{
				//redirect(U('Homemaking-motherBabyQA'),'','','提交失败');
				$this->error('提交失败');
			}
		}
	}
	
	/**
	  * motherBabyQAIssueSuccess
	  * 发布问题成功
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function motherBabyQAIssueSuccess(){
		header("content-type:text/html;charset='UTF-8'");
		if(!session('bindid')){
			$this->error('请在微信上打开，或者重新进入页面');
		}else{
			if(!I('param.id')){
					$this->error('非法操作',U('Homemaking-motherBabyQA','',''));
			}else{
				$homemakingCommunity = M('HomemakingCommunity','',DB_CONFIG3);
				$updateResult = $homemakingCommunity->where(array('fldId'=>I('param.id')))->setInc('fldViewNum');
				$homemakingCommunityContent = $homemakingCommunity->field('homemaking_community.fldId,homemaking_community.fldBindId,homemaking_community.fldContent,homemaking_community.fldViewNum,homemaking_community.fldReplyNum,homemaking_community.fldLikeNum,homemaking_community.fldLocation,CONVERT(varchar(100), homemaking_community.fldCreateDate, 120) as fldCreateDate,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where('homemaking_community.fldId='.I('param.id').' and fldAuditStatus in (0,1)')->join('left join homemaking_user_bind on homemaking_community.fldBindId = homemaking_user_bind.fldId')->find();
				//echo $homemakingCommunity->getLastSql();//die();
				if (empty($homemakingCommunityContent)) {
					$this->error('非法操作',U('Homemaking-motherBabyQA','',''));
				}
				//图片Url
				if (!empty($homemakingCommunityContent['fldOpenId'])) {
					$homemakingCommunityContent['PhotoUrl'] = "Uploads/Weixin/{$homemakingCommunityContent['fldOpenId']}.jpg";
				}else {
					$homemakingCommunityContent['PhotoUrl'] = "Uploads/Weixin/userphoto.jpg";
				}
				
				//处理时间
				$homemakingCommunityContent['fldCreateDate'] = $this->_timeFormat($homemakingCommunityContent['fldCreateDate']);
			}
			//获取用户点赞帖子ID
			if(!session('communityLike')){
				$homemakingCommunityLike = M('homemaking_community_like');
				$homemakingCommunityLikeResult = $homemakingCommunityLike->field('fldCommunityId')->where(array('fldBindId'=>session('bindid'),'fldStatus'=>1))->select();
				$communityLike = array();
				foreach ($homemakingCommunityLikeResult as $key => $value) {
					$communityLike[$value['fldCommunityId']] = $value['fldCommunityId'];
				}
				session('communityLike',$communityLike);
			}
			$map = array(' fldClass = 1 and fldAuditStatus in (0,1) and homemaking_community.fldType = 1 and homemaking_community.fldId != '.I('param.id'));
			$list = $homemakingCommunity->field('homemaking_community.fldId,homemaking_community.fldContent,homemaking_community.fldViewNum,homemaking_community.fldReplyNum,homemaking_community.fldLikeNum,homemaking_community.fldLocation,CONVERT(varchar(100), homemaking_community.fldCreateDate, 120) as fldCreateDate,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where($map)->join('left join homemaking_user_bind on homemaking_community.fldBindId = homemaking_user_bind.fldId')->order('fldCreateDate desc')->limit(5)->select();
			foreach ($list as $key => $value) {
				//判断是否已经点赞
				if (in_array($value['fldId'],session('communityLike'))) {
					$list[$key]['isLike'] = 1;
				}else{
					$list[$key]['isLike'] = 0;
				}
				
				//查询评论
				$homemakingCommunityReply = M('homemaking_community_reply');
				$homemakingCommunityReplyResult = $homemakingCommunityReply->field('homemaking_community_reply.fldContent,homemaking_user_bind.fldOpenId,homemaking_user_bind.fldOpenName')->where(array('fldCommunityId'=>$value['fldId']))->join('left join homemaking_user_bind on homemaking_community_reply.fldBindId = homemaking_user_bind.fldId')->select();
				$list[$key][reply] = $homemakingCommunityReplyResult;
				
				//图片Url
				if (!empty($value['fldOpenId'])) {
					$list[$key]['PhotoUrl'] = "Uploads/Weixin/{$value['fldOpenId']}.jpg";
				}else {
					$list[$key]['PhotoUrl'] = "Uploads/Weixin/userphoto.jpg";
				}
				
				//处理时间
				$list[$key]['fldCreateDate'] = $this->_timeFormat($value['fldCreateDate']);
			}
			if (IS_AJAX) {
				if (empty($list)) {
					$this->ajaxReturn('');
				}else {
					$this->ajaxReturn($list);
				}
			}else {
				import('ORG.Util.Jssdk');
				$jssdk = new JSSDK(APPID, APPSECRET);
				$signPackage = $jssdk->GetSignPackage();
				$this->assign('signPackage',$signPackage);
				$this->assign('homemakingCommunityContent',$homemakingCommunityContent);
				$this->assign('list',$list);
			}
			$this->display('motherBabyQAIssueSuccess');
		}
	}
	 
	/**
	  * parentingInformation
	  * 母婴资讯
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function parentingInformation(){
		$homemakingCommunityclass = M('HomemakingCommunityclass');
		$result = $homemakingCommunityclass->field('fldId,fldClassName,CONVERT(varchar(100), fldCreateDate, 120) as fldCreateDate')->select();
		$homemakingCommunityitem = M('homemakingCommunityitem');
		foreach ($result as $key => $value) {
			$homemakingCommunityitemResult = $homemakingCommunityitem->field('fldId,fldName,CONVERT(varchar(100), fldCreateDate, 120) as fldCreateDate')->where(array('fldCommunityClass'=>$value['fldId']))->select();
			if ($homemakingCommunityitemResult) {
				$result[$key]['homemaking_communityitem'] = $homemakingCommunityitemResult;
			}
		} 
		$this->assign('list',$result);
		$this->assign('timestamp',time());
		$this->display('parentingInformation');
	}
	
	/**
	  * parentingInformationSearch
	  * 母婴资讯搜索
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function parentingInformationSearch(){
		header("content-type:text/html;charset=utf-8");	
		if(I('param.id')){
			$data['fldCommunityItem'] = I('param.id');
			$this->assign('id',$data['fldCommunityItem']);
		}
		if(I('param.keyword') && I('param.keyword') != 'null'){
			$data['fldTitle'] =array('like','%' . I('param.keyword') .'%');
			$this->assign('keyword',I('param.keyword'));
		}
		$data['fldClass'] = 2;
		$data['fldIsPush'] = 1;
		$data['fldAuditStatus'] = 1;
		$page = I('param.page',1)-1;
		$homemakingCommunity=M('homemaking_community','',DB_CONFIG3);
		$count=$homemakingCommunity->where($data)->count();
		$result=$homemakingCommunity->field('fldId,fldTitle,fldViewNum')->where($data)->limit($page*10,10)->select();
		if(IS_AJAX){
			if(empty($result)){
				$this->ajaxReturn('');
			}else{
				$this->ajaxReturn($result);
			}
		}else{
			$this->assign('list',$result); //第二次请求
		}
		$this->assign('count',$count);
		$this->assign('timestamp',time());
		$this->display('parentingInformationSearch');
	}
	
	/**
	  * parentingInformationDetail
	  * 母婴资讯详情页
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function parentingInformationDetail(){
		header("content-type:text/html;charset=utf-8");
		$content=M('homemaking_community');
		$data['fldClass'] = 2;
		$data['fldIsPush'] = 1;
		$data['fldAuditStatus'] = 1;
		$data['fldId'] = $_GET['id'];
		$result = $content->where($data)->setInc('fldViewNum');
		$result=$content->field('fldId,fldTitle,fldCreateDate,fldContent,fldIsPush,fldAuditStatus')->where($data)->find();
		$result['fldCreateDate'] = $this->_timeFormat($result['fldCreateDate']);
		if ($result['fldIsPush'] != 1 || $result['fldAuditStatus'] != 1 ) {
			$this->error('非法操作！');
		}else {
			$this->assign('list',$result);
			$this->display('parentingInformationDetail');
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
			if (IS_AJAX) {
				$userinfo['user_account'] = I('post.phone');
				$userinfo['user_password'] =  I('post.password');
				$userinfo['ip'] = get_client_ip();
				if (session('openid')) {
					$userinfo['open_id'] = session('openid');
					$userinfo['type'] = 'weixin';
					$user = userinfo($userinfo['open_id']);
					$username = $user['nickname'];
					$headimgurl = $user['headimgurl'];
					$userinfo['open_name'] = $username;
					//$userinfo['account_from'] = '3001';
				}
				$data = urlencode(json_encode($userinfo));
				$url = APP_DOMIN.'Chitone-Account-login';
				$res = _get($url, $data);
				$uid = $res['result']['account_id'];
				session('uid', $uid);
				$code = $res['result']['code'];
				$redis = new Redis();
				$redis->connect('192.168.8.197', '6379');
				//$redis->connect('192.168.2.183', '6379');
				//$redis->auth('job5156RedisMaster183');
				$redisres = $redis->HGetAll($code);
				$per_user_id = $redisres['id'];
				session('per_user_id', $per_user_id);
				cookie('per', $code, 86400);
				
				if($uid){
					session('userPasswordFlag',true);
					$this->ajaxReturn('true');
				}else {
					$this->ajaxReturn('false');
				}
			}else{
				if(session('userPasswordFlag') && session('uid') && session('per_user_id')){
					$url = APP_DOMIN.'Chitone-AccountUser-modifyUser';
					$phone = I('post.account');
					$password = I('post.newPassword');
					$user['account_id'] = session('uid');
					$user['per_user_id'] = session('per_user_id');
					//$user['password'] = md5($phone.':'.$password);
					$user['password'] = $password;
					$data = urlencode(json_encode($user));
					$res = _get($url,$data);
					$status = $res['status'];
					if(status == 0){
						session('userPasswordFlag',null);
						import('ORG.Util.Jssdk');
						$jssdk = new JSSDK(APPID, APPSECRET);
						$signPackage = $jssdk->GetSignPackage();
						exit("<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
							<script>
								wx.config({
									debug: false,
									appId: '{$signPackage['appId']}',
									timestamp: {$signPackage['timestamp']},
									nonceStr: '{$signPackage['nonceStr']}',
									signature: '{$signPackage['signature']}',
									jsApiList: [
									  // 所有要调用的 API 都要加到这个列表中
									  'getLocation','openLocation','closeWindow'
									  //'getLocation,openLocation'
									  
									]
								  });
								wx.ready(function () {
									wx.closeWindow();
								});
							 </script>");
						//$this->success('更改成功',U('Homemaking-mamabang','',''));
						//redirect(U('Homemaking-motherBabyQA',array('v'=>time()),''));
					}else{
						$this->error('更改失败',U('Homemaking-changePassword',array('v'=>time()),''));
						//die();
					}
				}else{
					$this->error('账号密码错误',U('Homemaking-changePassword',array('v'=>time()),''));
					//die();
				}
			}
		}
		$this->assign('u',I('get.u'));
		$this->assign('p',I('get.p'));
		$this->display();
	}
	
	/**
	  * requirement
	  * 我要预约
	  * @access public
	  * @return void
	  * @date 2015-05-15
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirement(){
		header("content-type:text/html;charset='UTF-8'");
		$page = I('param.page',1);  //获取请求的页数
		//查询等级价格信息
		if (S('gradePriceArray')) {
			$gradePriceArray = S('gradePriceArray');
		}else {
			$gradePrice = D('HomemakingServicePrice');
			$gradePriceResult = $gradePrice->field('fldId,fldName,fldType')->relation(true)->select();
			foreach($gradePriceResult as $k=>$v){
				$temp = null;
				foreach($v['homemaking_service_price_detail'] as $kk=>$vv){
					if (1 == $vv['fldStatus']) {
						$gradePriceArray[$v['fldType']][$vv['fldGrade']] = $vv['fldPrice'];
						$gradePriceArray[$v['fldType']][$vv['fldPrice']] = $vv['fldGrade'];
						$temp .= ','.$vv['fldPrice'].'元';
					}
				}
				$gradePriceArray[$v['fldType']]['string'] =  '不限,'.substr($temp,1);
			}
			S('gradePriceArray',$gradePriceArray,3600);
		}
		
		//获取等级证书映射
		if (S('gradeCertificateMap')) {
			$gradeCertificateMap = S('gradeCertificateMap');
		}else {
			$homemakingServicePriceDetail = D('HomemakingServicePriceDetail');
			//$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->field('fldId,fldParentId,fldGrade,fldPrice')->relation(true)->where(array('fldStatus'=>1))->select();
			$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->alias('spd')->field('spd.fldId,spd.fldParentId,spd.fldGrade,spd.fldPrice,sp.fldType')->join('right join  homemaking_service_price as sp on spd.fldParentId = sp.fldID')->relation(true)->where(array('fldStatus'=>1))->select();
			foreach ($homemakingServicePriceDetailResult as $key1 => $value1) {
				foreach ($value1['homemaking_certificate'] as $key2 => $value2) {
					if ($value2['fldStatus'] == 1) {
						$gradeCertificateMap[$value1['fldType']][$value1['fldGrade']][] = $value2['fldName'];
					}
				}
			}
			S('gradeCertificateMap',$gradeCertificateMap,3600);
		}
		
		//获取工作年限映射数组
		if (S('workyearsMap') && S('wy')) {
			$workyearsMap = S('workyearsMap');
			$wy = S('wy');
		}else {
			$homemakingSetting = M('homemaking_setting');
			$workyears = $homemakingSetting->field('fldValue')->where(array('fldName'=>'workyears'))->find();
			//$workyearsMap = $this->_objectToArray(json_decode($workyears['fldValue']));
			$workyearsMap = json_decode($workyears['fldValue'],true);
			$wy = array_merge(array(0=>-1),array_keys($workyearsMap));
			S('workyearsMap',$workyearsMap,3600);
			S('wy',$wy,3600);
		}
		
		//获取所在地映射数组
		if (S('locationsMap')) {
			$locations = S('locationsMap');
			$this->assign('locations',$locations);
		}else {
			$homemakingSetting = M('homemaking_setting');
			$locations = $homemakingSetting->field('fldValue')->where(array('fldName'=>'location'))->find();
			$locations = implode(',',json_decode($locations['fldValue']));
			S('locationsMap',$locations,3600);
			$this->assign('locations',$locations);
		}
		
		//获取请求的家政员类型
		//dump(I('param.type'));
		//explode(I('param.type'));
		/*if($this->_checkUrlcode(urldecode(I('param.type'))) && $this->_checkUrlcode(urldecode(I('param.type')))!='null'){
			$type = ' HomeInfo.fldPosition like '."'%".$this->_checkUrlcode(urldecode(I('param.type')))."%' ";
			$t = $this->_checkUrlcode(urldecode(I('param.type')));
		}else{
			$type = ' HomeInfo.fldPosition like '."'%月嫂%' ";
			$t = '月嫂';
		}*/
		if($this->_checkUrlcode(urldecode(I('param.type'))) && $this->_checkUrlcode(urldecode(I('param.type'))) =='育婴员'){
			//$type = ' HomeInfo.fldPosition like '."'%".$this->_checkUrlcode(urldecode(I('param.type')))."%' ";
			//$type = " HomeInfo.fldPosition like '%育婴%' ";  
			//$type = " HomeInfo.fldhometype = '04' and HomeInfo.fldSign = 1 and HomeInfo.fldOnWork = 0 and HomeInfo.fldgrade in ('高级','中级','初级') ";
			$type = " HomeInfo.fldhometype = '04' and HomeInfo.fldSign = 1 and HomeInfo.fldOnWork = 0 and HomeInfo.fldgrade in ('一星','二星','三星','四星','五星','金牌') ";
			$signSql = 'select fldhomeid from homeSignBaby where fldIssign <> 2 ';
			$t = '育婴员';
		}else{
			//$type = ' HomeInfo.fldPosition like '."'%月嫂%' and HomeInfo.fldSign = 1 ";
			//$type = " HomeInfo.fldhometype = '03' and HomeInfo.fldSign = 1 and HomeInfo.fldgrade in ('A级','B级','C级','星级','特级','金牌') ";  
			$type = " HomeInfo.fldhometype = '03' and HomeInfo.fldSign = 1 and HomeInfo.fldgrade in ('一星','二星','三星','四星','五星','金牌','钻石') ";
			$signSql = 'select fldhomeID from HomeInfoSign where fldIssign <> 2 ';
			$t = '月嫂';
		}
		$this->assign('type',$t);
		
		//获取请求的心理价位
		$p = $this->_checkUrlcode(urldecode(I('param.pay','')));
		if(false != $p && $p!='null'){
			if($t == '月嫂'){
				$pay = $p=='null' || $p=='不限'?'':$p;
				$grade = $gradePriceArray[1][$pay];
				$grade = " and HomeInfo.fldGrade like "."'%".$grade."%' ";
				if ($p!='null' && $p=='不限') {
					$this->assign('pay','不限');
				}else {
					$this->assign('pay',$pay);
				}
			}else if($t == '育婴员'){
				$pay = $p=='null' || $p=='不限'?'':$p;
				$grade = $gradePriceArray[2][$pay];
				$grade = " and HomeInfo.fldGrade like "."'%".$grade."%' ";
				if ($p!='null' && $p=='不限') {
					$this->assign('pay','不限');
				}else {
					$this->assign('pay',$pay);
				}
			}
		}else{
			$grade = ' ';
		}
		
		//获取请求的籍贯
		if(urldecode(I('param.place')) && urldecode(I('param.place'))!='null' && urldecode(I('param.place'))!='不限'){
			$native = ' and HomeInfo.fldNative like '."'%".urldecode(I('param.place'))."%' "; 
			$this->assign('place',urldecode(I('param.place')));
		}else{
			$native = ' ';
			if(urldecode(I('param.place'))=='不限'){
				$this->assign('place',urldecode(I('param.place')));
			}
		}
		
		//获取请求的所在地
		if(urldecode(I('param.locations')) && urldecode(I('param.locations'))!='null' && urldecode(I('param.locations'))!='不限'){
			$location = ' and HomeInfo.fldLocation like '."'%".urldecode(I('param.locations'))."%' "; 
			$this->assign('location',urldecode(I('param.locations')));
		}else{
			$location = ' ';
			if(urldecode(I('param.locations'))=='不限'){
				$this->assign('location',urldecode(I('param.locations')));
			}
		}
		
		if ($t == '月嫂') {
			//获取请求的日期
			if(I('param.date') && I('param.date')!='null'){
				$childbirth = "'".str_replace(',','-',I('param.date'))."'"; 
				$this->assign('date',str_replace(',','-',I('param.date')));
			}else{
				//$childbirth = "'".date('Y-m-d H:i:s',time())."'";
				$childbirth = "'".date('Y-m-d',time())."'";
			}
			/*
			//老版使用订单表
			$homeMyCustServices = D('HomeMyCustServices');
			$homeMyCustServicesResult = $homeMyCustServices->field('fldID')->where('fldEndDate > '.$childbirth)->select();
			//echo $homeMyCustServices->getLastSql();
			foreach($homeMyCustServicesResult as $key=>$value){
				$workIdArray[$key] = $value['fldID'];
				$workIdString .= ','.$value['fldID'];
			}
			*/
			
			//新版使用排班表
			$homeCustomer = D('HomeCustomer');
			//SQL与PHP混拼
			/*$homeCustomerResult = $homeCustomer->field('fldID,fldreserveHomeID')->where(array("fldchildbirth >= DATEADD(d,-isnull(fldreserveDay,0),$childbirth) and fldchildbirth <= $childbirth"))->select();
			foreach($homeCustomerResult as $key=>$value){
				if (!empty($value['fldreserveHomeID'])) {
					//$workIdArray[$key] = $value['fldreserveHomeID'];
					$workIdString .= ','.$value['fldreserveHomeID'];
				}
			}
			//$workIdString = implode(',',$workIdArray)?:'0';
			if(!empty($workIdString)){
				$workIdString = substr($workIdString,1);
			}else{
				$workIdString = '0';
			}*/
			
			//纯SQL
			$sql = "select isnull(stuff((SELECT ','+ CAST(fldreserveHomeID as VARCHAR(10)) FROM HomeCustomer WHERE ( fldchildbirth >= DATEADD(d,-isnull(fldreserveDay,0),{$childbirth}) and fldchildbirth <= {$childbirth} ) for xml path('')),1,1,''),0) as fldreserveHomeIDs";
			$homeCustomerResult = $homeCustomer->query($sql);
			$workIdString = !empty($homeCustomerResult[0]['fldreserveHomeIDs'])?:'0';
			
			$work = " and HomeInfo.fldID not in (".$workIdString.")";
			$field = ' HomeInfo.fldTakeBabyNum, ';
		}else {
			if(urldecode(I('param.date')) && urldecode(I('param.date'))!='null'){
				switch (urldecode(I('param.date'))) {
					case '不限':
						$work ='';
						break;
					case '30岁以下':
						$work =" and HomeInfo.fldBirthday > '".date('Y-m-d',time()-(30*365.25*24*3600))."'";
						break;
					case '30,35岁':
						$work =" and HomeInfo.fldBirthday > '".date('Y-m-d',time()-(36*365.25*24*3600))."' and fldBirthday <= '".date('Y-m-d',time()-(30*365.25*24*3600))."'";
						break;
					case '36,40岁':
						$work =" and HomeInfo.fldBirthday > '".date('Y-m-d',time()-(41*365.25*24*3600))."' and fldBirthday <= '".date('Y-m-d',time()-(36*365.25*24*3600))."'";
						break;
					case '41,45岁':
						$work =" and HomeInfo.fldBirthday > '".date('Y-m-d',time()-(45*365.25*24*3600))."' and fldBirthday <= '".date('Y-m-d',time()-(41*365.25*24*3600))."'";
						break;
					case '45岁以上':
						$work =" and HomeInfo.fldBirthday <= '".date('Y-m-d',time()-(45*365.25*24*3600))."'";
						break;
					default:
						$work ='';
						break;
				}
				$this->assign('date',str_replace(',','-',urldecode(I('param.date'))));
			}else{
				$work ='';
			}
			$field = ' HomeInfo.fldWorkYears, ';
		}
		
		$homeInfo = D('HomeInfo');
		$homemakingImageUrl = M('homemaking_image_url');
		//$result = $homeInfo->field('HomeInfo.fldID,fldName,fldage,fldNative,fldEducation,fldLanguage,fldType,fldPosition,fldMobile,fldPhoto,fldGuid,fldIDCard,convert(varchar(10),fldBirthday,120) as fldBirthday,fldSign,fldLocation,fldSpecialtyDish,fldhonorGuid, '.$field.' HomeInfoSign.fldGrade as fldGradeYuesao ,HomeInfo.fldGrade as fldGradeYuying')->join('LEFT JOIN HomeInfoSign ON HomeInfo.fldID = HomeInfoSign.fldhomeID')->where($type.$grade.$native.$location.' and HomeInfo.fldCompany=1 and HomeInfo.fldID not in ('.$workIdString.')')->limit(($page-1)*10,10)->select();
		//$result = $homeInfo->field('fldID,fldName,fldage,fldNative,fldEducation,fldLanguage,fldType,fldPosition,fldMobile,fldPhoto,fldGuid,fldIDCard,convert(varchar(10),fldBirthday,120) as fldBirthday,fldSign,fldLocation,fldSpecialtyDish,fldhonorGuid, '.$field.' fldGrade,fldhometype')->where($type.$grade.$native.$location.$work." and fldCompany=1 and fldGrade <> '' ")->limit(($page-1)*10,10)->select();
		//$result = $homeInfo->field('HomeInfo.fldID,HomeInfo.fldName,HomeInfo.fldage,HomeInfo.fldNative,HomeInfo.fldEducation,HomeInfo.fldLanguage,HomeInfo.fldType,HomeInfo.fldPosition,HomeInfo.fldMobile,HomeInfo.fldPhoto,HomeInfo.fldGuid,HomeInfo.fldIDCard,convert(varchar(10),HomeInfo.fldBirthday,120) as fldBirthday,HomeInfo.fldSign,HomeInfo.fldLocation,HomeInfo.fldSpecialtyDish,HomeInfo.fldhonorGuid, '.$field.' HomeInfo.fldGrade,HomeInfo.fldhometype,HomeInfoSign.fldIssign,HomeInfoSign.fldID as hisID')->join('LEFT JOIN HomeInfoSign ON HomeInfo.fldID = HomeInfoSign.fldhomeID')->where($type.$grade.$native.$location.$work." and HomeInfo.fldCompany=1 and HomeInfo.fldGrade <> '' and HomeInfoSign.fldIssign <> 2")->limit(($page-1)*10,10)->select();
		$result = $homeInfo->field('fldID,fldName,fldage,fldNative,fldEducation,fldLanguage,fldType,fldPosition,fldMobile,fldPhoto,fldGuid,fldIDCard,convert(varchar(10),fldBirthday,120) as fldBirthday,fldSign,fldLocation,fldSpecialtyDish,fldhonorGuid, '.$field.' fldGrade,fldhometype')->where($type.$grade.$native.$location.$work." and fldCompany=1 and fldGrade <> ''  and fldID in (".$signSql.")")->limit(($page-1)*10,10)->select();
		//dump($result);
		//echo $homeInfo->getLastSql();
		echo $homeInfo->getDbError();
		foreach($result as $key=>$value){
			//查询证书
			/*$homemaking_certificate = array();
			if($value['fldIDCard'] !=null || $value['fldIDCard'] !=''){
				$homemaking_certificate[0] = "身份证已验证";
			}
			foreach($value['homemaking_certificate'] as $k=>$v){
				$homemaking_certificate[$k+1] = $v['fldName'];
			}
			//$homemaking_certificate[0] = "月嫂证";$homemaking_certificate[1] = "健康证";
			$result[$key]['homemaking_certificate'] = $homemaking_certificate;*/
			$result[$key]['homemaking_certificate'] = $gradeCertificateMap[($t == '月嫂')?1:2][$value['fldGrade']];
			//查询点评数
			$homemakingServiceComment = D('HomemakingServiceComment');
			$result[$key]['homemakingServiceCommentNum'] = $homemakingServiceComment->where(array('fldHomeID'=>$value['fldID']))->count();
			
			//获取图片URL
			$userphoto = '/Uploads/Weixin/userphoto.jpg';
			if(empty($value['fldGuid'])){
				$result[$key]['fldPhotoUrl'] = $userphoto;
			}else{
				$PhotoUrl = $homemakingImageUrl->field('fldUrl')->where(array('fldKey'=>$value['fldGuid']))->find();
				if (empty($PhotoUrl)) {
					$result[$key]['fldPhotoUrl'] = $userphoto;
				}else {
					$result[$key]['fldPhotoUrl'] = $PhotoUrl['fldUrl'];
				}
			}
			
			//处理等级价格
			/*if( (strpos($value['fldPosition'],'月嫂') !== false)  && !empty($value['fldGrade'])){
				$result[$key]['homeInfoSignGrade'] = $value['fldGrade'];
				$result[$key]['homeInfoSignGradePrice'] = $gradePriceArray[1][$value['fldGrade']];
			}
			if((strpos($value['fldPosition'],'育婴') !== false) && !empty($value['fldGrade'])){
				$result[$key]['homeInfoSignGrade'] = $value['fldGrade'];
				$result[$key]['homeInfoSignGradePrice'] = $gradePriceArray[2][$value['fldGrade']];
			}*/
			if( $value['fldhometype'] == '03'  && !empty($value['fldGrade'])){
				$result[$key]['fldPrice'] = $gradePriceArray[1][$value['fldGrade']];
			}else if($value['fldhometype'] == '04' && !empty($value['fldGrade'])){
				$result[$key]['fldPrice'] = $gradePriceArray[2][$value['fldGrade']];
			}
			
			//年龄,属相,星座
			if ($value['fldIDCard']) {
				$year =substr($value['fldIDCard'],6,4);
				$month =substr($value['fldIDCard'],10,2);
				$day =substr($value['fldIDCard'],12,2);
				//$result[$key]['fldage'] = round($value['fldage']);
				$result[$key]['fldage'] = floor((time()-strtotime($year.'-'.$month.'-'.$day))/(365.25*24*3600));
				$result[$key]['chineseZodiac'] = get_chinese_zodiac($year);
				$result[$key]['constellation'] = get_constellation($month, $day);
			}else if ($value['fldBirthday']) {
				//$result[$key]['fldage'] = round($value['fldage']);
				$result[$key]['fldage'] = floor((time()-strtotime($value['fldBirthday']))/(365.25*24*3600));
				$result[$key]['chineseZodiac'] = get_chinese_zodiac(date('Y',strtotime($value['fldBirthday'])));
				$result[$key]['constellation'] = get_constellation(date('m',strtotime($value['fldBirthday'])), date('d',strtotime($value['fldBirthday'])));
			}else {
				//处理年龄小数点问题
				$result[$key]['fldage'] = round($value['fldage']);
			}
			
			//籍贯添加"人"字
			$result[$key]['fldNative'] = $value['fldNative']?$value['fldNative'].'人':$value['fldNative'];
			
			//工作年限
			if ($t == '育婴员') {
				$tt = null;
				foreach ($wy as $k => $v) {
					if (($result[$key]['fldWorkYears']+($wy[$k]-$wy[$k-1]))>$v) {
						$tt = $v;
					}
				}
				$result[$key]['fldWorkYears'] = $workyearsMap[$tt];
			}
			
			//处理语言问题，国语转普通话
			$result[$key]['fldLanguage'] = $value['fldLanguage'] === null?'未设置':str_replace('*',' ',str_replace('国语','普通话',$value['fldLanguage']));
		}
		
		//返回等级价格信息
		if($t == '月嫂'){
			$this->assign('gradePriceArray',$gradePriceArray[1]);
		}else if($t == '育婴员'){
			$this->assign('gradePriceArray',$gradePriceArray[2]);
		}
		if(empty($result)){
			$result = '';
		}
		if(IS_AJAX){
			$this->ajaxReturn($result,'JSON');
		}else{
			$this->assign('timestamp',time());
			$this->assign('list',$result);
		}
		$this->display('requirement');
	}
	
	/**
	  * requirementDetail
	  * 我要预约详情页
	  * @access public
	  * @return void
	  * @date 2015-05-19
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirementDetail(){
		header("content-type:text/html;charset='UTF-8'");
		//dump(session('openid'));
		//查询等级价格信息
		if (S('gradePriceArray')) {
			$gradePriceArray = S('gradePriceArray');
		}else {
			$gradePrice = D('HomemakingServicePrice');
			$gradePriceResult = $gradePrice->field('fldId,fldName,fldType')->relation(true)->select();
			foreach($gradePriceResult as $k=>$v){
				$temp = null;
				foreach($v['homemaking_service_price_detail'] as $kk=>$vv){
					if (1 == $vv['fldStatus']) {
						$gradePriceArray[$v['fldType']][$vv['fldGrade']] = $vv['fldPrice'];
						$gradePriceArray[$v['fldType']][$vv['fldPrice']] = $vv['fldGrade'];
						$temp .= ','.$vv['fldPrice'].'元';
					}
				}
				$gradePriceArray[$v['fldType']]['string'] = substr($temp,1);
			}
			S('gradePriceArray',$gradePriceArray,3600);
		}
		
		//获取等级证书映射
		if (S('gradeCertificateMap')) {
			$gradeCertificateMap = S('gradeCertificateMap');
		}else {
			$homemakingServicePriceDetail = D('HomemakingServicePriceDetail');
			//$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->field('fldId,fldParentId,fldGrade,fldPrice')->relation(true)->where(array('fldStatus'=>1))->select();
			$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->alias('spd')->field('spd.fldId,spd.fldParentId,spd.fldGrade,spd.fldPrice,sp.fldType')->join('right join  homemaking_service_price as sp on spd.fldParentId = sp.fldID')->relation(true)->where(array('fldStatus'=>1))->select();
			foreach ($homemakingServicePriceDetailResult as $key1 => $value1) {
				foreach ($value1['homemaking_certificate'] as $key2 => $value2) {
					if ($value2['fldStatus'] == 1) {
						$gradeCertificateMap[$value1['fldType']][$value1['fldGrade']][] = $value2['fldName'];
					}
				}
			}
			S('gradeCertificateMap',$gradeCertificateMap,3600);
		}
		
		//构造婚育状况映射
		if (S('marryMap')) {
			$marryMap = S('marryMap');
		}else {
			$homemakingSetting = M('homemaking_setting');
			$marry = $homemakingSetting->field('fldValue')->where(array('fldName'=>'marry'))->find();
			$marryMap = json_decode($marry['fldValue']);
			S('marryMap',$marryMap,3600);
		}
		
		//获取工作年限映射数组
		if (S('workyearsMap') && S('wy')) {
			$workyearsMap = S('workyearsMap');
			$wy = S('wy');
		}else {
			$homemakingSetting = M('homemaking_setting');
			$workyears = $homemakingSetting->field('fldValue')->where(array('fldName'=>'workyears'))->find();
			$workyearsMap = $this->_objectToArray(json_decode($workyears['fldValue']));
			$wy = array_merge(array(0=>-1),array_keys($workyearsMap));
			S('workyearsMap',$workyearsMap,3600);
			S('wy',$wy,3600);
		}
		
		if (!(I('param.id')) || !I('param.type')) {
			$this->error('非法操作！');
		}else{
			$id = I('param.id');
			$type = I('param.type');
			$page = I('param.page',1)-1;
			
			if (!IS_AJAX) {
				//查询个人信息
				if ($type == 1){
					$field = ',fldTakeBabyNum,fldWorkYears';
				}else{
					$field = ',fldWorkYears';
				}
				$homeInfo = D('HomeInfo');
				$result = $homeInfo->field('fldID,fldName,fldSex,fldMarryStatus,fldNative,fldPosition,fldType,fldhometype,fldLanguage,fldage,fldIDCard,convert(varchar(10),fldBirthday,120) as fldBirthday,fldPhoto,fldSpecialty,fldSelfAppraise,fldSpecialtyDish,fldLocation,fldGrade,fldGuid,fldhonorGuid'.$field)->where(array('fldID'=>$id))->find();
				//$result = $homeInfo->field('HomeInfo.fldID,fldName,fldage,fldNative,fldEducation,fldLanguage,fldType,fldhometype,fldPosition,fldMobile,fldPhoto,fldIDCard,convert(varchar(10),fldBirthday,120) as fldBirthday,fldSign,fldLocation,fldSpecialtyDish,fldhonorGuid, '.$condition.' HomeInfoSign.fldGrade as fldGradeYuesao ,HomeInfo.fldGrade as fldGradeYuying')->join('LEFT JOIN HomeInfoSign ON HomeInfo.fldID = HomeInfoSign.fldhomeID')->where(array('HomeInfo.fldID'=>$id))->find();
				//echo $homeInfo->getDbError();
				if (!empty($result['fldGrade'])) {
					if ($type == 1) {
						$result['Price'] = $gradePriceArray[1][$result['fldGrade']];
					}else{
						$result['Price'] = $gradePriceArray[2][$result['fldGrade']];
					}
				}
				if (!($result['fldWorkYears'] === null)) {
					foreach ($wy as $k => $v) {
						if (($result['fldWorkYears']+($wy[$k]-$wy[$k-1]))>$v) {
							$fldWorkYears = $workyearsMap[$v];
						}
					}
					$result['fldWorkYears'] = $fldWorkYears;
				}
				if (!empty($result['fldGrade'])) {
					if ($type == 1) {
						$result['Price'] = $gradePriceArray[1][$result['fldGrade']];
					}else{
						$result['Price'] = $gradePriceArray[2][$result['fldGrade']];
					}
				}
				//获取证书信息
				/*if (!empty($result['homemaking_certificate'])) {
					$certificate = array();
					foreach ($result['homemaking_certificate'] as $key => $value) {
						if ($value['fldStatus'] == 1) {
							$certificate[] = $value['fldName'];
						}
					}
					$result['homemaking_certificate'] = $certificate;
				}*/
				$result['fldhometype'] = ('03' == $result['fldhometype'])?1:(('04' == $result['fldhometype'])?2:0);
				$result['homemaking_certificate'] = $gradeCertificateMap[$result['fldhometype']][$result['fldGrade']];
				
				//荣誉&证书
				$HomemakingImageUrl = D('HomemakingImageUrl');
				$result['honorPhoto'] = $HomemakingImageUrl->field('fldUrl')->where(array('fldKey'=>$result['fldhonorGuid']))->select();
				
				//年龄,属相,星座
				if ($result['fldIDCard']) {
					$year =substr($result['fldIDCard'],6,4);
					$month =substr($result['fldIDCard'],10,2);
					$day =substr($result['fldIDCard'],12,2);
					$result['fldage'] = floor((time()-strtotime($year.'-'.$month.'-'.$day))/(365.25*24*3600));
					$result['chineseZodiac'] = get_chinese_zodiac($year);
					$result['constellation'] = get_constellation($month, $day);
				}else if ($result['fldBirthday']) {
					$result['fldage'] = floor((time()-strtotime($result['fldBirthday']))/(365.25*24*3600));
					$result['chineseZodiac'] = get_chinese_zodiac(date('Y',strtotime($result['fldBirthday'])));
					$result['constellation'] = get_constellation(date('m',strtotime($result['fldBirthday'])), date('d',strtotime($result['fldBirthday'])));
				}else {
					//处理年龄小数点问题
					$result['fldage'] = round($result['fldage']);
				}
				
				//籍贯添加"人"字
				$result['fldNative'] = $result['fldNative']?$result['fldNative'].'人':$result['fldNative'];
				
				//处理语言问题，国语转普通话
				$result['fldLanguage'] = $result['fldLanguage'] === null?'未设置':str_replace('*',' ',str_replace('国语','普通话',$result['fldLanguage']));
				$result['fldMarryStatus'] = $result['fldMarryStatus'] == 0 ?'未设置':$marryMap[$result['fldMarryStatus']];
				
				//获取图片URL
				//$result['PhotoUrl'] = empty($result['fldGuid'])?'':$result['fldPhoto'];
				$userphoto = '/Uploads/Weixin/userphoto.jpg';
				if (empty($result['fldGuid'])) {
					$result['PhotoUrl'] = $userphoto;
				}else {
					$homemakingImageUrl = M('homemaking_image_url');
					$PhotoUrl = $homemakingImageUrl->field('fldUrl')->where(array('fldKey'=>$result['fldGuid']))->find();
					if (empty($PhotoUrl)) {
						$result['PhotoUrl'] = $userphoto;
					}else {
						$result['PhotoUrl'] = $PhotoUrl['fldUrl'];
					}
				}
				
				//查询雇主点评
				$homemakingServiceComment = M('homemaking_service_comment','',DB_CONFIG3);
				$homemakingServiceCommentcount = $homemakingServiceComment->where(array('fldHomeID'=>$id))->count();
				if ($type == 1) {
					$homemakingServiceCommentResult = $homemakingServiceComment->field('fldParentId,fldBabyWater,fldDealDoody,fldTouchBaby,fldWaterCloth,fldDevelopment,fldWatchBaby,fldHelpClean,fldHelpSuck,fldRoomHealth,fldMadeRice,fldHelpWay,fldHelpRecover,fldLoving,fldPositive,fldCommunication,fldCharacter,fldHealth,fldFormality,fldBabySitterServiceQuality')->where(array('fldHomeID'=>$id))->select();
					//计算点评分数
					foreach ($homemakingServiceCommentResult as $key => $value) {
						if (empty($value['fldBabySitterServiceQuality'])) {
							$baby += ($value['fldBabyWater'] + $value['fldDealDoody']  + $value['fldTouchBaby'] + $value['fldWaterCloth'] + $value['fldDevelopment'] + $value['fldWatchBaby'] );
							$mother += ($value['fldHelpClean'] + $value['fldHelpSuck']  + $value['fldRoomHealth'] + $value['fldMadeRice'] + $value['fldHelpWay'] + $value['fldHelpRecover'] );
							$sitter += ($value['fldLoving'] + $value['fldPositive']  + $value['fldCommunication'] + $value['fldCharacter'] + $value['fldHealth'] + $value['fldFormality'] );
							$num ++;
						}
					}
					$all = round((($baby+$mother+$sitter)*100/$num/90));
					if ($all == 0) {
						$baby = mt_rand(27,30);
						$mother = mt_rand(27,30);
						$sitter = mt_rand(27,30);
						$all = round((($baby+$mother+$sitter)*100/$num/90));
					}else {
						$baby = round($baby*100/$num/30);
						$mother = round($mother*100/$num/30);
						$sitter = round($sitter*100/$num/30);
					}
					$this->assign('all',$all);
					$this->assign('baby',$baby);
					$this->assign('mother',$mother);
					$this->assign('sitter',$sitter);
				}else{
					$homemakingServiceCommentResult = $homemakingServiceComment->field('fldParentId,fldBabySitterServiceQuality')->where(array('fldHomeID'=>$id))->select();
					foreach ($homemakingServiceCommentResult as $key => $value) {
						if (!empty($value['fldBabySitterServiceQuality'])) {
								$babySitterServiceQuality += $value['fldBabySitterServiceQuality'];
								$num ++;
						}
					}
					$all = round($babySitterServiceQuality*100/$num/5);
					if ($all == 0) {
						$all = mt_rand(100,100);
					}
					$this->assign('all',$all);
				}
				$this->assign('homemakingServiceCommentcount',$homemakingServiceCommentcount);
				$this->assign('type',$type);
				$this->assign('result',$result);
			}
			
			$homemakingServiceComment = M('homemaking_service_comment','',DB_CONFIG3);
			$homemakingServiceCommentResult = $homemakingServiceComment->field('fldParentId,fldAssessment,cast(fldUploadFile as varchar(40)) as fldUploadFile,fldDate')->where(array('fldHomeID'=>$id))->limit($page*10,10)->order('fldDate desc')->select();
			//echo $homemakingServiceComment->getLastSql();
			//echo $homemakingServiceComment->getDbError();
			if ($homemakingServiceCommentResult) {
				//$sysAttachFile = D('sysAttachFile');
				$homemakingImageUrl = D('homemaking_image_url');
				$homeMycustomer = D('HomeMycustomer');
				$homeCustomer = D('HomeCustomer');
				foreach ($homemakingServiceCommentResult as $key => $value) {
					//获取图片链接
					if(!empty($value['fldUploadFile'])){
						$homemakingImageUrlResult = $homemakingImageUrl->field('fldFileName,fldUrl')->where(array('fldKey'=>$value['fldUploadFile']))->select();
						//dump($homemakingImageUrlResult);
						if(!empty($homemakingImageUrlResult)){
							$homemakingImageUrlResultA = array();
							$prefix = 't_';
							for ($i=0; $i < count($homemakingImageUrlResult); $i++) { 
								$s = strstr($homemakingImageUrlResult[$i]['fldFileName'],'.',TRUE);
								if(strlen($s) == 36){
									$homemakingImageUrlResultT = array();
									$homemakingImageUrlResultT[] = $homemakingImageUrlResult[$i]['fldUrl'];
									for ($j=0; $j < count($homemakingImageUrlResult); $j++) { 
										//if (strpos($homemakingImageUrlResult[$j]['fldFileName'],$s) !== false) {
										if (strstr($homemakingImageUrlResult[$j]['fldFileName'],$prefix.$s)) {
											$homemakingImageUrlResultT[] = $homemakingImageUrlResult[$j]['fldUrl'];
										}
									}
									$homemakingImageUrlResultA[] = $homemakingImageUrlResultT;
								}
							}
							$homemakingServiceCommentResult[$key]['UploadFile'] = $homemakingImageUrlResultA;
						}
					}
					
					//获取交易单信息
					if (!empty($value['fldParentId'])) {
						$homeMycustomerResult = $homeMycustomer->field('fldID,fldCustomerID,fldBeginDate,fldEndDate')->where(array('fldID'=>$value['fldParentId']))->find();
						if (!empty($homeMycustomerResult)) {
							$homemakingServiceCommentResult[$key]['fldBeginDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldBeginDate']));
							$homemakingServiceCommentResult[$key]['fldEndDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldEndDate']));
							$homeCustomerResult = $homeCustomer->field('fldName')->where(array('fldID'=>$homeMycustomerResult['fldCustomerID']))->find();
							if ($homeCustomerResult) {
								$homemakingServiceCommentResult[$key]['fldName'] = $this->_substrCut($homeCustomerResult['fldName']);
							}
						}
					}
				}
			}
			if (IS_AJAX) {
				if (empty($homemakingServiceCommentResult)) {
					$this->ajaxReturn('');
				}else{
					$this->ajaxReturn($homemakingServiceCommentResult,'JSON');
				}
			}else{
				$this->assign('homemakingServiceCommentResult',$homemakingServiceCommentResult);
			}
			$this->display();
		}
	}
	
	/**
	  * requirementSubmit
	  * 提交我要预约
	  * @access public
	  * @return void
	  * @date 2015-05-21
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirementSubmit(){
		header("content-type:text/html;charset='utf-8'");
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		
		if(IS_POST){
			//获取籍贯编号映射
			/*$palNative = D('palNative');
			$result = $palNative->select();
			$palNativeMap = array();
			$palNativeMap['不限'] = '0';
			foreach($result as $value){
				$palNativeMap[$value['fldName']] = $value['fldNo'];
			}*/
			//是否在微信上进入
			if(!session('openid')){
				$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
			}
			//是否已注册
			if(!$uid){//没有注册，则自动注册
				session('requirement',I('post.'));
				$user_name = I('post.user');
				$user_location = I('post.place-code');
				$user_address = I('post.place').I('post.placeDetail');
				$user_phone = I('post.phone');
				$openid = session('openid');
				$userinfo = userinfo($openid);
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$phone = I('post.phone');
				//$password = I('post.passwd');
				//$password = $this->_randString(16,'ZT');
				$password = 'zt1234';
				if($openid){
					$operateType = I('post.operateType' , 'reg');
					if ('login' == $operateType) {
						redirect('Account-login?redirect=Homemaking-requirementSubmit-v'.time().'-type-'.I('param.type'));
					}else {
						$user['open_id'] = $openid;
						//$user['user_account'] = $phone;
						//$user['user_password'] = md5($phone.':'.$password);
						if (!empty($phone)){
							$user['user_account'] = $phone;
						}
						if (!empty($password)){
							$user['user_password'] = $password;
						}
						$user['ip'] = get_client_ip();
						$user['open_name'] = $username;
						$user['type'] ='weixin';
						$user['role_type'] = '0';
						$user['account_from'] = '3001';
						$user['appid'] = APPID;
						if(!empty($user_name)){
							$user['user_name'] = $user_name;
						}
						if(!empty($user_location)){
							$attdate['attach']['location'] = $user_location;
						}
						if(!empty($user_address)){
							$attdate['attach']['address'] = $user_address;
						}
						if(!empty($user_phone)){
							$attdate['attach']['mobile'] = $user_phone;
						}
						$data = urlencode(json_encode($user));
						$url = 'reReg' != $operateType?APP_DOMIN.'Chitone-Account-reg' : APP_DOMIN.'Chitone-Account-reReg';
						$res = _get($url,$data);
						if (empty($res) || $res['status'] != 0) {
							if ($res['status'] == 200000) {
								redirect('Account-login?redirect=Homemaking-requirementSubmit-v'.time().'-type-'.I('param.type'));
							}
							
						}
						$code = $res['result']['code'];
						cookie('per',$code,86400);
						$uid = $res['result']['account_id'];
						session('uid',$uid);
						//获取微信头像
						//$this->getphoto($uid,$headimgurl);
						$redis = new Redis();
						$redis->connect('192.168.8.197', '6379');
						//$redis->connect('192.168.2.183', '6379');
						//$redis->auth('job5156RedisMaster183');
						$redisresult = $redis->HGetAll($code);
						$per_user_id = $redisresult['id'];
						session('per_user_id',$per_user_id);
						$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
						$attdate['per_user_id'] = $per_user_id;
						//$userlocation = S($openid);
						//$userlocation = json_decode($userlocation, true);
						//$place = $this->_planeCrood($userlocation);
						//session('place', $place);
						//$attdate['attach']['user_x'] = $place['x'];
						//$attdate['attach']['user_y'] = $place['y'];
						$attdate['attach']['user_x'] = 0;
						$attdate['attach']['user_y'] = 0;
						$attdate['attach']['head_img_url'] = $headimgurl;
						$attres = urlencode(json_encode($attdate));
						$attresult = _get($atturl,$attres);
						
						//创建默认简历
						$resume['per_user_id'] = $per_user_id;
						$resume['resume_type'] = 4;
						$resume['resume_name'] = "我的微名片";
						$resumedata = urlencode(json_encode($resume));
						$resumeurl = APP_DOMIN.'Chitone-AccountUser-modifyResume';
						$resumeresult = _get($resumeurl,$resumedata);
						
						//注册成功推送绑定成功信息
						if($uid){
							$result = $this->sendWeixinTemplateMessage('changePassword','您已成功注册'.WECHAT_NAME.'账户',$phone,$password,'','温馨提醒：请妥善保管您的账号信息，为防止密码泄露，建议您立即修改密码。点此修改用户名密码',$uid);
						}

					}
				}else{
					$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
				}
			}else{
				$userinfo = userinfo(session('openid'));
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$code = cookie('per');
				$redis = new Redis();
				$redis->connect('192.168.8.197', '6379');
				//$redis->connect('192.168.2.183', '6379');
				//$redis->auth('job5156RedisMaster183');
				$redisresult = $redis->HGetAll($code);
				$per_user_id = $redisresult['id'];
				session('per_user_id',$per_user_id);
				$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
				$attdate['per_user_id'] = $per_user_id;
				$attdate['attach']['head_img_url'] = $headimgurl;
				$attdate['attach']['user_x'] = 0;
				$attdate['attach']['user_y'] = 0;
				if((I('post.place-code')&&I('post.place-code')!='1401040')||(I('post.place')=='广东东莞万江区')&&I('post.place-code')=='14010400' ){
					$attdate['attach']['location'] = I('post.place-code');
				}
				if(I('post.place').I('post.placeDetail')){
					$attdate['attach']['address'] = I('post.place').I('post.placeDetail');
				}
				$attres = urlencode(json_encode($attdate));
				$attresult = _get($atturl,$attres);
				//die();
			}
			if($uid){
				$type = I('post.type');
				$homeCustomer = D('HomeCustomer');
				//$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
				$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldPerUserId'=>$uid))->find();
				$homeCustomerId = $homeCustomerId['fldId'];
				if(!$homeCustomerId){
					$homeCustomerData = array();
					$homeCustomerData['fldName'] = I('post.user');
					$homeCustomerData['fldMobile'] = I('post.phone');
					$homeCustomerData['fldAddress'] = I('post.place').I('post.placeDetail');
					if($type==1){
						$homeCustomerData['fldchildbirth'] = I('post.dueDate');
					}elseif($type==2){
						$homeCustomerData['fldBadyBrithday'] = I('post.dueDate');
					}
					$homeCustomerData['fldPerUserId'] = $uid;
					$homeCustomerData['fldWeixinCode'] = session('openid');
					$homeCustomerData['fldInfoFrom'] = '微信：'.WECHAT_NAME;
					$homeCustomerData['lasteditby'] = 'admin';
					$homeCustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
					$homeCustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$homeCustomerId = $homeCustomer->add($homeCustomerData);
					//$homeCustomerId = $homeCustomer->data($homeCustomerData)->add();
					//echo $homeCustomer->getDbError();
				}
				if($homeCustomerId){
					if ($homeId = I('post.homeId',0)) {
						$homemakingRequirementData['fldHomeID'] = $homeId;
					}
					$homemakingRequirementData['fldPersonId'] = $homeCustomerId;
					$homemakingRequirementData['fldType'] = $type;
					$fldPriceId = M('homemaking_service_price_detail')->field('fldId')->where(array('fldPrice'=>substr(I('post.price'),0,-3)))->find();
					$homemakingRequirementData['fldPriceId'] = $fldPriceId['fldId'];
					//$homemakingRequirementData['fldNative'] = $palNativeMap[I('post.native')];
					$homemakingRequirementData['fldNative'] = I('post.native','不限')==''?'不限':I('post.native','不限');
					$homemakingRequirementData['fldAge'] = I('post.age',0)==''?0:I('post.age',0);
					//$homemakingRequirementData['fldDegree'] = I('post.education');
					$homemakingRequirementData['fldDegree'] = 0;
					//$homemakingRequirementData['fldMarryStatus'] = I('post.marry',0)==''?0:I('post.marry',0);
					//$homemakingRequirementData['fldWorkYears'] = I('post.workYear',0)==''?0:I('post.workYear',0);
					$homemakingRequirementData['fldMarryStatus'] = I('post.workYear',0)==''?0:I('post.workYear',0);
					$homemakingRequirementData['fldLanguage'] = I('post.language',0)==''?0:I('post.language',0);
					//$homemakingRequirementData['fldRemark'] = I('post.special');
					//$homemakingRequirementData['fldReserve'] = I('post.reserve',3)==''?3:I('post.reserve',3);
					$homemakingRequirementData['fldRemark'] = I('post.reserve','暂不上门')==''?'暂不上门':I('post.reserve','暂不上门');
					$homemakingRequirementData['fldStatus'] = 0;
					$homemakingRequirementData['fldOwner'] = 'admin';
					$homemakingRequirementData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$homemakingRequirementData['lastEditby'] = 'admin';
					$homemakingRequirementData['lastEditdt'] = date('Y-m-d H:i:s',time());
					$homemakingRequirement = M('homemaking_requirement');
					$homemakingRequirementId = $homemakingRequirement->add($homemakingRequirementData);
					//echo $homemakingRequirement->getDbError();
					if($homemakingRequirementId){
						$homeMycustomer = D('HomeMycustomer');
						$homeMycustomerData = array();
						$homeMycustomerData['fldCustomerID'] = $homeCustomerId;
						$homeMycustomerData['fldRequireId'] = $homemakingRequirementId;
						$homeMycustomerData['fldWorkAddress'] = I('post.place').I('post.placeDetail');
						$homeMycustomerData['fldWorkProject'] = $type == 1?'月嫂':'育婴嫂';
						$homeMycustomerData['fldAmount'] = substr(I('post.price'),0,-3);
						$homeMycustomerData['fldserviceFee'] = $type == 1?1700:1500;
						$homeMycustomerData['fldcommision'] = $type == 1?1700:1500;
						$homeMycustomerData['fldInsurance'] = 1;
						$homeMycustomerData['fldInsuranceFee'] = $type == 1?50:150;
						$homeMycustomerData['fldCustomerName'] = I('post.user');
						//$homeMycustomerData['fldMobile'] = I('post.phone');
						$homeMycustomerData['fldserverState'] = 0;
						$homeMycustomerData['fldreserveTime'] = I('post.dueDate');
						$homeMycustomerData['fldOrderFrom'] = '微信';
						$homeMycustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
						$homeMycustomerData['lasteditby'] = 'admin';
						$homeMycustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
						$addResult = $homeMycustomer->add($homeMycustomerData);
						//echo $homeMycustomer->getDbError();
						if($type==1){
							$updateResult = $homeCustomer->where(array('fldID'=>$homeCustomerId))->save(array('fldchildbirth'=>I('post.dueDate'),'fldBadyBrithday'=>null,'lasteditdt'=>date('Y-m-d H:i:s',time()),'lasteditby'=>'admin'));
						}elseif($type==2){
							$updateResult = $homeCustomer->where(array('fldID'=>$homeCustomerId))->save(array('fldchildbirth'=>null,'fldBadyBrithday'=>I('post.dueDate'),'lasteditdt'=>date('Y-m-d H:i:s',time()),'lasteditby'=>'admin'));
						}
					}
				}
				if($addResult){
					$result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请保持电话畅通，您的专属顾问会尽快为您提供服务。','0769-87073668',session('openname'),'',0);
					//$result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请等待工作人员为您安排时间','0769-87073668',session('openname'),'请保持电话畅通，网站工作人员会尽快为您提供服务。',0);
					//$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccess');
					if ($type == 1) {
						$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuesao');
					}else {
						$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuyingsao');
					}
					//redirect(U('Homemaking-requirementSubmit',array('type'=>'1')));
					//redirect(U('Homemaking-requirementSubmitSuccess'));
					//$this->display('requirementSubmitSuccess');
				 }else{
					//header("content-type:text/html;charset='utf-8'");
					//redirect(U('Homemaking-requirementSubmit'),'','','提交失败');
					//redirect(U('Homemaking-requirementSubmit','',''),1,'提交失败');
					$this->error('新增失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
				 }
			}else {
				$this->error('非法操作');
			}
		}else{
			if (session('time')){
				$time = session('time');
				$newtime = time();
				$losetime = $newtime - $time;
				$losetime = 120 - $losetime;
				if ($losetime > 1 && $losetime < 120) {
					$this->assign('time', $losetime);
				}
			}
			
			$homemakingServicePrice = D('HomemakingServicePrice');
			$parentIds = $homemakingServicePrice->field('fldId,fldType,fldName')->select();
			$homemakingServicePriceDetail = D('HomemakingServicePriceDetail');
			foreach($parentIds as $key=>$value){
				$result[$value['fldType']] = $homemakingServicePriceDetail->field('fldId,fldParentId,fldGrade,fldPrice')->relation(true)->where(array('fldParentId'=>$value['fldId'],'fldStatus'=>1))->select();
			}
			
			$resultJson = array();
			$price = array();
			foreach($result as $k=>$v){
				foreach($v as $kk=>$vv){
					$price[$k] .= ','.$vv['fldPrice'].'元';
					foreach($vv as $kkk=>$vvv){
						foreach($vvv as $kkkk=>$vvvv){
							switch($vvvv['fldName']){
								case '身份证':$style = 1;break;
								case '健康证':$style = 2;break;
								case '月嫂证':$style = 3;break;
								case '月子餐':$style = 4;break;
								case '育婴师证':$style = 5;break;
								case '催乳师证':$style = 6;break;
							}
							$resultJson[$k][$vv['fldPrice']][$vvvv['fldName']] = $style;
						}
					}
				}
				$price[$k] = substr($price[$k],1);
			}
			//$uid = 115;
			if($uid || session('openid')){
				$homeCustomer = D('HomeCustomer');
				//$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
				$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
				//echo $homeCustomer->getLastSql();
				if($homeCustomerResult){
					$this->assign('info',$homeCustomerResult);
					$this->assign('uid',$uid);
				}else{
					$homeInfo = D('HomeInfo');
					$homeInfoResult = $homeInfo->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
					if($homeInfoResult){
						$this->assign('info',$homeInfoResult);
						$this->assign('uid',$uid);
					}else{
						$homeTrainOrder = D('HomeTrainOrder');
						$homeTrainOrderResult = $homeTrainOrder->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
						if($homeInfoResult){
							$this->assign('info',$homeTrainOrderResult);
							$this->assign('uid',$uid);
						}
					}
				}
			}
			import('ORG.Util.Jssdk');
			$jssdk = new JSSDK(APPID, APPSECRET);
			$signPackage = $jssdk->GetSignPackage();
			$this->assign('signPackage',$signPackage);
			$this->assign('type',I('get.type'));
			$this->assign('price',$price);
			$this->assign('priceCertificate',json_encode($resultJson));
			$this->display();
		}
	}
	
	/**
	  * requirementSubmit4Login
	  * 登录绑定后提交我要预约
	  * @access public
	  * @return void
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirementSubmit4Login(){
		header("content-type:text/html;charset='utf-8'");
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		//echo '<Meta http-equiv="Content-Type" Content="text/html; Charset=utf-8">';
		if($uid && session('openid') && session('requirement')){
			$post = session('requirement');
			$userinfo = userinfo(session('openid'));
			$username = $userinfo['nickname'];
			$headimgurl = $userinfo['headimgurl'];
			$code = cookie('per');
			$redis = new Redis();
			$redis->connect('192.168.2.183', '6379');
			$redis->auth('job5156RedisMaster183');
			$redisresult = $redis->HGetAll($code);
			$per_user_id = $redisresult['id'];
			session('per_user_id',$per_user_id);
			$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
			$attdate['per_user_id'] = $per_user_id;
			$attdate['attach']['head_img_url'] = $headimgurl;
			$attdate['attach']['user_x'] = 0;
			$attdate['attach']['user_y'] = 0;
			if(($post['place-code']&&$post['place-code']!='1401040')||($post['place']=='广东东莞万江区')&&$post['place-code']=='14010400' ){
				$attdate['attach']['location'] = $post['place-code'];
			}
			if($post['place'].$post['placeDetail']){
				$attdate['attach']['address'] = $post['place'].$post['placeDetail'];
			}
			$attres = urlencode(json_encode($attdate));
			$attresult = _get($atturl,$attres);
			//die();
			
			$type = $post['type'];
			$homeCustomer = D('HomeCustomer');
			//$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
			$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldPerUserId'=>$uid))->find();
			$homeCustomerId = $homeCustomerId['fldId'];
			if(!$homeCustomerId){
				$homeCustomerData = array();
				$homeCustomerData['fldName'] = $post['user'];
				$homeCustomerData['fldMobile'] = $post['phone'];
				$homeCustomerData['fldAddress'] = $post['place'].$post['placeDetail'];
				if($type==1){
					$homeCustomerData['fldchildbirth'] = $post['dueDate'];
				}elseif($type==2){
					$homeCustomerData['fldBadyBrithday'] = $post['dueDate'];
				}
				$homeCustomerData['fldPerUserId'] = $uid;
				$homeCustomerData['fldWeixinCode'] = session('openid');
				$homeCustomerData['fldInfoFrom'] = '微信：'.WECHAT_NAME;
				$homeCustomerData['lasteditby'] = 'admin';
				$homeCustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
				$homeCustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homeCustomerId = $homeCustomer->add($homeCustomerData);
				//$homeCustomerId = $homeCustomer->data($homeCustomerData)->add();
				//echo $homeCustomer->getDbError();
			}
			if($homeCustomerId){
				if ($homeId = $post['homeId']) {
					$homemakingRequirementData['fldHomeID'] = $homeId;
				}
				$homemakingRequirementData['fldPersonId'] = $homeCustomerId;
				$homemakingRequirementData['fldType'] = $type;
				//$fldPriceId = M('homemaking_service_price_detail')->field('fldId')->where(array('fldPrice'=>substr($post['price'],0,-3)))->find();
				$fldPriceId = M('homemaking_service_price_detail')->alias('spd')->field('spd.fldId')->join('right join homemaking_service_price as sp on spd.fldParentId = sp.fldId')->where(array('spd.fldPrice'=>substr(I('post.price'),0,-3),'sp.fldType'=>$type))->find();
				$homemakingRequirementData['fldPriceId'] = $fldPriceId['fldId'];
				//$homemakingRequirementData['fldNative'] = $palNativeMap[$post['native']];
				$homemakingRequirementData['fldNative'] = $post['native']==''?'不限':$post['native'];
				$homemakingRequirementData['fldAge'] = $post['age']==''?0:$post['age'];
				//$homemakingRequirementData['fldDegree'] = $post['education'];
				$homemakingRequirementData['fldDegree'] = 0;
				//$homemakingRequirementData['fldMarryStatus'] = $post['marry']==''?0:$post['marry'];
				//$homemakingRequirementData['fldWorkYears'] = $post['workYear']==''?0:$post['workYear'];
				$homemakingRequirementData['fldMarryStatus'] = $post['workYear']==''?0:$post['workYear'];
				$homemakingRequirementData['fldLanguage'] = $post['language']==''?0:$post['language'];
				//$homemakingRequirementData['fldRemark'] = $post['special'];
				//$homemakingRequirementData['fldReserve'] = $post['reserve']==''?3:$post['reserve'];
				$homemakingRequirementData['fldRemark'] = $post['reserve']==''?'暂不上门':$post['reserve'];
				$homemakingRequirementData['fldStatus'] = 0;
				$homemakingRequirementData['fldOwner'] = 'admin';
				$homemakingRequirementData['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homemakingRequirementData['lastEditby'] = 'admin';
				$homemakingRequirementData['lastEditdt'] = date('Y-m-d H:i:s',time());
				$homemakingRequirement = M('homemaking_requirement');
				$homemakingRequirementId = $homemakingRequirement->add($homemakingRequirementData);
				//echo $homemakingRequirement->getDbError();
				if($homemakingRequirementId){
					$homeMycustomer = D('HomeMycustomer');
					$homeMycustomerData = array();
					$homeMycustomerData['fldCustomerID'] = $homeCustomerId;
					$homeMycustomerData['fldRequireId'] = $homemakingRequirementId;
					$homeMycustomerData['fldWorkAddress'] = $post['place'].$post['placeDetail'];
					$homeMycustomerData['fldWorkProject'] = $type == 1?'月嫂':'育婴嫂';
					$homeMycustomerData['fldAmount'] = substr($post['price'],0,-3);
					$homeMycustomerData['fldserviceFee'] = $type == 1?1700:1500;
					$homeMycustomerData['fldcommision'] = $type == 1?1700:1500;
					$homeMycustomerData['fldInsurance'] = 1;
					$homeMycustomerData['fldInsuranceFee'] = $type == 1?50:150;
					$homeMycustomerData['fldCustomerName'] = $post['user'];
					//$homeMycustomerData['fldMobile'] = $post['phone'];
					$homeMycustomerData['fldserverState'] = 0;
					$homeMycustomerData['fldreserveTime'] = $post['dueDate'];
					$homeMycustomerData['fldOrderFrom'] = '微信';
					$homeMycustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
					$homeMycustomerData['lasteditby'] = 'admin';
					$homeMycustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$addResult = $homeMycustomer->add($homeMycustomerData);
					//echo $homeMycustomer->getDbError();
					if($type==1){
						$updateResult = $homeCustomer->where(array('fldID'=>$homeCustomerId))->save(array('fldchildbirth'=>$post['dueDate'],'fldBadyBrithday'=>null,'lasteditdt'=>date('Y-m-d H:i:s',time()),'lasteditby'=>'admin'));
					}elseif($type==2){
						$updateResult = $homeCustomer->where(array('fldID'=>$homeCustomerId))->save(array('fldchildbirth'=>null,'fldBadyBrithday'=>$post['dueDate'],'lasteditdt'=>date('Y-m-d H:i:s',time()),'lasteditby'=>'admin'));
					}
				}
			}
			if($addResult){
				session('requirement',null);
				$result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请保持电话畅通，您的专属顾问会尽快为您提供服务。','0769-87073668',session('openname'),'',0);
				//$result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请等待工作人员为您安排时间','0769-87073668',session('openname'),'请保持电话畅通，网站工作人员会尽快为您提供服务。',0);
				//$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccess');
				if ($type == 1) {
					$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuesao');
				}else {
					$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuyingsao');
				}
			 }else{
				$this->error('新增失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>$type),''));
			 }
		}else {
			$this->error('预约失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>$type),''));
		}
	}
	
	/**
	  * customerCenter
	  * 客户中心
	  * @access public
	  * @return void
	  * @date 2015-05-26
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function customerCenter(){
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		//$uid = 115;
		//没有注册，推送消息提醒
		if(!$uid){
			/*if(!session('openid')){
				$this->error('请在微信上打开，或者重新进入页面');
			}*/
			$result = $this->sendWeixinTemplateMessage('noReservation','您还没未预约服务需求!','您尚未预约月嫂/育婴嫂服务，暂不能查看客户中心。','请在菜单栏点击我要预约','','您也可以电话预约服务，免费预约热线0769-87073668',0);
			import('ORG.Util.Jssdk');
			$jssdk = new JSSDK(APPID, APPSECRET);
			$signPackage = $jssdk->GetSignPackage();
			exit("<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
				<script>
					wx.config({
						debug: false,
						appId: '{$signPackage['appId']}',
						timestamp: {$signPackage['timestamp']},
						nonceStr: '{$signPackage['nonceStr']}',
						signature: '{$signPackage['signature']}',
						jsApiList: [
						  // 所有要调用的 API 都要加到这个列表中
						  'getLocation','openLocation','closeWindow'
						  //'getLocation,openLocation'
						  
						]
					  });
					wx.ready(function () {
					 	 wx.closeWindow();
					});
				 </script>");
		}else{
			//获取籍贯编号映射
			/*$palNative = D('palNative');
			$result = $palNative->select();
			$palNativeMap = array();
			$palNativeMap['0'] = '不限';
			foreach($result as $value){
				$palNativeMap[$value['fldNo']] = $value['fldName'];
			}*/
			/*$homemakingSetting = M('homemaking_setting');
			$degree = $homemakingSetting->field('fldValue')->where(array('fldName'=>'degree'))->find();
			$age = $homemakingSetting->field('fldValue')->where(array('fldName'=>'age'))->find();
			$language = $homemakingSetting->field('fldValue')->where(array('fldName'=>'language'))->find();
			$marry = $homemakingSetting->field('fldValue')->where(array('fldName'=>'marry'))->find();
			//构造学历映射
			$degreeMap = json_decode($degree['fldValue']);
			//构造年龄映射
			$ageMap = json_decode($age['fldValue']);
			//构造语言映射
			$languageMap = json_decode($language['fldValue']);
			//构造婚育状况映射
			$marryMap = json_decode($marry['fldValue']);
			*/
			
			$homemakingSetting = M('homemaking_setting');
			//获取学历映射
			if (S('degreeMap')) {
				$degreeMap = S('degreeMap');
			}else {
				$degree = $homemakingSetting->field('fldValue')->where(array('fldName'=>'degree'))->find();
				$degreeMap = json_decode($degree['fldValue']);
				S('degreeMap',$degreeMap,3600);
			}
			//获取年龄映射
			if (S('ageMap')) {
				$ageMap = S('ageMap');
			}else {
				$age = $homemakingSetting->field('fldValue')->where(array('fldName'=>'age'))->find();
				$ageMap = json_decode($age['fldValue']);
				S('ageMap',$ageMap,3600);
			}
			//获取语言映射
			if (S('languageMap')) {
				$languageMap = S('languageMap');
			}else {
				$language = $homemakingSetting->field('fldValue')->where(array('fldName'=>'language'))->find();
				$languageMap = json_decode($language['fldValue']);
				S('languageMap',$languageMap,3600);
			}
			//获取婚育状况映射
			if (S('marryMap')) {
				$marryMap = S('marryMap');
			}else {
				$marry = $homemakingSetting->field('fldValue')->where(array('fldName'=>'marry'))->find();
				$marryMap = json_decode($marry['fldValue']);
				S('marryMap',$marryMap,3600);
			}
			//获取工作经验映射
			if (S('workExperienceMap')) {
				$workExperienceMap = S('workExperienceMap');
			}else {
				$workExperience = $homemakingSetting->field('fldValue')->where(array('fldName'=>'workExperience'))->find();
				$workExperienceMap = json_decode($workExperience['fldValue'],true);
				S('workExperienceMap',$workExperienceMap,3600);
			}
			//获取工作年限映射数组
			if (S('workyearsMap') && S('wy')) {
				$workyearsMap = S('workyearsMap');
				$wy = S('wy');
			}else {
				$homemakingSetting = M('homemaking_setting');
				$workyears = $homemakingSetting->field('fldValue')->where(array('fldName'=>'workyears'))->find();
				$workyearsMap = $this->_objectToArray(json_decode($workyears['fldValue']));
				$wy = array_merge(array(0=>-1),array_keys($workyearsMap));
				S('workyearsMap',$workyearsMap,3600);
				S('wy',$wy,3600);
			}
			
			//获取等级证书映射
			if (S('gradeCertificateMap')) {
				$gradeCertificateMap = S('gradeCertificateMap');
			}else {
				$homemakingServicePriceDetail = D('HomemakingServicePriceDetail');
				//$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->field('fldId,fldParentId,fldGrade,fldPrice')->relation(true)->where(array('fldStatus'=>1))->select();
				$homemakingServicePriceDetailResult  = $homemakingServicePriceDetail->alias('spd')->field('spd.fldId,spd.fldParentId,spd.fldGrade,spd.fldPrice,sp.fldType')->join('right join  homemaking_service_price as sp on spd.fldParentId = sp.fldID')->relation(true)->where(array('fldStatus'=>1))->select();
				foreach ($homemakingServicePriceDetailResult as $key1 => $value1) {
					foreach ($value1['homemaking_certificate'] as $key2 => $value2) {
						if ($value2['fldStatus'] == 1) {
							$gradeCertificateMap[$value1['fldType']][$value1['fldGrade']][] = $value2['fldName'];
						}
					}
				}
				S('gradeCertificateMap',$gradeCertificateMap,3600);
			}
			
			//客户个人信息
			$homeCustomer = D('HomeCustomer');
			$homeCustomerInfo = $homeCustomer->field('fldID,fldName,fldSex,fldMobile,fldAddress,CONVERT(varchar(100), fldchildbirth, 120) as fldchildbirth,CONVERT(varchar(100), fldBadyBrithday, 120) as fldBadyBrithday,fldPerUserId,fldWeiXinCode')->where("fldPerUserId = ".$uid)->find();
			//echo $homeCustomer->getLastSql();
			//echo $homeCustomer->getDbError();
			//没有客户信息，则提醒
			if(!$homeCustomerInfo){
				$result = $this->sendWeixinTemplateMessage('noReservation','您还没未预约服务需求!','您尚未预约月嫂/育婴嫂服务，暂不能查看客户中心。','请在菜单栏点击我要预约','','您也可以电话预约服务，免费预约热线0769-87073668',0);
				import('ORG.Util.Jssdk');
				$jssdk = new JSSDK(APPID, APPSECRET);
				$signPackage = $jssdk->GetSignPackage();
				exit("<script src='http://res.wx.qq.com/open/js/jweixin-1.0.0.js'></script>
					<script>
						wx.config({
							debug: false,
							appId: '{$signPackage['appId']}',
							timestamp: {$signPackage['timestamp']},
							nonceStr: '{$signPackage['nonceStr']}',
							signature: '{$signPackage['signature']}',
							jsApiList: [
							  // 所有要调用的 API 都要加到这个列表中
							  'getLocation','openLocation','closeWindow'
							  //'getLocation,openLocation'
							  
							]
						  });
						wx.ready(function () {
						 	 wx.closeWindow();
						});
					 </script>");
			}else {
				$homeCustomerInfo['PhotoUrl'] = empty($homeCustomerInfo['fldWeiXinCode'])?'Uploads/Weixin/userphoto.jpg':'Uploads/Weixin/'.$homeCustomerInfo['fldWeiXinCode'].'.jpg';
				//$homeCustomerInfo['fldchildbirth'] = empty($homeCustomerInfo['fldchildbirth'])?null:date_format($homeCustomerInfo['fldchildbirth'], 'Y.m.d');
				//$homeCustomerInfo['fldBadyBrithday'] = empty($homeCustomerInfo['fldBadyBrithday'])?null:date_format($homeCustomerInfo['fldBadyBrithday'], 'Y.m.d');
				$homeCustomerInfo['fldchildbirth'] = empty($homeCustomerInfo['fldchildbirth'])?null:date('Y.m.d',strtotime($homeCustomerInfo['fldchildbirth']));
				$homeCustomerInfo['fldBadyBrithday'] = empty($homeCustomerInfo['fldBadyBrithday'])?null:date('Y.m.d',strtotime($homeCustomerInfo['fldBadyBrithday']));
				//echo $homeCustomer->getDbError();
				//dump($homeCustomerInfo['fldchildbirth']);
				//dump($homeCustomerInfo['fldBadyBrithday']);
				//客户预约需求
				$homemakingRequirement = M('HomemakingRequirement');
				$homemakingRequirementInfo = $homemakingRequirement->field('fldId,fldPersonId,fldType,fldPriceId,fldStatus,fldNative,fldDegree,fldAge,fldLanguage,fldMarryStatus,fldRemark')->where(array('fldPersonId'=>$homeCustomerInfo['fldID']))->order('fldId desc')->select();
				foreach($homemakingRequirementInfo as $key=>$value){
					$price = M('HomemakingServicePriceDetail')->field('fldId,fldGrade,fldPrice,fldOrderMoney')->where(array('fldId'=>$value['fldPriceId'],'fldStatus'=>1))->find();
					if($price){
						$homemakingRequirementInfo[$key]['Price'] = round($price['fldPrice']);
						$homemakingRequirementInfo[$key]['Grade'] = $price['fldGrade'];
						$homemakingRequirementInfo[$key]['OrderMoney'] = $price['fldOrderMoney'];
					}
					//$homemakingRequirementInfo[$key]['Native'] = $value['fldNative'] === null?'未设置':$palNativeMap[$value['fldNative']];
					//$homemakingRequirementInfo[$key]['Degree'] = $value['fldDegree'] === null?'未设置':$degreeMap[$value['fldDegree']];
					//$homemakingRequirementInfo[$key]['Marry'] = $value['fldMarryStatus'] === null?'未设置':$marryMap[$value['fldMarryStatus']];
					$homemakingRequirementInfo[$key]['WorkYear'] = $value['fldMarryStatus'] === null?'未设置':$workExperienceMap[$value['fldMarryStatus']];
					$homemakingRequirementInfo[$key]['Age'] =  $value['fldAge'] === null?'未设置':$ageMap[$value['fldAge']];
					$homemakingRequirementInfo[$key]['Language'] = $value['fldLanguage'] === null?'未设置':str_replace('*',' ',str_replace('国语','普通话',$languageMap[$value['fldLanguage']]));
					
					//客户服务
					$service = array();
					$homeMycustomer = D('HomeMycustomer','',DB_CONFIG3);
					$homeMycustomerResult = $homeMycustomer->field('fldID,fldAmount,fldserviceFee,fldcommision,fldInsuranceFee,fldBeginDate,fldEndDate,fldserverState')->where(array('fldRequireId'=>$value['fldId']))->find();
					//echo $homeMycustomer->getDbError();
					if($homeMycustomerResult){
						$homeMycustomerResult['fldBeginDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldBeginDate']));
						$homeMycustomerResult['fldEndDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldEndDate']));
						$homeMycustomerResult['fldAmount'] = round($homeMycustomerResult['fldAmount']);
						$homeMycustomerResult['Deposit'] = $homeMycustomerResult['fldserviceFee'] + $homeMycustomerResult['fldInsuranceFee'];
						$homeMyCustServices = D('HomeMyCustServices');
						$homeMyCustServicesResult = $homeMyCustServices->field('fldHomeID,fldFlag')->where('fldMycustomerID = '.$homeMycustomerResult['fldID'].'and (fldFlag = 1 or fldFlag = 2) ')->find();
						//echo $homeMyCustServices->getDbError();
					}
					if(homeMyCustServicesResult){
						$service = array_merge_recursive($homeMycustomerResult,$homeMyCustServicesResult);
						$homeInfo = D('HomeInfo');
						if($value['fldType'] == 1){
							$homeInfoResult = $homeInfo->field('fldID,fldName,fldSex,fldage,fldLanguage,fldMarryStatus,fldNative,fldPosition,fldType,fldIDCard,fldEducation,fldGrade,fldLocation,fldSpecialtyDish,fldTakeBabyNum')->where(array('fldID'=>$homeMyCustServicesResult['fldHomeID']))->find();
						}else{
							$homeInfoResult = $homeInfo->field('fldID,fldName,fldSex,fldage,fldLanguage,fldMarryStatus,fldNative,fldPosition,fldType,fldIDCard,fldEducation,fldGrade,fldLocation,fldSpecialtyDish,fldWorkYears')->where(array('fldID'=>$homeMyCustServicesResult['fldHomeID']))->find();
						}
						if($homeInfoResult){
							//echo $homeInfo->getLastSql();
							unset($homeInfoResult['fldID']);
							$homeInfoResult['fldage'] = round($homeInfoResult['fldage']);
							$homeInfoResult['Marry'] = null != $homeInfoResult['fldMarryStatus']?$marryMap[$homeInfoResult['fldMarryStatus']]:'未设置';
							if (!($homeInfoResult['fldWorkYears'] === null)) {
								foreach ($wy as $k => $v) {
									if (($homeInfoResult['fldWorkYears']+($wy[$k]-$wy[$k-1]))>$v) {
										$fldWorkYears = $workyearsMap[$v];
									}
								}
								$homeInfoResult['WorkYears']  = $fldWorkYears;
							}else {
								$homeInfoResult['WorkYears']  = '未设置';
							}
							//$homeInfoResult['WorkYears'] = null != $homeInfoResult['fldWorkYears']?$jobyearsMap[$homeInfoResult['fldWorkYears']]:'未设置';
							//处理语言问题，国语转普通话
							$homeInfoResult['fldLanguage'] = $homeInfoResult['fldLanguage'] === null?'未设置':str_replace('*',' ',str_replace('国语','普通话',$homeInfoResult['fldLanguage']));
							//获取证书
							/*$certificate =array();
							foreach ($homeInfoResult['homemaking_certificate'] as $k => $v) {
								if($v['fldStatus'] == 1){
									$certificate[$k] = $v['fldName'];
								}
							}
							unset($homeInfoResult['homemaking_sitter']);
							$homeInfoResult['homemaking_certificate'] = $certificate;*/
							if (!empty($homeInfoResult['fldGrade'])) {
								$homeInfoResult['homemaking_certificate'] = $gradeCertificateMap[$value['fldType']][$homeInfoResult['fldGrade']];
							}
							$service = array_merge_recursive($service,$homeInfoResult);
							$homemakingRequirementInfo[$key]['service'] = $service;
						}
					}
				}
			}
			$time = session('time');
			$newtime = time();
			$losetime = $newtime - $time;
			$losetime = 120 - $losetime;
			if ($losetime > 1 && $losetime < 120) {
				$this->assign('time', $losetime);
			}
			$this->assign('timestamp',time());
			$this->assign('homeCustomerInfo',$homeCustomerInfo);
			$this->assign('homemakingRequirementInfo',$homemakingRequirementInfo);
			$this->display('customerCenter');
		}
	}
	 
	/**
	  * customerCenterComment
	  * 客户中心点评
	  * @access public
	  * @return void
	  * @date 2015-05-27
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function customerCenterComment(){
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		$homemakingServiceComment = M('homemaking_service_comment');
		if (I('param.t') == 1) {
			$homemakingServiceCommentResult = $homemakingServiceComment->field('fldId,fldParentId,fldBabyWater,fldDealDoody,fldTouchBaby,fldWaterCloth,fldDevelopment,fldWatchBaby,fldHelpClean,fldHelpSuck,fldRoomHealth,fldMadeRice,fldHelpWay,fldHelpRecover,fldLoving,fldPositive,fldCommunication,fldCharacter,fldHealth,fldFormality,fldAssessment,cast(fldUploadFile as varchar(40)) as fldUploadFile,CONVERT(varchar(100), fldDate, 120) as fldDate')->where(array('fldParentId'=>I('param.cid'),'fldHomeID'=>I('param.hid')))->find();
		}else {
				$homemakingServiceCommentResult = $homemakingServiceComment->field('fldParentId,fldBabySitterServiceQuality,fldAssessment,cast(fldUploadFile as varchar(40)) as fldUploadFile,CONVERT(varchar(100), fldDate, 120) as fldDate')->where(array('fldParentId'=>I('param.cid'),'fldHomeID'=>I('param.hid')))->find();
		}
		if (IS_POST) {
			if ($homemakingServiceCommentResult) {
				$this->error('已经评论过，请勿重复评论。');
			}
			//$uploadFileKey = trim(com_create_guid(),'{}');
			$uploadFileKey = trim(getGUID(),'{}');
			$data['fldParentId'] = I('post.cid');
			$data['fldHomeID'] = I('post.hid');
			$data['fldDate'] = date('Y-m-d H:i:s',time());
			$data['fldAssessment'] = I('post.commentTxt').date('[Y.m.d]',time());
			$data['fldUploadFile'] = $uploadFileKey;
			$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
			$data['fldOwner'] = 'admin';
			if (I('post.t') == 1) {
				$data['fldBabyWater'] = I('post.nurse');
				$data['fldDealDoody'] = I('post.bianbian');
				$data['fldTouchBaby'] = I('post.anmo');
				$data['fldWaterCloth'] = I('post.xiaodu');
				$data['fldDevelopment'] = I('post.qianneng');
				$data['fldWatchBaby'] = I('post.yichang');
				$data['fldHelpClean'] = I('post.weisheng');
				$data['fldHelpSuck'] = I('post.buru');
				$data['fldRoomHealth'] = I('post.fangjian');
				$data['fldMadeRice'] = I('post.yuezican');
				$data['fldHelpWay'] = I('post.xinde');
				$data['fldHelpRecover'] = I('post.chanhou');
				$data['fldLoving'] = I('post.zeren');
				$data['fldPositive'] = I('post.taidu');
				$data['fldCommunication'] = I('post.goutong');
				$data['fldCharacter'] = I('post.xingge');
				$data['fldHealth'] = I('post.xiguan');
				$data['fldFormality'] = I('post.wenming');
			}else {
				$data['fldBabySitterServiceQuality'] = I('post.star');
			}
			$addResult = $homemakingServiceComment->add($data);
			if ($addResult) {
				//$model = new Model();
				$homemakingImageUrl = D('homemaking_image_url');
				$data = array();
				$data['fldKey'] = $uploadFileKey;
				$data['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$data['fldOwner'] = 'admin';
				foreach (session(I('param.cid').'uploadFileName') as $key => $value) {
					$data['fldFileName'] = $key;
					$data['fldUrl'] = $value;
					$addResult = $addResult && $homemakingImageUrl->add($data);
					/*$add = "insert into sysAttachFile values('".$key."','".$value."','\\\\192.168.0.19\\oaupload\\school_system\\weixin\\','','".date('Y-m-d H:i:s',time())."','Weixin','".$uploadFileKey."','','');";
					$addResult = $addResult && $model->execute($add);*/
				}
				session(I('param.cid').'uploadFileName',null);
			}
			//echo $homemakingServiceComment->getLastSql();
			//echo $homemakingServiceComment->getDbError();
			if ($addResult) {
				//$this->success('评价成功');exit();
				redirect(U('Homemaking-customerCenterComment',array('v'=>time(),'t'=>I('post.t'),'rid'=>I('post.rid'),'cid'=>I('post.cid'),'hid'=>I('post.hid')),''));
			}else {
				$this->error('评价失败');exit();
			}
		}
		//$uid = 115;
		//没有注册，推送消息提醒
		if(!$uid){
			$this->error('非法操作！');
		}else{
			//客户个人信息
			$homeCustomer = D('HomeCustomer');
			$homeCustomerInfo = $homeCustomer->field('fldID,fldName,fldSex,fldMobile,fldAddress,CONVERT(varchar(100), fldchildbirth, 120) as fldchildbirth,CONVERT(varchar(100), fldBadyBrithday, 120) as fldBadyBrithday,fldPerUserId,fldWeiXinCode')->where("fldPerUserId = ".$uid)->find();
			$homeCustomerInfo['PhotoUrl'] = empty($homeCustomerInfo['fldWeiXinCode'])?'':'Uploads/Weixin/'.$homeCustomerInfo['fldWeiXinCode'].'.jpg';
			//$homeCustomerInfo['fldchildbirth'] = empty($homeCustomerInfo['fldchildbirth'])?null:date_format($homeCustomerInfo['fldchildbirth'], 'Y.m.d');
			//$homeCustomerInfo['fldBadyBrithday'] = empty($homeCustomerInfo['fldBadyBrithday'])?null:date_format($homeCustomerInfo['fldBadyBrithday'], 'Y.m.d');
			$homeCustomerInfo['fldchildbirth'] = empty($homeCustomerInfo['fldchildbirth'])?null:date('Y.m.d',strtotime($homeCustomerInfo['fldchildbirth']));
			$homeCustomerInfo['fldBadyBrithday'] = empty($homeCustomerInfo['fldBadyBrithday'])?null:date('Y.m.d',strtotime($homeCustomerInfo['fldBadyBrithday']));
			
			//客户预约需求
			$homemakingRequirement = M('HomemakingRequirement');
			$homemakingRequirementInfo = $homemakingRequirement->field('fldId,fldPersonId,fldType,fldPriceId,fldStatus,fldNative,fldDegree,fldAge,fldLanguage,fldMarryStatus,fldRemark')->where(array('fldId'=>I('get.rid')))->find();
			$price = M('HomemakingServicePriceDetail')->field('fldId,fldGrade,fldPrice,fldOrderMoney')->where(array('fldId'=>$homemakingRequirementInfo['fldPriceId'],'fldStatus'=>1))->find();
			if($price){
				$homemakingRequirementInfo['Price'] = round($price['fldPrice']);
				$homemakingRequirementInfo['Grade'] = $price['fldGrade'];
				$homemakingRequirementInfo['OrderMoney'] = $price['fldOrderMoney'];
				
				//处理语言问题，国语转普通话
				$homemakingRequirementInfo['Language'] = $homemakingRequirementInfo['fldLanguage'] === null?'未设置':str_replace('*',' ',str_replace('国语','普通话',$homemakingRequirementInfo['fldLanguage']));
			}
			
			//客户服务
			$homeMycustomer = D('HomeMycustomer');
			$homeMycustomerResult = $homeMycustomer->field('fldID,fldAmount,fldserviceFee,fldcommision,fldInsuranceFee,fldBeginDate,fldEndDate,fldserverState')->where(array('fldID'=>I('get.cid')))->find();
			//echo $homeMycustomer->getDbError();
			if($homeMycustomerResult){
				$homeMycustomerResult['fldBeginDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldBeginDate']));
				$homeMycustomerResult['fldEndDate'] = date('Y.m.d',strtotime($homeMycustomerResult['fldEndDate']));
				$homeMycustomerResult['fldAmount'] = round($homeMycustomerResult['fldAmount']);
				$homeMycustomerResult['Deposit'] = $homeMycustomerResult['fldserviceFee'] + $homeMycustomerResult['fldInsuranceFee'];
				$homemakingRequirementInfo['service'] = $homeMycustomerResult;
			}
			$homeInfo = D('HomeInfo');
			$homeInfoResult = $homeInfo->field('fldID,fldName')->where(array('fldID'=>I('get.hid')))->find();
			if ($homeInfoResult) {
				$homemakingRequirementInfo['fldName'] = $homeInfoResult['fldName'];
			}
			
			//echo $homemakingServiceComment->getLastSql();
			//显示评论
			if ($homemakingServiceCommentResult) {
				//查找图片URL
				if (!empty($homemakingServiceCommentResult['fldUploadFile'])) {
					$homemakingImageUrl = D('homemaking_image_url');
					$homemakingImageUrlResult = $homemakingImageUrl->field('fldFileName,fldUrl')->where(array('fldKey'=>$homemakingServiceCommentResult['fldUploadFile']))->select();
					if(!empty($homemakingImageUrlResult)){
						$homemakingImageUrlResultA = array();
						$prefix = 't_';
						for ($i=0; $i < count($homemakingImageUrlResult); $i++) { 
							$s = strstr($homemakingImageUrlResult[$i]['fldFileName'],'.',TRUE);
							if(strlen($s) == 36){
								$homemakingImageUrlResultT = array();
								$homemakingImageUrlResultT[] = $homemakingImageUrlResult[$i]['fldUrl'];
								for ($j=0; $j < count($homemakingImageUrlResult); $j++) { 
									//if (strpos($homemakingImageUrlResult[$j]['fldFileName'],$s) !== false) {
									if (strstr($homemakingImageUrlResult[$j]['fldFileName'],$prefix.$s)) {
										$homemakingImageUrlResultT[] = $homemakingImageUrlResult[$j]['fldUrl'];
									}
								}
								$homemakingImageUrlResultA[] = $homemakingImageUrlResultT;
							}
						}
						$homemakingServiceCommentResult['fldUploadFile'] = $homemakingImageUrlResultA;
					}
				}
				//计算评分
				if (I('param.t') == 1) {
					$homemakingServiceCommentResult['baby'] = round(($homemakingServiceCommentResult['fldBabyWater'] + $homemakingServiceCommentResult['fldDealDoody']  + $homemakingServiceCommentResult['fldTouchBaby'] + $homemakingServiceCommentResult['fldWaterCloth'] + $homemakingServiceCommentResult['fldDevelopment'] + $homemakingServiceCommentResult['fldWatchBaby'] )*100/6/5);
					$homemakingServiceCommentResult['mother'] = round(($homemakingServiceCommentResult['fldHelpClean'] + $homemakingServiceCommentResult['fldHelpSuck']  + $homemakingServiceCommentResult['fldRoomHealth'] + $homemakingServiceCommentResult['fldMadeRice'] + $homemakingServiceCommentResult['fldHelpWay'] + $homemakingServiceCommentResult['fldHelpRecover'] )*100/6/5);
					$homemakingServiceCommentResult['sitter'] = round(($homemakingServiceCommentResult['fldLoving'] + $homemakingServiceCommentResult['fldPositive']  + $homemakingServiceCommentResult['fldCommunication'] + $homemakingServiceCommentResult['fldCharacter'] + $homemakingServiceCommentResult['fldHealth'] + $homemakingServiceCommentResult['fldFormality'] )*100/6/5);
				}else {
					$homemakingServiceCommentResult['babySitterServiceQuality'] = $homemakingServiceCommentResult['fldBabySitterServiceQuality']*100/5;
				}
				$this->assign('homemakingServiceCommentResult',$homemakingServiceCommentResult);
			}
			if(session(I('get.cid').'uploadFileName')){
				$uploadFileName = session(I('get.cid').'uploadFileName');
				$uploadFile  = array();
				$i= 0;
				foreach ($uploadFileName as $key => $value) {
					if ($i%2 == 0) {
						$uploadFile[] = array('id'=>$key,'src'=>$uploadFileName['t_'.$key]);
					}
					$i++;
				}
				for ($i=0; $i < 3 - sizeof($uploadFileName)/2; $i++) { 
					$numArray[] = $i+1;
				}
				$this->assign('uploadFileSize',$numArray);
				$this->assign('uploadFile',$uploadFile);
			}else {
				$numArray = array(1,2,3);
				$this->assign('uploadFileSize',$numArray);
			}
			$time = session('time');
			$newtime = time();
			$losetime = $newtime - $time;
			$losetime = 120 - $losetime;
			if ($losetime > 1 && $losetime < 120) {
				$this->assign('time', $losetime);
			}
			$this->assign('t',I('get.t'));
			$this->assign('rid',I('get.rid'));
			$this->assign('cid',I('get.cid'));
			$this->assign('hid',I('get.hid'));
			$this->assign('homeCustomerInfo',$homeCustomerInfo);
			$this->assign('homemakingRequirementInfo',$homemakingRequirementInfo);
			$this->display('customerCenterComment');
		}
	}
	
	/**
	  * updateContact
	  * 客户中心更新联系方式
	  * @access public
	  * @return void
	  * @date 2015-05-28
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function updateContact(){
		if(IS_POST){
			if(I('post.id') && session('uid')){
				$condition = I('post.id')? " fldID = ".I('post.id').' ' : " fldPersonId = ".session('uid').' ';
				$user['fldMobile'] = I('post.phone');
				if(I('post.place') && I('post.place') != ''){
					$user['fldAddress'] = I('post.place');
				}
				$homeCustomer = D('HomeCustomer');
				$updateResult = $homeCustomer->where($condition)->save($user);
				if($updateResult){
					$code = cookie('per');
					if ($code){
						$redis = new Redis();
						$redis->connect('192.168.2.183', '6379');
						$redis->auth('job5156RedisMaster183');
						$res = $redis->HGetAll($code);
						$uid = $res['account_id'];
						$pid = $res['id'];
					}
					
					$url = APP_DOMIN.'Chitone-AccountUser-modifyUser';
					$phone = I('post.phone');
					$account['account_id'] = $uid;
					$account['per_user_id'] = $pid;
					$account['account'] = $phone;
					$account['mobile'] = $phone;
					if(I('post.place') && I('post.place') != ''){
						$account['address'] = I('post.place');
					}
					$data = urlencode(json_encode($account));
					$res = _get($url,$data);
					$status = $res['status'];
					if( status == 0){
						//$this->success('更新成功',U('Homemaking-customerCenter','',''));
						redirect(U('Homemaking-customerCenter',array('v'=>time()),''));
					}else{
						$this->error('更新失败',U('Homemaking-customerCenter','',''));
					}
				}else{
					$this->error('更新失败',U('Homemaking-customerCenter','',''));
				}
			}else{
				$this->error('非法操作',U('Homemaking-customerCenter','',''));
			}
		}
	}
	
	/**
	  * unpay
	  * 客户中心支付未支付订单
	  * @access public
	  * @return void
	  * @date 2015-06-03
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function unpay(){
		if (I('param.id') && session('uid')) {
			$homeCustomer = D('HomeCustomer');
			$homeCustomerResult = $homeCustomer->field('fldID')->where(array('fldPerUserId'=>session('uid')))->find();
			$homeMycustomer = D('HomeMycustomer');
			$homeMycustomerResult = $homeMycustomer->field('fldCustomerID,fldserviceFee,fldInsuranceFee')->where(array('fldRequireId'=>I('param.id')))->find();
			if ($homeCustomerResult['fldID'] == $homeMycustomerResult['fldCustomerID']) {
				$homemakingRequirement = M('homemaking_requirement');
				$type = $homemakingRequirement->field('fldType')->where(array('fldId'=>I('param.id')))->find();
				if ($type['fldType'] == 1) {
					$this->wxpay(I('param.id'),(($homeMycustomerResult['fldserviceFee']+$homeMycustomerResult['fldInsuranceFee'])*100),'预约定金','unpayYuesao');
				}else {
					$this->wxpay(I('param.id'),(($homeMycustomerResult['fldserviceFee']+$homeMycustomerResult['fldInsuranceFee'])*100),'预约定金','unpayYuyingsao');
				}
			}
		}else {
			$this->error('非法操作');
		}
	}
	
	/**
	  * upload
	  * 上传图片
	  * @access public
	  * @return void
	  * @date 2015-05-30
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function upload(){
		import('ORG.Net.UploadFile');
		$prefix = 't_';
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 2097152 ;// 设置附件上传大小
		//$upload->maxSize  = 3145728 ;// 设置附件上传大小
		//$upload->maxSize  = 5242880 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Uploads/';// 设置附件上传目录
		//$upload->saveRule =  substr(com_create_guid(),1,-1);
		$upload->saveRule =  substr(getGUID(),1,-1);
		$upload->thumb =  true;
		$upload->thumbPrefix = $prefix;
		$upload->thumbMaxWidth = '50,100';
		$upload->thumbMaxHeight = '50,100';
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->_wlog(date('Y-m-d H:i:s',time()).':'.$upload->getErrorMsg()."\r\n\r\n");
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			$timage = $this->crossDomainUpload(getcwd().substr($upload->savePath,1).$prefix.$info[0]['savename']);
			$image = $this->crossDomainUpload(getcwd().substr($upload->savePath,1).$info[0]['savename']);
			if ($timage && $image) {
				if(!session(I('param.cid').'uploadFileName')){
					session(I('param.cid').'uploadFileName',array($info[0]['savename']=>$image,$prefix.$info[0]['savename']=>$timage));
				}else{
					$uploadFileName = session(I('param.cid').'uploadFileName');
					$uploadFileName[$info[0]['savename']] = $image;
					$uploadFileName[$prefix.$info[0]['savename']] = $timage;
					session(I('param.cid').'uploadFileName',$uploadFileName);
				}
				$this->_wlog(date('Y-m-d H:i:s',time()).':'.$prefix.$info[0]['savename']."\r\n");
				$this->_wlog(date('Y-m-d H:i:s',time()).':'.$timage."\r\n");
				$this->_wlog(date('Y-m-d H:i:s',time()).':'.$info[0]['savename']."\r\n");
				$this->_wlog(date('Y-m-d H:i:s',time()).':'.$image."\r\n");
				$this->_wlog(date('Y-m-d H:i:s',time()).':'.json_encode(array('id'=>$info[0]['savename'],'src'=>$timage,'originsrc'=>$image))."\r\n\r\n");
				unlink(getcwd().substr($upload->savePath,1).$prefix.$info[0]['savename']);
				unlink(getcwd().substr($upload->savePath,1).$info[0]['savename']);
				echo json_encode(array('id'=>$info[0]['savename'],'src'=>$timage,'originsrc'=>$image));
				//$this->ajaxReturn(array('id'=>$info[0]['savename'],'src'=>$timage,'originsrc'=>$image));
			}else {
				$this->ajaxReturn('false');
			}
		 }
	 }
	 
	/**
	  * delete
	  * 删除图片
	  * @access public
	  * @return void
	  * @date 2015-05-30
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	 public function delete(){
		 if (I('post.id')) {
			$prefix = 't_';
			if(!session(I('param.cid').'uploadFileName')){
				die('false');
			}else{
				$uploadFileName = session(I('param.cid').'uploadFileName');
				unset($uploadFileName[I('post.id')]);
				unset($uploadFileName[$prefix.I('post.id')]);
				session(I('param.cid').'uploadFileName',$uploadFileName);
				die('true');
			}
		}else {
			die('false');
		}
	 }
	
	/**
	  * crossDomainUpload
	  * 跨域上传图片
	  * @access public
	  * @param string $fileLocation 图片路径
	  * @return string 图片URL
	  * @date 2015-06-05
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	//public function crossDomainUpload($fileLocation = 'E:\CYH\work\jiazheng\SVN\homemaking\Uploads\Weixin\userphoto.jpg'){
	public function crossDomainUpload($fileLocation = '/data/www/html/newer/homemaking/Uploads/Weixin/userphoto.jpg'){
		$uri = IMG_DOMAIN."open/api/common/upload";
		// post参数数组
		$data = array (
				//'file' => '@/data/www/html/newer/homemaking/Uploads/Weixin/userphoto.jpg',
				//'file' => '@E:\CYH\work\jiazheng\SVN\homemaking\Uploads\Weixin\oeRcKtyS7fTNhXCYiEdkS517alv8.jpg',
				//'file' => '@'.getcwd().'/Uploads/Weixin/userphoto.jpg',
				//'file' => '@'.getcwd().'\Uploads\Weixin\userphoto.jpg',
				'file' => '@'.$fileLocation,
				'type' => 'img',
				'proType' => 'house',
				'callbackURL'=>DOMAIN.'Homemaking-crossDomainUploadResult',
				'authToken'=>'93531dfa818a298c2ddb5eda4f4353ae'
		);
		//dump($data);dump($uri);
		//初始化
		$ch = curl_init ();
		//各种项设置，可以查看php手册设置
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_POST, 1 );//post方式
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		//执行
		$result = curl_exec ( $ch );
		//容错机制
		if($result === false){
			var_dump(curl_error($ch));
		}
		//curl_getinfo()获取各种运行中信息，便于调试
		$info = curl_getinfo($ch);
		//echo "执行时间".$info['total_time'].PHP_EOL;
		//释放
		curl_close ( $ch );
		$start = "document.getElementById(\"fileUrl\").value='";
		/*$end = "';
		document.getElementById(\"result\").value='";*/
		//$end = "';document.getElementById(\"result\").value='";
		//$end = "';\r";
		$end = "';";
		//dump($result);dump($this->_getBetween($result, $start, $end));
		//print_r($result);
		return ($this->_getBetween($result, $start, $end));
	}
	
	/**
	  * crossDomainUploadResult
	  * 跨域上传图片回调函数
	  * @access public
	  * @return void
	  * @date 2015-06-05
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function crossDomainUploadResult(){
		if (I('post.result') && I('post.result') == '成功') {
			$this->fileUrl = I('post.fileUrl');
		}
	}
	
	/**
	  * jssdk
	  * jssdk测试
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function jssdk(){
		import('ORG.Util.Jssdk');
		$jssdk = new JSSDK(APPID, APPSECRET);
		$signPackage = $jssdk->GetSignPackage();
		//dump($signPackage);
		$this->assign('signPackage',$signPackage);
		$this->display();
	}
	
	/**
	  * wxpay
	  * 微信支付
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function wxpay($homemakingRequirementId,$price=1,$detail='贡献一分钱!!!',$tpl='')
	{
		import('ORG.Util.WxPayPubHelper');
		//$price=1;
		//使用jsapi接口
		$jsApi = new JsApi_pub();
		
		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if(session('openid')){
			$openid = session('openid');
		}else{
			if(session('weixin_code')){
				$code = session('weixin_code');
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();
			}else{
				if (!isset($_GET['code']))
				{
					//触发微信返回code码
					$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
					Header("Location: $url"); 
				}else
				{
					//获取code码，以获取openid
					$code = $_GET['code'];
					$jsApi->setCode($code);
					$openid = $jsApi->getOpenId();
				}
			}
		}
		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub();
		
		//设置统一支付接口参数
		//设置必填参数
		$unifiedOrder->setParameter("openid","$openid");//商品描述
		$unifiedOrder->setParameter("body","$detail");//商品描述
		//自定义订单号，此处仅作举例
		$timeStamp = time();
		//$out_trade_no = WxPayConf_pub::APPID."zt".$homemakingRequirementId."jz"."$timeStamp";
		$out_trade_no = $this->_randString(8,'zT').$homemakingRequirementId."jz"."$timeStamp";
		
		$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
		$unifiedOrder->setParameter("total_fee","$price");//总金额
		$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		$unifiedOrder->setParameter("attach",$homemakingRequirementId);//商品ID
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
		//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
		//$unifiedOrder->setParameter("openid","XXXX");//用户标识
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
		//dump($jsApiParameters);
		$this->assign('price',$price/100);
		$this->assign('jsApiParameters',$jsApiParameters);
		$this->display($tpl);
	}
	
	/**
	  * wxpayNotify
	  * 微信支付回调
	  * @access public
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function wxpayNotify(){
		import('ORG.Util.WxPayPubHelper');
		
		//使用通用通知接口
		$notify = new Notify_pub();
		
		//存储微信的回调
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
		$notify->saveData($xml);
		
		//验证签名，并回应微信。
		//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
		}
		$returnXml = $notify->returnXml();
		echo $returnXml;
		
		//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
		
		//以log文件形式记录回调信息
		$log_ = new Log_();
		//$log_name="./notify_url.log";//log文件路径
		$log_name=LOG_PATH."/wxpayNotify_".date('Y_m_d',time()).".log";//log文件路径
		$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
		if($notify->checkSign() == TRUE)
		{
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
			}
			elseif($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
			}
			else{
				//此处应该更新一下订单状态，商户自行增删操作
				//$xmlArray = $this->_xml2array($xml);
				$xmlArray = $notify->xmlToArray($xml);
				$homemakingRequirement = M('homemaking_requirement');
				$result = $homemakingRequirement->where(array('fldId'=>$xmlArray['attach']))->save(array('fldStatus'=>1));
				/*if($result){
					session('homemakingRequirementId',null);
				}*/
				$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
			}
			
			//商户自行增加处理流程,
			//例如：更新订单状态
			//例如：数据库操作
			//例如：推送支付完成信息
		}
	}
	
	/**
	  * http_request 
	  * http请求公共函数
	  * @access protected
	  * @param string $url 请求链接
	  * @param string $data 请求数据
	  * @return string
	  * @date 2015-06-01
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	protected function http_request($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	/**
	  * sendWeixinTemplateMessage 
	  * 发送微信模板消息
	  * @access public
	  * @param string $type 消息类型
	  * @param string $first 消息头
	  * @param string $keyword1 填充字段1
	  * @param string $keyword2 填充字段2
	  * @param string $keyword3 填充字段3
	  * @param string $remark 消息备注
	  * @param string $uid 通行证ID
	  * @return boolean
	  * @date 2015-06-01
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function sendWeixinTemplateMessage($type,$first=WECHAT_NAME,$keyword1='',$keyword2='',$keyword3='',$remark='广东智通人才连锁股份有限公司',$uid=0){
		$access_token = accesstoken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$message['touser'] = session('openid');
		//$message['touser'] = "oeRcKt-i_5zl0utHfjsb8Umu6N0Y";
		if($type == 'changePassword'){
			$message['template_id'] = "behNiGKEUTZ9LAcTZwF6DDsSWltMM9-F4ZqBZBBOM0U";//测试号
			//$message['template_id'] = "AB1rMxBZBpADnwAHrFxHexfGLhXJyMzQ7hGz9RkYt_4";//国民妈妈
			//$message['template_id'] = "335sAIHAlOYH6xFpTBNaDbLrOYxNKXWEm_brFuTzKaY";//智通到家
			$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-changePassword-id-".$uid."-u-".$keyword1."-p-".$keyword2."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
			$message['topcolor'] = "#FF0000";
			$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
			$message['data']['keyword1'] = array('value'=>$keyword1,'color'=>'#173177');
			$message['data']['keyword2'] = array('value'=>$keyword2,'color'=>'#173177');
			$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
		}elseif($type == 'noReservation'){
			$message['template_id'] = "CBwV6wZLLbjiNSCNllwAnyEMYb8dHMsNHIWjMZF532M";//测试号
			//$message['template_id'] = "lym5WBrNN8b_DUiwRvk4oPWjZV32tPJ2_hRiHBRz8dk";//国民妈妈
			//$message['template_id'] = "__HvOZMFWOk2jKG8HOH1IpaZxNBoZJoq4prLTL4yUBs";//智通到家
			//$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-sendWeixinTemplateMessage4&scope=snsapi_base&state=123#wechat_redirect";
			$message['topcolor'] = "#FF0000";
			$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
			$message['data']['keyword1'] = array('value'=>$keyword1,'color'=>'#173177');
			$message['data']['keyword2'] = array('value'=>$keyword2,'color'=>'#173177');
			$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
			$message['topcolor'] = "#FF0000";
		}elseif($type == 'reservationSuccess'){
			$message['template_id'] = "IPKd4TUn9oHTQpF03e0ywWUg4kIiOoZqsffR6L_U-UU";;//测试号
			//$message['template_id'] = "saSxHBUjYrvR01V5srbqDdcFmL2GHzCpNeWvK1PlAd8";//国民妈妈
			//$message['template_id'] = "S97upe3J4U7IrnAOKJOwKq3jsoXWj2KASzGQknn1Rs0";//智通到家
			//$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-sendWeixinTemplateMessage3&scope=snsapi_base&state=123#wechat_redirect";
			$message['topcolor'] = "#FF0000";
			$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
			$message['data']['keynote1'] = array('value'=>$keyword1,'color'=>'#173177');
			$message['data']['keynote2'] = array('value'=>$keyword2,'color'=>'#173177');
			$message['data']['keynote3'] = array('value'=>$keyword3,'color'=>'#173177');
			$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
			$message['topcolor'] = "#FF0000";
		}
		$data = urlencode(json_encode($message));
		$res = $this->http_request($url,urldecode(json_encode($message)));
		$res = json_decode($res,true);
		if($res['errcode'] === 0){
			return true;
		}elseif($res['errcode'] == 40001){
			S(APPID.'accesstoken',null);
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".accesstoken();
			$res = $this->http_request($url,urldecode(json_encode($message)));
			$res = json_decode($res,true);
			if($res['errcode'] == 0){
				return true;
			}else{
				return false;
			}
		}else {
			return false;
		}
	}
	
	public function sendWeixinTemplateMessage2($uid = 0,$user_account = '',$user_password ='',$type = 1){
		$uid = 115;
		$first = '您已成功注册金牌月嫂账户';
		$remark = '温馨提醒：请妥善保管您的账号信息，为防止密码泄露，建议您立即修改密码。点此修改用户名密码';
		$user_account = "13798719518";
		$user_password = "zt1234";
		$access_token = accesstoken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		//$message['touser'] = "oRaVSuKw47upvPuyZmIOu9tj1gf0";
		//$message['touser'] = "oeRcKt5KFJwkhApXJyrrACBh_if8";
		$message['touser'] = "oeRcKt-i_5zl0utHfjsb8Umu6N0Y";
		//$message['touser'] = "oeRcKt1H2nJ54gTG148dHOm9wAhA";
		
		//$message['template_id'] = "JtMbGFnbU0VFMqewpulTTYx9HqztqTXyli2C8IPrErE";
		$message['template_id'] = "AB1rMxBZBpADnwAHrFxHexfGLhXJyMzQ7hGz9RkYt_4";
		$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-changePassword-id-".$uid."-u-".$user_account."-p-".$user_password."&scope=snsapi_base&state=123#wechat_redirect";
		$message['topcolor'] = "#FF0000";
		$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
		$message['data']['keyword1'] = array('value'=>$user_account,'color'=>'#173177');
		$message['data']['keyword2'] = array('value'=>$user_password,'color'=>'#173177');
		$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
		
		/*
		//$message['touser'] = session('openid');
		$message['touser'] = "oRaVSuKw47upvPuyZmIOu9tj1gf0";
		$message['template_id'] = "6GYxr-Hhma5UEO55Ki5pvxVl82-f9wZ5CjWWiCUF3dU";
		$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=http://ariaasia.gicp.net/Homemaking-changePassword-id-".$uid."-u-".$user_account."-p-".$user_password."&scope=snsapi_base&state=123#wechat_redirect";
		$message['topcolor'] = "#FF0000";
		$message['data']['username'] = array('value'=>$user_account,'color'=>'#173177');
		$message['data']['password'] = array('value'=>$user_password,'color'=>'#173177');*/
		$data = urlencode(json_encode($message));
		
		$res = $this->http_request($url,urldecode(json_encode($message)));
		//var_dump($res);
		dump(json_decode($res,true));
		//$code = $res['errcode'];
		if($res['errcode'] == 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function sendWeixinTemplateMessage3($uid = 0,$keyword1 = '',$keyword2 ='',$type = 1){
		$uid = 115;
		$first = '您已预约成功!';
		$keyword1 = "请等待工作人员为您安排时间";
		$keyword2 = "0769-87073668";
		$keyword3 = session('openname');
		$remark = '请保持电话畅通，网站工作人员会尽快为您提供服务。';
		$access_token = accesstoken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		//$message['touser'] = "oRaVSuKw47upvPuyZmIOu9tj1gf0";
		//$message['touser'] = "oeRcKt5KFJwkhApXJyrrACBh_if8";
		$message['touser'] = "oeRcKt-i_5zl0utHfjsb8Umu6N0Y";
		//$message['touser'] = "oeRcKt1H2nJ54gTG148dHOm9wAhA";
		
		//$message['template_id'] = "JtMbGFnbU0VFMqewpulTTYx9HqztqTXyli2C8IPrErE";
		$message['template_id'] = "saSxHBUjYrvR01V5srbqDdcFmL2GHzCpNeWvK1PlAd8";
		//$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-sendWeixinTemplateMessage3&scope=snsapi_base&state=123#wechat_redirect";
		$message['topcolor'] = "#FF0000";
		$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
		$message['data']['keynote1'] = array('value'=>$keyword1,'color'=>'#173177');
		$message['data']['keynote2'] = array('value'=>$keyword2,'color'=>'#173177');
		$message['data']['keynote3'] = array('value'=>$keyword3,'color'=>'#173177');
		$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
		
		$data = urlencode(json_encode($message));
		$res = $this->http_request($url,urldecode(json_encode($message)));
		//var_dump($res);
		dump(json_decode($res,true));
		//$code = $res['errcode'];
		if($res['errcode'] == 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function sendWeixinTemplateMessage4($uid = 0,$keyword1 = '',$keyword2 ='',$type = 1){
		$uid = 115;
		$first = '您还没未预约服务需求!';
		$keyword1 = "您尚未预约月嫂/育婴嫂服务，暂不能查看客户中心。";
		$keyword2 = "请在菜单栏点击我要预约";
		$remark = '您也可以电话预约服务，免费预约热线0769-87073668';
		$access_token = accesstoken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		//$message['touser'] = "oRaVSuKw47upvPuyZmIOu9tj1gf0";
		//$message['touser'] = "oeRcKt5KFJwkhApXJyrrACBh_if8";
		//$message['touser'] = "oeRcKt-i_5zl0utHfjsb8Umu6N0Y";
		$message['touser'] = "oeRcKt1H2nJ54gTG148dHOm9wAhA";
		
		//$message['template_id'] = "JtMbGFnbU0VFMqewpulTTYx9HqztqTXyli2C8IPrErE";
		$message['template_id'] = "lym5WBrNN8b_DUiwRvk4oPWjZV32tPJ2_hRiHBRz8dk";
		//$message['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".APPID."&redirect_uri=".DOMAIN."Homemaking-sendWeixinTemplateMessage4&scope=snsapi_base&state=123#wechat_redirect";
		$message['topcolor'] = "#FF0000";
		$message['data']['first'] = array('value'=>$first,'color'=>'#173177');
		$message['data']['keyword1'] = array('value'=>$keyword1,'color'=>'#173177');
		$message['data']['keyword2'] = array('value'=>$keyword2,'color'=>'#173177');
		$message['data']['remark'] = array('value'=>$remark,'color'=>'#173177');
		
		$data = urlencode(json_encode($message));
		$res = $this->http_request($url,urldecode(json_encode($message)));
		//var_dump($res);
		dump(json_decode($res,true));
		//$code = $res['errcode'];
		if($res['errcode'] == 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	  * _systemConstant 
	  * 系统常量定义
	  * @access private
	  * @return void
	  * @date 2015-05-31
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	private function _systemConstant(){
		//系统常量定义  
		//去THinkPHP手册中进行查找  
		echo "<br>"."网站的根目录地址".__ROOT__." ";  
		echo "<br>"."入口文件地址".__APP__." "; 
		echo "<br>"."当前模块地址".__URL__." "; 
		echo "<br>"."当前url地址".__SELF__." ";
		echo "<br>"."当前操作地址".__ACTION__." ";
		echo "<br>"."当前模块的模板目录".__CURRENT__." ";
		echo "<br>"."当前操作名称".ACTION_NAME." ";
		echo "<br>"."当前项目目录".APP_PATH." ";
		echo "<br>"."当前项目名称".APP_NAME." ";
		echo "<br>"."当前项目的模板目录".APP_TMPL_PATH." ";
		echo "<br>"."项目的公共文件目录".APP_PUBLIC_PATH." ";
		echo "<br>"."项目的配置文件目录".CONFIG_PATH." ";
		echo "<br>"."项目的公共文件目录".COMMON_PATH." ";
		//自动缓存与表相关的全部信息
		echo "<br>"."项目的数据文件目录".DATA_PATH." runtime下的data目录";
		echo "<br>"." ".GROUP_NAME."";
		echo "<br>"." ".IS_CGI."";
		echo "<br>"." ".IS_WIN."";
		echo "<br>"." ".LANG_SET."";
		echo "<br>"." ".LOG_PATH."";
		echo "<br>"." ".LANG_PATH."";
		echo "<br>"." ".TMPL_PATH."";
		//js放入的位置，供多个应用的公共资源
		echo "<br>"." ".WEB_PUBLIC_PATH."";
	}
	
	/**
	 * 地域选择（三级选择）
	 * @return [type] [description]
	 */
	public function areaSelect(){
		$ptimearea = getZoning();
		$code = I('post.code');
		
		if(S('ptimearea')){
			$ptimearea = S('ptimearea');
		}else{
			ksort($ptimearea);
			while (1) {
				$cur = current($ptimearea);
				$ck = key($ptimearea);
				
				if(!$cur){
					break;
				} 
				
				$next = next($ptimearea);
				$nk = key($ptimearea);
				if($ck%1000000 == 0 && $nk%1000000 != 0 && $nk%10000 == 0){
					$ptimearea[$ck]['hasChild'] = 1;
				}
				if($ck%10000 == 0 && $nk%10000 != 0 && $nk%100 == 0){
					$ptimearea[$ck]['hasChild'] = 1;
				}
			}
			
			S('ptimearea',$ptimearea,0);
		}
		
		if($code){
			if($code%1000000 == 0){
				$i = 0;
				foreach ($ptimearea as $key => $value) {
					if($code == 46000000 || $code == 11000000 || $code == 10000000 || $code == 12000000 || $code == 13000000){//三沙
						if($key%100 == 0 && $key > $code && $key < ($code+1000000)){
							$arr[$i]['code'] = $key;
							$arr[$i]['name'] = trim($value['name'],'"');
							$arr[$i]['hasChild'] = isset($value['hasChild']) ? $value['hasChild'] : 0;
							$i++;
						}
					}else{
						if($key%1000000 && $key%10000 == 0 && ($key > $code) && ($key < ($code+1000000))){
							$arr[$i]['code'] = $key;
							$arr[$i]['name'] = trim($value['name'],'"');
							$arr[$i]['hasChild'] = isset($value['hasChild']) ? $value['hasChild'] : 0;
							$i++;
						}
					}
				}
				
				$result = json_encode(array('items'=>$arr,"ret"=>1));
				echo $result;
				exit();
			}else if($code%10000 == 0){
				$i = 0;
				foreach ($ptimearea as $key => $value) {
					if($key%100 == 0 && $key > $code && $key < $code + 10000){
						$arr[$i]['code'] = $key;
						$arr[$i]['name'] = trim($value['name'],'"');
						$arr[$i]['hasChild'] = isset($value['hasChild']) ? $value['hasChild'] : 0;
						$i++;
					}
				}
				$result = json_encode(array('items'=>$arr,"ret"=>1));
				echo $result;
				exit();
			}
		}else{
			$i = 0;
			foreach ($ptimearea as $key => $value) {
				if($key%1000000 == 0){
					$arr[$i]['code'] = $key;
					$arr[$i]['name'] = trim($value['name'],'"');
					$arr[$i]['hasChild'] = 1;
					if($key == 47000000 || ($key > 40000000 && $key < 46000000)){//钓鱼岛、香港、澳门、台湾、国外、其他
						$arr[$i]['hasChild'] = 0;
					}
					$i++;
				}
			}
			$result = json_encode(array('items'=>$arr,"ret"=>1));
			echo $result;
		} 
	}
	
	/*************************************以下为已开发但暂时去掉的功能************************************/
	/**
	  * regist
	  * 注册
	  * @access public
	  * @return void
	  * @date 2015-05-21
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function regist(){
		$redirect = I('get.str') ? I('get.str') : I('get.redirect');
		if(IS_POST){
			//dump($_POST);
			 $openid = session('openid');
			 $userinfo = userinfo($openid);
			 $username = $userinfo['nickname'];
			 $headimgurl = $userinfo['headimgurl'];
			 $phone = I('post.phone');
			 $password = I('post.passwd');
			 //$password = $this->_randString(16,'ZT');
			 //$password = 'zt1234';
			 $user['open_id'] = $openid;
			 $user['user_account'] = $phone;
			 $user['user_password'] = md5($phone.':'.$password);
			 $user['ip'] = get_client_ip();
			 $user['open_name'] = $username;
			 $user['type'] ='weixin';
			 $user['role_type'] = '0';
			 $user['account_from'] = '3001';
			 $data = urlencode(json_encode($user));
			 $url = APP_DOMIN.'Chitone-Account-reg';
			 $res = _get($url,$data);
			 $code = $res['result']['code'];
			 cookie('per',$code,86400);
			 $uid = $res['result']['account_id'];
			 session('uid',$uid);
			 $this->getphoto($uid,$headimgurl);
			
			 //redirect('Homemaking-mamabang?redirect=' . $redirect, 0, '页面跳转中...');
			 $redirect = I('post.redirect');
			 $redirect = urlencode($redirect);
			 if($res){
				 if($redirect){
					redirect($redirect);
				 }else{
					redirect(U('Homemaking/index',array('t'=>'mamabang'),''));
				 }
			 }else{
				redirect(U('Homemaking/regist'));
			 }
		}
		//$area = getZoning();
		$this->assign('redirect',$redirect);
		$this->display("regist");
	}
	
	/**
	  * regist2
	  * 注册2
	  * @access public
	  * @return void
	  * @date 2015-05-21
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function regist2(){
		$redirect = I('get.str') ? I('get.str') : I('get.redirect');
		if(IS_POST){
			 $user_name = I('post.name');
			 $user_sex = I('post.sex');
			 $user_address = I('post.address');
			 $user_highest_diploma = I('post.highest_diploma');
			 $user_birthday = I('post.birthday');
			 $user_phone = I('post.phone');
			 $openid = session('openid');
			 $userinfo = userinfo($openid);
			 $username = $userinfo['nickname'];
			 $headimgurl = $userinfo['headimgurl'];
			 $phone = I('post.phone');
			 //$password = I('post.passwd');
			 //$password = $this->_randString(16,'ZT');
			 $password = 'zt1234';
			 $user['open_id'] = $openid;
			 $user['user_account'] = $phone;
			 $user['user_password'] = md5($phone.':'.$password);
			 $user['ip'] = get_client_ip();
			 $user['open_name'] = $username;
			 $user['type'] ='weixin';
			 $user['role_type'] = '0';
			 $user['account_from'] = '3001';
			 if(!empty($user_name)){
				 $user['user_name'] = $user_name;
			 }
			 if(!empty($user_sex)){
				 $user['gender'] = $user_sex;
			 }
			 if(!empty($user_address)){
				 $user['location'] = $user_address;
			 }
			 if(!empty($user_highest_diploma)){
				 $user['degree'] = $user_highest_diploma;
			 }
			 if(!empty($user_birthday)){
				 $user['birthday'] = $user_birthday;
			 }
			 if(!empty($user_phone)){
				 $user['mobile'] = $user_phone;
			 }
			 $data = urlencode(json_encode($user));
			 $url = APP_DOMIN.'Chitone-Account-reg';
			 $res = _get($url,$data);
			 $code = $res['result']['code'];
			 cookie('per',$code,86400);
			 $uid = $res['result']['account_id'];
			 session('uid',$uid);
			 $this->getphoto($uid,$headimgurl);
			
			 //redirect('Homemaking-mamabang?redirect=' . $redirect, 0, '页面跳转中...');
			 $redirect = I('post.redirect');
			 $redirect = urlencode($redirect);
			 if($res){
				 if($redirect){
					 redirect($redirect);
				 }else{
					 redirect(U('Homemaking/index',array('type'=>'mamabang')));
				 }
			 }else{
				 redirect(U('Homemaking/regist'));
			 }
		}
		$this->assign('redirect',$redirect);
		$this->display("regist");
	}
	
	/**
	  * mamabangajax
	  * 母婴问答ajax方法
	  * @access public
	  * @return void
	  * @date 2015-05-14
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function mamabangajax(){
		import("ORG.Util.AjaxPage");// 导入分页类  注意导入的是自己写的AjaxPage类
		$community = M('homemaking_certificate','',DB_CONFIG3);
		$count = $community->count(); //计算记录数
		$limitRows = 4; // 设置每页记录数
		
		$p = new AjaxPage($count, $limitRows,"page"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		
		$data = $community->order('fldCreateDate desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		//echo $community->getLastSql();
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display();
	 }
	
	/**
	  * trainSubmit
	  * 提交培训信息
	  * @access public
	  * @return void
	  * @date 2015-05-22
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function trainSubmit(){
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		
		$homemakingTrainInformation = M('homemaking_train_information');
		$trainPrice = $homemakingTrainInformation->field('fldId,fldName,fldOrderFees')->where(array('fldStatus'=>1))->select();
		if(IS_POST){
			//是否已注册
			if(!$uid){
				$user_name = I('post.user');
				$user_location = I('post.place-code');
				$user_address = I('post.place').I('post.placeDetail');
				$user_phone = I('post.phone');
				$openid = session('openid');
				$userinfo = userinfo($openid);
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$phone = I('post.phone');
				//$password = I('post.passwd');
				//$password = $this->_randString(16,'ZT');
				$password = 'zt1234';
				if($openid){
					$user['open_id'] = $openid;
					$user['user_account'] = $phone;
					$user['user_password'] = md5($phone.':'.$password);
					$user['ip'] = get_client_ip();
					$user['open_name'] = $username;
					$user['type'] ='weixin';
					$user['role_type'] = '0';
					$user['account_from'] = '3001';
					if(!empty($user_name)){
						$user['user_name'] = $user_name;
					}
					if(!empty($user_location)){
						$user['location'] = $user_location;
					}
					if(!empty($user_address)){
						$user['address'] = $user_address;
					}
					if(!empty($user_phone)){
						$user['mobile'] = $user_phone;
					}
					$data = urlencode(json_encode($user));
					$url = APP_DOMIN.'Chitone-Account-reg';
					$res = _get($url,$data);
					$code = $res['result']['code'];
					cookie('per',$code,86400);
					$uid = $res['result']['account_id'];
					session('uid',$uid);
					$this->getphoto($uid,$headimgurl);
					if($uid){
						//$this->sendWeixinTemplateMessage($uid,$phone,$password);
						$result = $this->sendWeixinTemplateMessage('changePassword','您已成功注册'.WECHAT_NAME.'账户',$phone,$password,'','温馨提醒：请妥善保管您的账号信息，为防止密码泄露，建议您立即修改密码。点此修改用户名密码',$uid);
					}
				}else{
					//echo '请在微信上打开，或者重新进入页面';
					$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-trainSubmit','',''));
					die();
				}
			}
			
			$homeTrainOrder = D('HomeTrainOrder');
			$homeTrainOrderData = array();
			$homeCustomerData['fldName'] = I('post.user');
			//$homeTrainOrderData['fldName'] = 'test';
			foreach($trainPrice as $value){
				if($value['fldId'] == I('post.course')){
					$fldMajorName = $value['fldName'];
					$fldOrderFees = $value['fldOrderFees'];
				}
			}
			$homeTrainOrderData['fldMobile'] = I('post.phone');
			$homeTrainOrderData['fldsex'] = I('post.sex')==1?'女':'男';
			$homeTrainOrderData['fldAddress'] = I('post.place').I('post.placeDetail');
			$homeTrainOrderData['fldBirthDay'] = I('post.dueDate');
			//$homeTrainOrderData['fldDegree'] = I('post.education');
			$homeTrainOrderData['fldPhrase'] = substr($fldMajorName,0,6);
			$homeTrainOrderData['fldMajorNo'] = I('post.course');
			$homeTrainOrderData['fldMajorName'] = $fldMajorName;
			$homeTrainOrderData['fldOrderFees'] = $fldOrderFees;
			$homeTrainOrderData['fldPerUserId'] = $uid;
			$homeTrainOrderData['fldWeixiCode'] = session('openid');
			$homeTrainOrderData['fldMark'] = '微信';
			$homeTrainOrderData['lasteditby'] = 'admin';
			$homeTrainOrderData['lasteditdt'] = date('Y-m-d H:i:s',time());
			$homeTrainOrderData['fldcreateDate'] = date('Y-m-d H:i:s',time());
			$addResult = $homeTrainOrder->add($homeTrainOrderData);
			
			$redirect = I('post.redirect');
			$redirect = urlencode($redirect);
			//redirect('Homemaking-mamabang?redirect=' . $redirect, 0, '页面跳转中...');
			if($addResult){
				 if($redirect){
					 redirect($redirect);
				 }else{
					 $this->display('trainSubmitSuccess');
				 }
			 }else{
				 $this->error('新增失败',U('Homemaking-trainSubmit'));
			 }
		}
		else{
			if (session('time')){
				$time = session('time');
				$newtime = time();
				$losetime = $newtime - $time;
				$losetime = 120 - $losetime;
				if ($losetime > 1 && $losetime < 120) {
					$this->assign('time', $losetime);
				}
			}
			//$uid = 115;
			//dump($uid);
			if($uid){
				$homeCustomer = D('HomeCustomer');
				$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
				if($homeCustomerResult){
					$this->assign('info',$homeCustomerResult);
					$this->assign('uid',$uid);
				}else{
					$homeInfo = D('HomeInfo');
					$homeInfoResult = $homeInfo->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
					if($homeInfoResult){
						$this->assign('info',$homeInfoResult);
						$this->assign('uid',$uid);
					}else{
						$homeTrainOrder = D('HomeTrainOrder');
						$homeTrainOrderResult = $homeTrainOrder->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
						if($homeInfoResult){
							$this->assign('info',$homeTrainOrderResult);
							$this->assign('uid',$uid);
						}
					}
				}
			}
			
			$this->assign('trainPrice',$trainPrice);
			$this->display('trainSubmit');
		}
	}
	
	/**
	  * competeSitter
	  * 提交竞聘信息
	  * @access public
	  * @return void
	  * @date 2015-05-25
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function competeSitter(){
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		
		$homemakingCertificate = M('homemaking_certificate');
		$certificate1 = $homemakingCertificate->field('fldId,fldName,fldType')->where("fldStatus = 1 and fldType = 1 and fldName not like '%餐%'")->select();
		$certificate2 = $homemakingCertificate->field('fldId,fldName,fldType')->where("fldStatus = 1 and fldType = 2 and fldName not like '%餐%'")->select();
		if(IS_POST){
			//是否已注册
			if(!$uid){
				$user_name = I('post.user');
				$user_location = I('post.place-code');
				$user_address = I('post.place').I('post.placeDetail');
				$user_phone = I('post.phone');
				$openid = session('openid');
				$userinfo = userinfo($openid);
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$phone = I('post.phone');
				//$password = I('post.passwd');
				//$password = $this->_randString(16,'ZT');
				$password = 'zt1234';
				if($openid){
					$user['open_id'] = $openid;
					$user['user_account'] = $phone;
					$user['user_password'] = md5($phone.':'.$password);
					$user['ip'] = get_client_ip();
					$user['open_name'] = $username;
					$user['type'] ='weixin';
					$user['role_type'] = '0';
					$user['account_from'] = '3001';
					if(!empty($user_name)){
						$user['user_name'] = $user_name;
					}
					if(!empty($user_location)){
						$user['location'] = $user_location;
					}
					if(!empty($user_address)){
						$user['address'] = $user_address;
					}
					if(!empty($user_phone)){
						$user['mobile'] = $user_phone;
					}
					$data = urlencode(json_encode($user));
					$url = APP_DOMIN.'Chitone-Account-reg';
					$res = _get($url,$data);
					$code = $res['result']['code'];
					cookie('per',$code,86400);
					$uid = $res['result']['account_id'];
					session('uid',$uid);
					$this->getphoto($uid,$headimgurl);
					if($uid){
						//$this->sendWeixinTemplateMessage($uid,$phone,$password);
						$result = $this->sendWeixinTemplateMessage('changePassword','您已成功注册'.WECHAT_NAME.'账户',$phone,$password,'','温馨提醒：请妥善保管您的账号信息，为防止密码泄露，建议您立即修改密码。点此修改用户名密码',$uid);
					}
				}else{
					//echo '请在微信上打开，或者重新进入页面';
					$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-competeSitter','',''));
					die();
				}
			}
			$homeInfo = D('HomeInfo');
			$homeInfoId = $homeInfo->field('fldId')->where(array('fldWeixinCode'=>session('openid')))->find();
			$homeInfoId = $homeInfoId['fldId'];
			if(!$homeInfoId){
				$education = array('0'=>'无','1'=>'小学','2'=>'初中','3'=>'高中','4'=>'中专','5'=>'大专','6'=>'本科及以上',);
				$homeInfoData = array();
				$homeInfoData['fldName'] = I('post.user');
				//$homeInfoData['fldName'] = 'test';
				$homeInfoData['fldMobile'] = I('post.phone');
				$homeInfoData['fldSex'] = I('post.sex')-1;
				$homeInfoData['fldHomeAddress'] = I('post.place').I('post.placeDetail');
				$homeInfoData['fldBirthDay'] = I('post.dueDate');
				$homeInfoData['fldEducation'] = $education[I('post.education')];
				$homeInfoData['fldPerUserId'] = $uid;
				$homeInfoData['fldWeixinCode'] = session('openid');
				$homeInfoData['lasteditby'] = 'admin';
				$homeInfoData['lasteditdt'] = date('Y-m-d H:i:s',time());
				$homeInfoData['fldCreater'] = 'admin';
				$homeInfoData['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homeInfoId = $homeInfo->add($homeInfoData);
			}
			if($homeInfoId){
				$homemakingSitter = M('HomemakingSitter');
				//$homemakingSitterId = $homemakingSitter->field('fldId')->where(array('fldHomeId'=>$homeInfoId,'fldType'=>I('post.type')))->find();
				$homemakingSitterId = $homemakingSitter->field('fldId')->where(array('fldHomeId'=>$homeInfoId))->find();
				$homemakingSitterId = $homemakingSitterId['fldId'];
				if(!$homemakingSitterId){
					$homemakingSitterData = array();
					$homemakingSitterData['fldPersonId'] = $homeInfoId;
					$homemakingSitterData['fldType'] = I('post.type');
					$homemakingSitterData['fldStatus'] = 0;
					$homemakingSitterData['fldSelfComment'] = '';
					$homemakingSitterData['fldOwner'] = 'admin';
					$homemakingSitterData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$homemakingSitterData['lastEditby'] = 'admin';
					$homemakingSitterData['lastEditdt'] = date('Y-m-d H:i:s',time());
					$addResult = $homemakingSitter->add($homemakingSitterData);
				}
				$homemakingPersonCertificate = M('HomemakingPersonCertificate');
				$homemakingPersonCertificateData = array();
				foreach(I('post.certificate1') as $key=>$value){
					$homemakingPersonCertificateId = $homemakingPersonCertificate->field('fldId')->where(array('fldHomeId'=>$homeInfoId,'fldCertificateId'=>$value))->find();
					$homemakingPersonCertificateId = $homemakingPersonCertificateId['fldId'];
					if(!$homemakingPersonCertificateId){
						$homemakingPersonCertificateData[$key]['fldHomeId'] = $homeInfoId;
						$homemakingPersonCertificateData[$key]['fldCertificateId'] = $value;
						$homemakingPersonCertificateData[$key]['lastEditby'] = 'admin';
						$homemakingPersonCertificateData[$key]['lastEditdt'] = date('Y-m-d H:i:s',time());
						$homemakingPersonCertificateData[$key]['fldOwner'] = 'admin';
						$homemakingPersonCertificateData[$key]['fldCreateDate'] = date('Y-m-d H:i:s',time());
						$addResult = $addResult && $homemakingPersonCertificate->add($homemakingPersonCertificateData[$key]);
					}
				}
			}
			$redirect = I('post.redirect');
			$redirect = urlencode($redirect);
			//redirect('Homemaking-mamabang?redirect=' . $redirect, 0, '页面跳转中...');
			if($addResult){
				 if($redirect){
					 redirect($redirect);
				 }else{
					 $this->display('trainSubmitSuccess');
				 }
			 }else{
				 $this->error('新增失败',U('Homemaking-competeSitter'));
			 }
		}
		else{
			if (session('time')){
				$time = session('time');
				$newtime = time();
				$losetime = $newtime - $time;
				$losetime = 120 - $losetime;
				if ($losetime > 1 && $losetime < 120) {
					$this->assign('time', $losetime);
				}
			}
			//$uid = 116;
			if($uid){
				$homeCustomer = D('HomeCustomer');
				$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
				if($homeCustomerResult){
					$this->assign('info',$homeCustomerResult);
					$this->assign('uid',$uid);
				}else{
					$homeInfo = D('HomeInfo');
					$homeInfoResult = $homeInfo->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
					if($homeInfoResult){
						$this->assign('info',$homeInfoResult);
						$this->assign('uid',$uid);
					}else{
						$homeTrainOrder = D('HomeTrainOrder');
						$homeTrainOrderResult = $homeTrainOrder->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
						if($homeInfoResult){
							$this->assign('info',$homeTrainOrderResult);
							$this->assign('uid',$uid);
						}
					}
				}
			}
			
			$this->assign('certificate1',$certificate1);
			$this->assign('certificate2',$certificate2);
			$this->display();
		}
	}
	
	
	
	/**
	  * requirementSubmit
	  * 提交我要预约
	  * @access public
	  * @return void
	  * @date 2015-05-21
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirementSubmit2(){
		header("content-type:text/html;charset='utf-8'");
		//session('uid',117);
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		$homemakingSetting = M('homemaking_setting');
		//获取学历映射
		if (S('degreeMap')) {
			$degreeMap = S('degreeMap');
			if (S('degreeString')) {
				$degreeString = S('degreeString');
			}else {
				foreach ($degreeMap as $key => $value) {
					$degreeString .= $value.',';
				}
				$degreeString = substr($degreeString,0,-1);
				S('degreeString',$degreeString,3600);
			}
		}else {
			$degree = $homemakingSetting->field('fldValue')->where(array('fldName'=>'degree'))->find();
			$degreeMap = json_decode($degree['fldValue']);
			S('degreeMap',$degreeMap,3600);
			if (S('degreeString')) {
				$degreeString = S('degreeString');
			}else {
				foreach ($degreeMap as $key => $value) {
					$degreeString .= $value.',';
				}
				$degreeString = substr($degreeString,0,-1);
				S('degreeString',$degreeString,3600);
			}
		}
		
		//获取年龄映射
		if (S('ageMap')) {
			$ageMap = S('ageMap');
		}else {
			$age = $homemakingSetting->field('fldValue')->where(array('fldName'=>'age'))->find();
			$ageMap = json_decode($age['fldValue']);
			S('ageMap',$ageMap,3600);
		}
		if (S('ageString')) {
			$ageString = S('ageString');
		}else {
			foreach ($ageMap as $key => $value) {
				$ageString .= $value.',';
			}
			$ageString = substr($ageString,0,-1);
			S('ageString',$ageString,3600);
		}
		
		//获取语言映射
		if (S('languageMap')) {
			$languageMap = S('languageMap');
			if (S('languageString')) {
				$languageString = S('languageString');
			}else {
				foreach ($languageMap as $key => $value) {
					if (strstr($value,"*")) {
						$value = str_replace('*',' ',str_replace('国语','普通话',$value));
						$languageMap[$key] = $value;
					}
					$languageString .= $value.',';
				}
				$languageString = substr($languageString,0,-1);
				S('languageString',$languageString,3600);
			}
		}else {
			$language = $homemakingSetting->field('fldValue')->where(array('fldName'=>'language'))->find();
			$languageMap = json_decode($language['fldValue']);
			S('languageMap',$languageMap,3600);
			if (S('languageString')) {
				$languageString = S('languageString');
			}else {
				foreach ($languageMap as $key => $value) {
					if (strstr($value,"*")) {
						$value = str_replace('*',' ',str_replace('国语','普通话',$value));
						$languageMap[$key] = $value;
					}
					$languageString .= $value.',';
				}
				$languageString = substr($languageString,0,-1);
				S('languageString',$languageString,3600);
			}
		}
		
		//获取婚育状况映射
		if (S('marryMap')) {
			$marryMap = S('marryMap');
			if (S('marryString')) {
				$marryString = S('marryString');
			}else {
				foreach ($marryMap as $key => $value) {
					$marryString .= $value.',';
				}
				$marryString = substr($marryString,0,-1);
				S('marryString',$marryString,3600);
			}
		}else {
			$marry = $homemakingSetting->field('fldValue')->where(array('fldName'=>'marry'))->find();
			$marryMap = json_decode($marry['fldValue']);
			S('marryMap',$marryMap,3600);
			if (S('marryString')) {
				$marryString = S('marryString');
			}else {
				foreach ($marryMap as $key => $value) {
					$marryString .= $value.',';
				}
				$marryString = substr($marryString,0,-1);
				S('marryString',$marryString,3600);
			}
		}
		
		if(IS_POST){
			//获取籍贯编号映射
			/*$palNative = D('palNative');
			$result = $palNative->select();
			$palNativeMap = array();
			$palNativeMap['不限'] = '0';
			foreach($result as $value){
				$palNativeMap[$value['fldName']] = $value['fldNo'];
			}*/
			//是否在微信上进入
			if(!!session('openid')){
				$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
			}
			//是否已注册
			if(!$uid){//没有注册，则自动注册
				session('requirement',I('post.'));
				$user_name = I('post.user');
				$user_location = I('post.place-code');
				$user_address = I('post.place').I('post.placeDetail');
				$user_phone = I('post.phone');
				$openid = session('openid');
				$userinfo = userinfo($openid);
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$phone = I('post.phone');
				//$password = I('post.passwd');
				//$password = $this->_randString(16,'ZT');
				$password = 'zt1234';
				if($openid){
					$user['open_id'] = $openid;
					//$user['user_account'] = $phone;
					//$user['user_password'] = md5($phone.':'.$password);
					if (!empty($phone)){
						$user['user_account'] = $phone;
					}
					if (!empty($password)){
						$user['user_password'] = $password;
					}
					$user['ip'] = get_client_ip();
					$user['open_name'] = $username;
					$user['type'] ='weixin';
					$user['role_type'] = '0';
					$user['account_from'] = '3001';
					if(!empty($user_name)){
						$user['user_name'] = $user_name;
					}
					if(!empty($user_location)){
						$attdate['attach']['location'] = $user_location;
					}
					if(!empty($user_address)){
						$attdate['attach']['address'] = $user_address;
					}
					if(!empty($user_phone)){
						$attdate['attach']['mobile'] = $user_phone;
					}
					$data = urlencode(json_encode($user));
					$url = APP_DOMIN.'Chitone-Account-reg';
					$res = _get($url,$data);
					if (empty($res) || $res['status'] != 0) {
						if ($res['status'] == 200000) {
							redirect('Account-login?redirect=Homemaking-requirementSubmit-v'.time().'-type-'.I('param.type'));
						}
						
					}
					$code = $res['result']['code'];
					cookie('per',$code,86400);
					$uid = $res['result']['account_id'];
					session('uid',$uid);
					//获取微信头像
					//$this->getphoto($uid,$headimgurl);
					$redis = new Redis();
					$redis->connect('192.168.2.183', '6379');
					$redis->auth('job5156RedisMaster183');
					$redisresult = $redis->HGetAll($code);
					$per_user_id = $redisresult['id'];
					session('per_user_id',$per_user_id);
					$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
					$attdate['per_user_id'] = $per_user_id;
					//$userlocation = S($openid);
					//$userlocation = json_decode($userlocation, true);
					//$place = $this->_planeCrood($userlocation);
					//session('place', $place);
					//$attdate['attach']['user_x'] = $place['x'];
					//$attdate['attach']['user_y'] = $place['y'];
					$attdate['attach']['user_x'] = 0;
					$attdate['attach']['user_y'] = 0;
					$attdate['attach']['head_img_url'] = $headimgurl;
					$attres = urlencode(json_encode($attdate));
					$attresult = _get($atturl,$attres);
					//注册成功推送绑定成功信息
					if($uid){
						$result = $this->sendWeixinTemplateMessage('changePassword','您已成功注册'.WECHAT_NAME.'账户',$phone,$password,'','温馨提醒：请妥善保管您的账号信息，为防止密码泄露，建议您立即修改密码。点此修改用户名密码',$uid);
					}
					//die();
				}else{
					$this->error('请在微信上打开，或者重新进入页面',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
				}
			}else{
				$userinfo = userinfo(session('openid'));
				$username = $userinfo['nickname'];
				$headimgurl = $userinfo['headimgurl'];
				$code = cookie('per');
				$redis = new Redis();
				$redis->connect('192.168.2.183', '6379');
				$redis->auth('job5156RedisMaster183');
				$redisresult = $redis->HGetAll($code);
				$per_user_id = $redisresult['id'];
				session('per_user_id',$per_user_id);
				$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
				$attdate['per_user_id'] = $per_user_id;
				$attdate['attach']['head_img_url'] = $headimgurl;
				$attdate['attach']['user_x'] = 0;
				$attdate['attach']['user_y'] = 0;
				if((I('post.place-code')&&I('post.place-code')!='1401040')||(I('post.place')=='广东东莞万江区')&&I('post.place-code')=='14010400' ){
					$attdate['attach']['location'] = I('post.place-code');
				}
				if(I('post.place').I('post.placeDetail')){
					$attdate['attach']['address'] = I('post.place').I('post.placeDetail');
				}
				$attres = urlencode(json_encode($attdate));
				$attresult = _get($atturl,$attres);
				//die();
			}
			if($uid){
				$type = I('post.type');
				$homeCustomer = D('HomeCustomer');
				//$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
				$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldPerUserId'=>$uid))->find();
				$homeCustomerId = $homeCustomerId['fldId'];
				if(!$homeCustomerId){
					$homeCustomerData = array();
					$homeCustomerData['fldName'] = I('post.user');
					$homeCustomerData['fldMobile'] = I('post.phone');
					$homeCustomerData['fldAddress'] = I('post.place').I('post.placeDetail');
					if($type==1){
						$homeCustomerData['fldchildbirth'] = I('post.dueDate');
					}elseif($type==2){
						$homeCustomerData['fldBadyBrithday'] = I('post.dueDate');
					}
					$homeCustomerData['fldPerUserId'] = $uid;
					$homeCustomerData['fldWeixinCode'] = session('openid');
					$homeCustomerData['fldInfoFrom'] = '微信：'.WECHAT_NAME;
					$homeCustomerData['lasteditby'] = 'admin';
					$homeCustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
					$homeCustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$homeCustomerId = $homeCustomer->add($homeCustomerData);
					//$homeCustomerId = $homeCustomer->data($homeCustomerData)->add();
					//echo $homeCustomer->getDbError();
				}
				if($homeCustomerId){
					$homemakingRequirementData['fldPersonId'] = $homeCustomerId;
					$homemakingRequirementData['fldType'] = $type;
					$fldPriceId = M('homemaking_service_price_detail')->field('fldId')->where(array('fldPrice'=>substr(I('post.price'),0,-3)))->find();
					$homemakingRequirementData['fldPriceId'] = $fldPriceId['fldId'];
					//$homemakingRequirementData['fldNative'] = $palNativeMap[I('post.native')];
					$homemakingRequirementData['fldNative'] = I('post.native','不限')==''?'不限':I('post.native','不限');
					$homemakingRequirementData['fldAge'] = I('post.age','不限')==''?0:array_search(I('post.age','不限'),$ageMap);
					//$homemakingRequirementData['fldDegree'] = I('post.education');
					$homemakingRequirementData['fldDegree'] = 0;
					$homemakingRequirementData['fldMarryStatus'] = I('post.marry','不限')==''?0:array_search(I('post.marry','不限'),$marryMap);
					$homemakingRequirementData['fldLanguage'] = I('post.language','不限')==''?0:array_search(I('post.language','不限'),$languageMap);
					$homemakingRequirementData['fldRemark'] = I('post.special');
					$homemakingRequirementData['fldStatus'] = 0;
					$homemakingRequirementData['fldOwner'] = 'admin';
					$homemakingRequirementData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$homemakingRequirementData['lastEditby'] = 'admin';
					$homemakingRequirementData['lastEditdt'] = date('Y-m-d H:i:s',time());
					$homemakingRequirement = M('homemaking_requirement');
					$homemakingRequirementId = $homemakingRequirement->add($homemakingRequirementData);
					//echo $homemakingRequirement->getDbError();
					if($homemakingRequirementId){
						$homeMycustomer = D('HomeMycustomer');
						$homeMycustomerData = array();
						$homeMycustomerData['fldCustomerID'] = $homeCustomerId;
						$homeMycustomerData['fldRequireId'] = $homemakingRequirementId;
						$homeMycustomerData['fldWorkAddress'] = I('post.place').I('post.placeDetail');
						$homeMycustomerData['fldWorkProject'] = $type == 1?'月嫂':'育婴嫂';
						$homeMycustomerData['fldAmount'] = substr(I('post.price'),0,-3);
						$homeMycustomerData['fldserviceFee'] = $type == 1?1700:1500;
						$homeMycustomerData['fldcommision'] = $type == 1?1700:1500;
						$homeMycustomerData['fldInsurance'] = 1;
						$homeMycustomerData['fldInsuranceFee'] = $type == 1?50:150;
						$homeMycustomerData['fldCustomerName'] = I('post.user');
						//$homeMycustomerData['fldMobile'] = I('post.phone');
						$homeMycustomerData['fldserverState'] = 0;
						$homeMycustomerData['fldreserveTime'] = I('post.dueDate');
						$homeMycustomerData['fldOrderFrom'] = '微信';
						$homeMycustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
						$homeMycustomerData['lasteditby'] = 'admin';
						$homeMycustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
						$addResult = $homeMycustomer->add($homeMycustomerData);
						//echo $homeMycustomer->getDbError();
					}
				}
				if($addResult){
					 $result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请等待工作人员为您安排时间','0769-87073668',session('openname'),'请保持电话畅通，网站工作人员会尽快为您提供服务。',0);
					 //$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccess');
					 if ($type == 1) {
					 	$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuesao');
					 }else {
					 	$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuyingsao');
					 }
					 //redirect(U('Homemaking-requirementSubmit',array('type'=>'1')));
					 //redirect(U('Homemaking-requirementSubmitSuccess'));
					 //$this->display('requirementSubmitSuccess');
				 }else{
					 //header("content-type:text/html;charset='utf-8'");
					 //redirect(U('Homemaking-requirementSubmit'),'','','提交失败');
					 //redirect(U('Homemaking-requirementSubmit','',''),1,'提交失败');
					 $this->error('新增失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>I('param.type')),''));
				 }
			}else {
				$this->error('非法操作');
			}
		}else{
			if (session('time')){
				$time = session('time');
				$newtime = time();
				$losetime = $newtime - $time;
				$losetime = 120 - $losetime;
				if ($losetime > 1 && $losetime < 120) {
					$this->assign('time', $losetime);
				}
			}
			
			$homemakingServicePrice = D('HomemakingServicePrice');
			$parentIds = $homemakingServicePrice->field('fldId,fldType,fldName')->select();
			$homemakingServicePriceDetail = D('HomemakingServicePriceDetail');
			foreach($parentIds as $key=>$value){
				$result[$value['fldType']] = $homemakingServicePriceDetail->field('fldId,fldParentId,fldGrade,fldPrice')->relation(true)->where(array('fldParentId'=>$value['fldId'],'fldStatus'=>1))->select();
			}
			
			$resultJson = array();
			$price = array();
			foreach($result as $k=>$v){
				foreach($v as $kk=>$vv){
					$price[$k] .= ','.$vv['fldPrice'].'元';
					foreach($vv as $kkk=>$vvv){
						foreach($vvv as $kkkk=>$vvvv){
							switch($vvvv['fldName']){
								case '身份证':$style = 1;break;
								case '健康证':$style = 2;break;
								case '月嫂证':$style = 3;break;
								case '月子餐':$style = 4;break;
								case '育婴师证':$style = 5;break;
								case '催乳师证':$style = 6;break;
							}
							$resultJson[$k][$vv['fldPrice']][$vvvv['fldName']] = $style;
						}
					}
				}
				$price[$k] = substr($price[$k],1);
			}
			//$uid = 115;
			if($uid || session('openid')){
				$homeCustomer = D('HomeCustomer');
				//$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
				$homeCustomerResult = $homeCustomer->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
				//echo $homeCustomer->getLastSql();
				if($homeCustomerResult){
					$this->assign('info',$homeCustomerResult);
					$this->assign('uid',$uid);
				}else{
					$homeInfo = D('HomeInfo');
					$homeInfoResult = $homeInfo->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
					if($homeInfoResult){
						$this->assign('info',$homeInfoResult);
						$this->assign('uid',$uid);
					}else{
						$homeTrainOrder = D('HomeTrainOrder');
						$homeTrainOrderResult = $homeTrainOrder->field('fldId,fldName,fldMobile')->where(array('fldPerUserId'=>$uid))->find();
						if($homeInfoResult){
							$this->assign('info',$homeTrainOrderResult);
							$this->assign('uid',$uid);
						}
					}
				}
			}
			import('ORG.Util.Jssdk');
			$jssdk = new JSSDK(APPID, APPSECRET);
			$signPackage = $jssdk->GetSignPackage();
			$this->assign('signPackage',$signPackage);
			$this->assign('type',I('get.type'));
			$this->assign('price',$price);
			$this->assign('priceCertificate',json_encode($resultJson));
			$this->assign('degreeString',degreeString);
			$this->assign('ageString',ageString);
			$this->assign('languageString',languageString);
			$this->assign('marryString',marryString);
			$this->display();
		}
	}
	
	
	/**
	  * requirementSubmit4Login
	  * 登录绑定后提交我要预约
	  * @access public
	  * @return void
	  * @date 2015-06-16
	  * @author RohoChan<[email]rohochan@gmail.com[/email]>
	  **/
	public function requirementSubmit4Login2(){
		header("content-type:text/html;charset='utf-8'");
		
		$homemakingSetting = M('homemaking_setting');
		//获取学历映射
		if (S('degreeMap')) {
			$degreeMap = S('degreeMap');
			if (S('degreeString')) {
				$degreeString = S('degreeString');
			}else {
				foreach ($degreeMap as $key => $value) {
					$degreeString .= $value.',';
				}
				$degreeString = substr($degreeString,0,-1);
				S('degreeString',$degreeString,3600);
			}
		}else {
			$degree = $homemakingSetting->field('fldValue')->where(array('fldName'=>'degree'))->find();
			$degreeMap = json_decode($degree['fldValue']);
			S('degreeMap',$degreeMap,3600);
			if (S('degreeString')) {
				$degreeString = S('degreeString');
			}else {
				foreach ($degreeMap as $key => $value) {
					$degreeString .= $value.',';
				}
				$degreeString = substr($degreeString,0,-1);
				S('degreeString',$degreeString,3600);
			}
		}
		
		//获取年龄映射
		if (S('ageMap')) {
			$ageMap = S('ageMap');
		}else {
			$age = $homemakingSetting->field('fldValue')->where(array('fldName'=>'age'))->find();
			$ageMap = json_decode($age['fldValue']);
			S('ageMap',$ageMap,3600);
		}
		
		if (S('ageString')) {
			$ageString = S('ageString');
		}else {
			foreach ($ageMap as $key => $value) {
				$ageString .= $value.',';
			}
			$ageString = substr($ageString,0,-1);
			S('ageString',$ageString,3600);
		}
		
		//获取语言映射
		if (S('languageMap')) {
			$languageMap = S('languageMap');
			if (S('languageString')) {
				$languageString = S('languageString');
			}else {
				foreach ($languageMap as $key => $value) {
					if (strstr($value,"*")) {
						$value = str_replace('*',' ',str_replace('国语','普通话',$value));
						$languageMap[$key] = $value;
					}
					$languageString .= $value.',';
				}
				$languageString = substr($languageString,0,-1);
				S('languageString',$languageString,3600);
			}
		}else {
			$language = $homemakingSetting->field('fldValue')->where(array('fldName'=>'language'))->find();
			$languageMap = json_decode($language['fldValue']);
			S('languageMap',$languageMap,3600);
			if (S('languageString')) {
				$languageString = S('languageString');
			}else {
				foreach ($languageMap as $key => $value) {
					if (strstr($value,"*")) {
						$value = str_replace('*',' ',str_replace('国语','普通话',$value));
						$languageMap[$key] = $value;
					}
					$languageString .= $value.',';
				}
				$languageString = substr($languageString,0,-1);
				S('languageString',$languageString,3600);
			}
		}
		//获取婚育状况映射
		if (S('marryMap')) {
			$marryMap = S('marryMap');
			if (S('marryString')) {
				$marryString = S('marryString');
			}else {
				foreach ($marryMap as $key => $value) {
					$marryString .= $value.',';
				}
				$marryString = substr($marryString,0,-1);
				S('marryString',$marryString,3600);
			}
		}else {
			$marry = $homemakingSetting->field('fldValue')->where(array('fldName'=>'marry'))->find();
			$marryMap = json_decode($marry['fldValue']);
			S('marryMap',$marryMap,3600);
			if (S('marryString')) {
				$marryString = S('marryString');
			}else {
				foreach ($marryMap as $key => $value) {
					$marryString .= $value.',';
				}
				$marryString = substr($marryString,0,-1);
				S('marryString',$marryString,3600);
			}
		}
		
		if (session('uid')) {
			$uid = session('uid');
		}else {
			$account_info = D('AccountInfo');
			$uid = $account_info->logining();
		}
		if($uid && session('openid')){
			$post = session('requirement');
			$userinfo = userinfo(session('openid'));
			$username = $userinfo['nickname'];
			$headimgurl = $userinfo['headimgurl'];
			$code = cookie('per');
			$redis = new Redis();
			$redis->connect('192.168.2.183', '6379');
			$redis->auth('job5156RedisMaster183');
			$redisresult = $redis->HGetAll($code);
			$per_user_id = $redisresult['id'];
			session('per_user_id',$per_user_id);
			$atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
			$attdate['per_user_id'] = $per_user_id;
			$attdate['attach']['head_img_url'] = $headimgurl;
			$attdate['attach']['user_x'] = 0;
			$attdate['attach']['user_y'] = 0;
			if(($post['place-code']&&$post['place-code']!='1401040')||($post['place']=='广东东莞万江区')&&$post['place-code']=='14010400' ){
				$attdate['attach']['location'] = $post['place-code'];
			}
			if($post['place'].$post['placeDetail']){
				$attdate['attach']['address'] = $post['place'].$post['placeDetail'];
			}
			$attres = urlencode(json_encode($attdate));
			$attresult = _get($atturl,$attres);
			//die();
			
			$type = $post['type'];
			$homeCustomer = D('HomeCustomer');
			//$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldWeiXinCode'=>session('openid'),'fldPerUserId'=>$uid,'_logic'=>'OR'))->find();
			$homeCustomerId = $homeCustomer->field('fldId')->where(array('fldPerUserId'=>$uid))->find();
			$homeCustomerId = $homeCustomerId['fldId'];
			if(!$homeCustomerId){
				$homeCustomerData = array();
				$homeCustomerData['fldName'] = $post['user'];
				$homeCustomerData['fldMobile'] = $post['phone'];
				$homeCustomerData['fldAddress'] = $post['place'].$post['placeDetail'];
				if($type==1){
					$homeCustomerData['fldchildbirth'] = $post['dueDate'];
				}elseif($type==2){
					$homeCustomerData['fldBadyBrithday'] = $post['dueDate'];
				}
				$homeCustomerData['fldPerUserId'] = $uid;
				$homeCustomerData['fldWeixinCode'] = session('openid');
				$homeCustomerData['fldInfoFrom'] = '微信：'.WECHAT_NAME;
				$homeCustomerData['lasteditby'] = 'admin';
				$homeCustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
				$homeCustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homeCustomerId = $homeCustomer->add($homeCustomerData);
				//$homeCustomerId = $homeCustomer->data($homeCustomerData)->add();
				//echo $homeCustomer->getDbError();
			}
			if($homeCustomerId){
				$homemakingRequirementData['fldPersonId'] = $homeCustomerId;
				$homemakingRequirementData['fldType'] = $type;
				$fldPriceId = M('homemaking_service_price_detail')->field('fldId')->where(array('fldPrice'=>substr(I('post.price'),0,-3)))->find();
				$homemakingRequirementData['fldPriceId'] = $fldPriceId['fldId'];
				//$homemakingRequirementData['fldNative'] = $palNativeMap[I('post.native')];
				$homemakingRequirementData['fldNative'] = I('post.native','不限')==''?'不限':I('post.native','不限');
				$homemakingRequirementData['fldAge'] = I('post.age','不限')==''?0:array_search(I('post.age','不限'),$ageMap);
				//$homemakingRequirementData['fldDegree'] = I('post.education');
				$homemakingRequirementData['fldDegree'] = 0;
				$homemakingRequirementData['fldMarryStatus'] = I('post.marry','不限')==''?0:array_search(I('post.marry','不限'),$marryMap);
				$homemakingRequirementData['fldLanguage'] = I('post.language','不限')==''?0:array_search(I('post.language','不限'),$languageMap);
				$homemakingRequirementData['fldRemark'] = I('post.special');
				$homemakingRequirementData['fldStatus'] = 0;
				$homemakingRequirementData['fldOwner'] = 'admin';
				$homemakingRequirementData['fldCreateDate'] = date('Y-m-d H:i:s',time());
				$homemakingRequirementData['lastEditby'] = 'admin';
				$homemakingRequirementData['lastEditdt'] = date('Y-m-d H:i:s',time());
				$homemakingRequirement = M('homemaking_requirement');
				$homemakingRequirementId = $homemakingRequirement->add($homemakingRequirementData);
				//echo $homemakingRequirement->getDbError();
				if($homemakingRequirementId){
					$homeMycustomer = D('HomeMycustomer');
					$homeMycustomerData = array();
					$homeMycustomerData['fldCustomerID'] = $homeCustomerId;
					$homeMycustomerData['fldRequireId'] = $homemakingRequirementId;
					$homeMycustomerData['fldWorkAddress'] = $post['place'].$post['placeDetail'];
					$homeMycustomerData['fldWorkProject'] = $type == 1?'月嫂':'育婴嫂';
					$homeMycustomerData['fldAmount'] = substr($post['price'],0,-3);
					$homeMycustomerData['fldserviceFee'] = $type == 1?1700:1500;
					$homeMycustomerData['fldcommision'] = $type == 1?1700:1500;
					$homeMycustomerData['fldInsurance'] = 1;
					$homeMycustomerData['fldInsuranceFee'] = $type == 1?50:150;
					$homeMycustomerData['fldCustomerName'] = $post['user'];
					//$homeMycustomerData['fldMobile'] = I('post.phone');
					$homeMycustomerData['fldserverState'] = 0;
					$homeMycustomerData['fldreserveTime'] = $post['dueDate'];
					$homeMycustomerData['fldOrderFrom'] = '微信';
					$homeMycustomerData['lasteditdt'] = date('Y-m-d H:i:s',time());
					$homeMycustomerData['lasteditby'] = 'admin';
					$homeMycustomerData['fldCreateDate'] = date('Y-m-d H:i:s',time());
					$addResult = $homeMycustomer->add($homeMycustomerData);
					//echo $homeMycustomer->getDbError();
				}
			}
			if($addResult){
				 session('requirement',null);
				 //$result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请等待工作人员为您安排时间','0769-87073668',session('openname'),'请保持电话畅通，网站工作人员会尽快为您提供服务。',0);
				 $result = $this->sendWeixinTemplateMessage('reservationSuccess','您已预约成功!','请保持电话畅通，您的专属顾问会尽快为您提供服务。','0769-87073668',session('openname'),'',0);
				 //$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccess');
				 if ($type == 1) {
				 	$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuesao');
				 }else {
				 	$this->wxpay($homemakingRequirementId,(($homeMycustomerData['fldserviceFee']+$homeMycustomerData['fldInsuranceFee'])*100),'预约定金','requirementSubmitSuccessYuyingsao');
				 }
			 }else{
				 $this->error('新增失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>$type),''));
			 }
		}else {
			$this->error('预约失败',U('Homemaking-requirementSubmit',array('v'=>time(),'type'=>$type),''));
		}
	}
	
}