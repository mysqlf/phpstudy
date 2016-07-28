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
				result.jsApiList = ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','showAllNonBaseMenuItem'];

				wx.config(result);
				//console.log(data);
				wx.ready(function(){
					wx.onMenuShareTimeline({
					    title: '', // 分享标题
					    link: '', // 分享链接
					    imgUrl: 'http://'+location.host+'/Assets/Home/images/wx/qrcode_for_gh_d493e0534132_344.jpg', // 分享图标
					    success: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					        // 用户确认分享后执行的回调函数
					    },
					    cancel: function () { 
					        // 用户取消分享后执行的回调函数
					        document.querySelector('.share-tip').style.display='none';
					    }
					});
					wx.onMenuShareAppMessage({
					    title: '', // 分享标题
					    desc: '', // 分享描述
					    link: '', // 分享链接
					    imgUrl: 'http://'+location.host+'/Assets/Home/images/wx/qrcode_for_gh_d493e0534132_344.jpg', // 分享图标
					    type: '', // 分享类型,music、video或link，不填默认为link
					    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
					    success: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					        // 用户确认分享后执行的回调函数
					    },
					    cancel: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					        // 用户取消分享后执行的回调函数
					    }
					});
					wx.onMenuShareQQ({
					    title: '', // 分享标题
					    desc: '', // 分享描述
					    link: '', // 分享链接
					    imgUrl: 'http://'+location.host+'/Assets/Home/images/wx/qrcode_for_gh_d493e0534132_344.jpg', // 分享图标
					    success: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					       // 用户确认分享后执行的回调函数
					    },
					    cancel: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					       // 用户取消分享后执行的回调函数
					    }
					});
					wx.onMenuShareWeibo({
					    title: '', // 分享标题
					    desc: '', // 分享描述
					    link: '', // 分享链接
					    imgUrl: 'http://'+location.host+'/Assets/Home/images/wx/qrcode_for_gh_d493e0534132_344.jpg', // 分享图标
					    success: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					       // 用户确认分享后执行的回调函数
					    },
					    cancel: function () { 
					    	document.querySelector('.share-tip').style.display='none';
					        // 用户取消分享后执行的回调函数
					    }
					});
					wx.showAllNonBaseMenuItem();
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