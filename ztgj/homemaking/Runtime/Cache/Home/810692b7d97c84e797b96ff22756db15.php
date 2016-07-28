<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<title>关于私享家</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="/Assets/Home/css/common.min.css" rel="stylesheet">
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=RvufqHb1h9WY4qwhBmGWs2Wv"></script>
</head>
<body>
	<section class="introduce-item skill_item">
		<h2 class="title">公司简介</h2>
		<div class="table-horizontal align-middle skill_list">
		  	<p class="first">东莞市私享家家庭服务有限公司是广东智通人才连锁集团旗下机构（证券代码：830969），是东莞首家为高端家庭量身定制家庭服务的企业，主营服务产品有私家定制管家、月嫂、育婴师、环境管理师、营养配餐师、教育指导师、养生保健师等，旨在以培养家庭服务型人才和高端家庭管理型人才为己任，以引领中国家庭服务行业标准为使命。我们践行员工制的企业化管理模式，坚持“安全、贴心、高效、极致”的服务理念，专注为您营造舒适的家庭环境、祝您畅享尊贵的品味生活，缔造本土最具品质的高端家庭服务机构！</p>
			<p><img class="img-block" src="/Assets/Home/images/skill_0.png" alt=""></p>
		</div>
	</section>
	<section class="introduce-item skill_item">
		<h2 class="title">服务宣言</h2>
		<div class="table-horizontal align-middle skill_list">
		  	<p>我们正在从事一个朝阳的行业</p>
		  	<p>我们正在提供一种人性化专属的服务</p>
		  	<p>我们正在使家庭和社会更和谐更稳定</p>
		  	<p>所以，我们深知......</p>
		  	<p>服务就是我们的社会使命</p>
		  	<p>信誉就是我们的企业灵魂</p>
		  	<p>品质就是我们的价值体现</p>
		  	<p>为此......</p>
		  	<p>我们将更加热情服务每一位客户</p>
		  	<p>更加用心聆听每一个声音</p>
		  	<p>因为我们致力于成为家庭服务行业的优秀典范</p>
		  	<p style="margin-top:10px;">私享家</p>
		  	<p>私家定制，享你所享</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_1.png" alt=""></p>
		</div>
	</section>
	<!--<section class="introduce-item skill_item">
		<h2 class="title">企业文化</h2>
		<p><a href="http://mp.weixin.qq.com/s?__biz=MzAxMzkzMzM5Mw==&mid=100000183&idx=1&sn=fb7c3d6e6e5850eae9cd8b6f18e88026&scene=18#wechat_redirect">详情请点击</a></p>
	</section>-->
	<section class="introduce-item skill_item">
		<h2 class="title">公司环境</h2>
		<div class="table-horizontal align-middle skill_list">
		  	<p class="sm_title">前台</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_2.png" alt=""></p>
		  	<p class="sm_title">办公区入口</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_3.png" alt=""></p>
		  	<p class="sm_title">会议室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_4.png" alt=""></p>
		  	<p class="sm_title">客户接待室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_5.png" alt=""></p>
		  	<p class="sm_title">茶水间</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_6.png" alt=""></p>
		  	<p class="sm_title">实操室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_7.png" alt=""></p>
		  	<p class="sm_title">小教室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_8.png" alt=""></p>
		  	<p class="sm_title">财务室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_9.png" alt=""></p>
		  	<p class="sm_title">总经理办公室</p>
		  	<p><img class="img-block" src="/Assets/Home/images/skill_10.png" alt=""></p>
		  	<p style="margin-top:10px;">电话/地址/公交</p>
			<p style="margin-top:10px;">座  机：0769-26260090</p>
			<p>地  址：东莞市东城区东莞大道11号环球经贸中心主楼2808</p>
			<p>公交：19路 328路 36路 46路 9路 C2路 C4路  X13路  X5路</p>
		</div>
	</section>
	<section class="introduce-item skill_item">
		<h2 class="title">导航地图</h2>
		<p id="allmap"></p>
	</section>
	<script type="text/javascript">
	// 百度地图API功能
	var map = new BMap.Map("allmap");
	var point = new BMap.Point(113.765821,23.015877);
	map.centerAndZoom(point,14);
	map.addOverlay(new BMap.Marker(point));
	</script>
</body>

</style>
</html>