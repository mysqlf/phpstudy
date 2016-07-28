<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<title>学员们的学后感言</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="/Assets/Home/css/common.min.css" rel="stylesheet">
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=RvufqHb1h9WY4qwhBmGWs2Wv"></script>
</head>
<body>
	<section class="introduce-item skill_item">
		<h2 class="title">学员们的学后感言</h2>
		<div class="table-horizontal align-middle skill_list" id="img_box">
			<p><img class="img-block" src="/Assets/Home/images/impression_0.png" alt=""></p>
		</div>
	</section>
	<section class="introduce-item skill_item">
		<h2 class="title">户外拓展</h2>
		<div class="table-horizontal align-middle skill_list">
			<p class="sm_title"><img class="img-block" src="/Assets/Home/images/impression_37.png" alt=""></p>
			<p class="sm_title"><img class="img-block" src="/Assets/Home/images/impression_38.png" alt=""></p>
			<p class="sm_title"><img class="img-block" src="/Assets/Home/images/impression_39.png" alt=""></p>
		</div>
	</section>
	<script type="text/javascript" src="/Assets/Home/js/lib/sea.js"></script>
	<script>
	seajs.use(['/Assets/Home/js/lib/jquery-2.1.4.min'],function($){
		var source='/Assets/Home/images/';
		var dataJson=[
			{
				name:'谢璐',
				src1:source+'impression_1.png',
				src2:source+'impression_2.png',
				src3:source+'impression_3.png',
				src4:source+'impression_4.png',
			},
			{
				name:'吴海荣',
				src1:source+'impression_5.png',
				src2:source+'impression_6.png',
				src3:source+'impression_7.png',
				src4:source+'impression_8.png',
			},
			{
				name:'申利莉',
				src1:source+'impression_9.png',
				src2:source+'impression_10.png',
				src3:source+'impression_11.png',
				src4:source+'impression_12.png',
			},
			{
				name:'朱正英',
				src1:source+'impression_13.png',
				src2:source+'impression_14.png',
				src3:source+'impression_15.png',
				src4:source+'impression_16.png',
			},
			{
				name:'孙彦',
				src1:source+'impression_17.png',
				src2:source+'impression_18.png',
				src3:source+'impression_19.png',
				src4:source+'impression_20.png',
			},
			{
				name:'郭英',
				src1:source+'impression_21.png',
				src2:source+'impression_22.png',
				src3:source+'impression_23.png',
				src4:source+'impression_24.png',
			},
			{
				name:'胡远玉',
				src1:source+'impression_25.png',
				src2:source+'impression_26.png',
				src3:source+'impression_27.png',
				src4:source+'impression_28.png',
			},
			{
				name:'高春红',
				src1:source+'impression_29.png',
				src2:source+'impression_30.png',
				src3:source+'impression_31.png',
				src4:source+'impression_32.png',
			},
			{
				name:'廖七华',
				src1:source+'impression_33.png',
				src2:source+'impression_34.png',
				src3:source+'impression_35.png',
				src4:source+'impression_36.png',
			}
			
		]
		var html="";
		for(var i=0;i<dataJson.length;i++){
			html+='<p class="sm_title">'+dataJson[i].name+'</p>'+
			'<p><img class="img-block" src="'+dataJson[i].src1+'" alt=""></p>'+
			'<p><img class="img-block" src="'+dataJson[i].src2+'" alt=""></p>'+
			'<p><img class="img-block" src="'+dataJson[i].src3+'" alt=""></p>'+
			'<p><img class="img-block" src="'+dataJson[i].src4+'" alt=""></p>'
		}
		$('#img_box').append(html);
	});
	</script>
</body>

</style>
</html>