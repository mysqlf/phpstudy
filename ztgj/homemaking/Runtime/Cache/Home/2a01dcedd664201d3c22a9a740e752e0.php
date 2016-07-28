<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>母婴问答</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="/Assets/Home/css/common.min.css" rel="stylesheet">
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
	<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp&libraries=convertor"></script>
	<script>
	  wx.config({
	    //debug: true,
	    appId: '<?php echo $signPackage["appId"];?>',
	    timestamp: <?php echo $signPackage["timestamp"];?>,
	    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
	    signature: '<?php echo $signPackage["signature"];?>',
	    jsApiList: [
	      // 所有要调用的 API 都要加到这个列表中
	      'getLocation','openLocation','hideMenuItems'
	    ]
	  });
	  wx.ready(function () {
	    // 在这里调用 API
	    wx.getLocation({
		    success: function (res) {
		        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
		        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
		        var speed = res.speed; // 速度，以米/每秒计
		        var accuracy = res.accuracy; // 位置精度
		        codeLatLng(latitude,longitude);
		        /*wx.openLocation({
				    latitude: latitude, // 纬度，浮点数，范围为90 ~ -90
				    longitude: longitude, // 经度，浮点数，范围为180 ~ -180。
				    name: '', // 位置名
				    address: '', // 地址详情说明
				    scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
				    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
				});*/
		    }
		});
		wx.hideMenuItems({
		    menuList: ['menuItem:share:appMessage','menuItem:share:timeline','menuItem:share:qq','menuItem:share:weiboApp','menuItem:favorite','menuItem:share:facebook','menuItem:share:QZone','menuItem:copyUrl','menuItem:originPage','menuItem:openWithQQBrowser','menuItem:openWithSafari','menuItem:share:email'] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
			/*,success: function (res) {
				alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
			},
			fail: function (res) {
				alert(JSON.stringify(res));
			}*/
		});	
	  });
	//腾讯地图反地址解析
	function codeLatLng(lat,lng) {
	    var geocoder = new qq.maps.Geocoder();
	    var latLng = new qq.maps.LatLng(lat, lng);
	    qq.maps.convertor.translate(new qq.maps.LatLng(lat, lng),1, function(res){
	    	latlng = res[0];
	    	geocoder.getAddress(latlng);
	    	geocoder.setComplete(function(result) {
	    		if (result.detail.addressComponents.district != '') {
	        		document.getElementById("location").value=result.detail.addressComponents.city+'.'+result.detail.addressComponents.district;
	        		//alert(result.detail.addressComponents.city+'.'+result.detail.addressComponents.district);
	    		}
	    		else if (result.detail.addressComponents.city =='东莞市' && result.detail.addressComponents.town =='篁村区') {
	        		document.getElementById("location").value=result.detail.addressComponents.city+'.市辖区';
	        		//alert(result.detail.addressComponents.city+'.市辖区');
	    		}else{
	        		document.getElementById("location").value=result.detail.addressComponents.city+'.'+result.detail.addressComponents.town;
	        		//alert(result.detail.addressComponents.city+'.'+result.detail.addressComponents.town);
	    		}
	        	//console.log(result);
	        	//alert(result.detail.address);
	        	//document.getElementById("location").value=result.detail.addressComponents.city+result.detail.addressComponents.district+result.detail.addressComponents.street;
	        	//alert(result.detail.address);
		        /*wx.openLocation({
				    latitude: latlng.getLat(), // 纬度，浮点数，范围为90 ~ -90
				    longitude: latlng.getLng(), // 经度，浮点数，范围为180 ~ -180。
				    name: '', // 位置名
				    address: '', // 地址详情说明
				    scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
				    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
				});*/
	        });
	        //若服务请求失败，则运行以下函数
	        geocoder.setError(function() {
	            //alert("出错了，请输入正确的经纬度！！！");
	            document.getElementById("location").value="广东东莞万江区";
	        });
		});
	}
	</script>
</head>
<body>
<div id="P_mamabang">
	<section class="banner">
	 <img class="img-block" src="/Assets/Home/images/banner2.jpg" alt="">
	</section>
    <section class="public-box stick-up public-box1">
      <form class="shuoshuo-form" action="Homemaking-motherBabyQAIssue" method="post">
      	 <div class="horizontal horizontal-r">
       	  <div class="right">
       	  	<input class="btn btn-block btn-lg btn-primary public-btn" type="button" value="发表">
       	  </div>
       	  <div class="left">
       	  	<input class="form-control input-toggle" name="shuoshuo" maxLength="500" required placeholder="请在此阐述您的疑问，字数500字以内" readonly></input>
       	  	<input id="location"  type="hidden" name="location" >
       	  </div>

       </div>
      </form>
    </section>

    <section>
    	<ul class="shuoshuo-box">
    	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="horizontal" data-id="<?php echo ($vo["fldId"]); ?>">
             <div class="left">
                  <!--<img class="img-responsive" src="Uploads/Weixin/<?php echo ($vo["fldOpenId"]); ?>.jpg" alt="">-->
                  <img class="img-responsive" src="<?php echo ($vo["PhotoUrl"]); ?>" alt="">
             </div>
             <div class="right">
                <a href="Homemaking-motherBabyQADetail-id-<?php echo ($vo["fldId"]); ?>-v-<?php echo ($vo["v"]); ?>" title="" target="_blank">
                  <h2 class="title c-brand"><?php echo ($vo["fldOpenName"]); ?></h2>
                  <div class="limit-h limit-3">
                  	<p class="limit-content"><?php echo ($vo["fldContent"]); ?></p>
                  </div>
		               
                </a>
                <div class="clearfix footer">
                   <span><?php echo ($vo["fldCreateDate"]); ?></span>
                   <a href="Homemaking-motherBabyQADetail-id-<?php echo ($vo["fldId"]); ?>-v-<?php echo ($vo["v"]); ?>" class="pull-right pinglun-btn" title="" target="_blank"><i class="icon icon-pinglun-gray"></i><?php echo ($vo["fldReplyNum"]); ?></a>
                   
                   <?php if($vo["isLike"] == 1): ?><span class="pull-right zan-btn isLike"><i class="icon"></i><span class="txt"><?php echo ($vo["fldLikeNum"]); ?></span></span>
                   <?php else: ?>
                   	<a href="javascript:;" class="pull-right zan-btn"><i class="icon"></i><span class="txt"><?php echo ($vo["fldLikeNum"]); ?></span></a><?php endif; ?>
                </div>
                	<?php if($vo["reply"] != null): ?><div class="reply-box">
		                <?php if(is_array($vo["reply"])): $i = 0; $__LIST__ = $vo["reply"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><p class="text-overflow"><span class="c-brand"><?php echo ($voo["fldOpenName"]); ?>：</span><?php echo ($voo["fldContent"]); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
	                </div><?php endif; ?>
             </div>
         </li><?php endforeach; endif; else: echo "" ;endif; ?>
       </ul>
    </section>
</div>

<script type="text/javascript" src="/Assets/Home/js/lib/sea.js"></script>
<script type="text/javascript" src="/Assets/Home/js/page/mamabang.min.js"></script>
</body>
</html>