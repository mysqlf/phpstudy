(function(){
	function loadConfig(){
		var xmlhttp;
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
		}else{
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				var data = eval("(" + xmlhttp.responseText + ")");

				//data.jsApiList = ['onMenuShareTimeline'];//'getLocation',
				//alert(JSON.stringify(data));
				//通过config接口注入权限验证配置
				var result = {};
				result.appId = data.appId;
				result.timestamp = data.timestamp;
				result.nonceStr = data.nonceStr;
				result.signature = data.signature;

				result.debug = false;				
				result.jsApiList = ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'];

				wx.config(result);
				//console.log(data);
				wx.ready(function(){
					wx.hideMenuItems({
					    menuList: ['menuItem:share:appMessage','menuItem:share:timeline','menuItem:share:qq','menuItem:share:weiboApp','menuItem:favorite','menuItem:share:facebook','menuItem:share:QZone','menuItem:copyUrl','menuItem:originPage','menuItem:openWithQQBrowser','menuItem:openWithSafari','menuItem:share:email','menuItem:share:brand'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
					});						
				});						
				wx.error(function(res){
					console.log('error:'+res);
				});
			}
		}
		xmlhttp.open('GET','/WeixinJS-get_jsconfig?url='+encodeURIComponent(location.href.split('#')[0]),true);
		xmlhttp.send();
	}
	loadConfig();
})();