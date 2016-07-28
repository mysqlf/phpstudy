<?php 
class WeixinJSAction extends Action {
	private $_timestamp;
	private $_nonceStr = 'zhitongrencaiwang';
	private $_appId = APPID;

 	function get_jsconfig(){
 		$url = htmlspecialchars_decode(I('get.url'));
 		//$url = iconv("UTF-8", "gbk", urldecode($url));
		$accesstoken = accesstoken();

 		if(S('ticket')){
 			$this->_timestamp = S('timestamp');
 			$ticket = S('ticket');
 		}else{
  			$this->_timestamp = time();	
  			$ticket = $this->_ticket($accesstoken);		
 		}
		$signature = $this->_signature($ticket,$url);
		$data = array('appId'=>$this->_appId,'timestamp'=>$this->_timestamp,'nonceStr'=>$this->_nonceStr,'signature'=>$signature,'ticket'=>$ticket,'accesstoken'=>$accesstoken,'url'=>$url);
		echo json_encode($data);
	}

	private function _ticket($accesstoken){
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=jsapi';
		$timeout = 5;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);

		$res = curl_exec($ch);

		curl_close($ch);

        $res = json_decode($res,true);

        S('ticket',$res['ticket'],7100);
        S('timestamp',$this->_timestamp,7200);
        return $res['ticket'];
	}

	private function _signature($ticket,$url){
		return sha1('jsapi_ticket='.$ticket.'&noncestr='.$this->_nonceStr.'&timestamp='.$this->_timestamp.'&url='.$url);
	}
}