<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php if($type == 1): ?>月嫂详情页<?php else: ?>育婴嫂详情页<?php endif; ?></title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="/Assets/Home/css/common.min.css" rel="stylesheet">
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>

<div id="P_requirementDetail" class="padding-bottom-gutter">
<section class="panel panel-border user-panel">
	<div class="panel-body horizontal">
	    <div class="user-head left">
	    	<img class="lazy" src="Assets/Home/images/lazy.gif" data-original="<?php echo ($result["PhotoUrl"]); ?>" alt="">
	    </div>
	    <div class="right user-info clearfix">
	    	<div class="clearfix text-overflow">
	    		<input id="postData" type="hidden" data-id="<?php echo ($result["fldID"]); ?>" data-type="<?php echo ($type); ?>">
	    		<h4 class="name col-4">
		    	   <?php echo ($result["fldName"]); ?>
		    	</h4>
		    	<div class="c-brand col-8 text-right">
		    	<?php if($result["Price"] != null): echo ($result["Price"]); if($type == 1): ?>元/26天起<?php else: ?>元/月起<?php endif; else: ?>未设置<?php endif; ?>
		    	</div>
	    	</div>
	    	<div class="text-overflow clearfix col-12">
	    	  	<span class="c-gray-light"><?php if($result["fldage"] != null): echo ($result["fldage"]); ?>岁<?php else: ?><!--未设置--><?php endif; ?></span>
	    	  	<span class="c-gray-light"><?php if($result["fldage"] != null): ?>属<?php echo ($result["chineseZodiac"]); else: ?><!--未设置--><?php endif; ?></span>
	    	  	<span class="c-gray-light"><?php if($result["fldage"] != null): echo ($result["constellation"]); else: ?><!--未设置--><?php endif; ?></span>
	    	   	<span class="c-gray-light"><?php if($result["fldNative"] != null): echo ($result["fldNative"]); else: ?><!--未设置--><?php endif; ?></span>
	        </div>
	        <div class="text-overflow clearfix col-12">
	        <?php if($type == 1): ?>带过几个宝宝：<span class="c-gray-light"><?php if($result["fldTakeBabyNum"] != null): echo ($result["fldTakeBabyNum"]); ?>个<?php else: ?>未设置<?php endif; ?></span>
	    	<?php else: ?>
	    	 工作年限：<span class="c-gray-light"><?php if($result["fldWorkYears"] != null): echo ($result["fldWorkYears"]); else: ?>未设置<?php endif; ?></span><?php endif; ?>
	        </div>
	        <div class="text-overflow clearfix col-12">
	    	 拿手菜：<span class="c-gray-light"><?php if($result["fldSpecialtyDish"] != null): echo ($result["fldSpecialtyDish"]); else: ?>未设置<?php endif; ?></span>
	        </div>
	        <div class="text-overflow clearfix col-12">
	    	 语言能力：<span class="c-gray-light"><?php if($result["fldLanguage"] != null): echo ($result["fldLanguage"]); else: ?>未设置<?php endif; ?></span>
	        </div>
	        <div class="text-overflow clearfix col-12">
		        <?php if($type == 1): ?>工作年限：<span class="c-gray-light"><?php if($result["fldWorkYears"] != null): echo ($result["fldWorkYears"]); else: ?>未设置<?php endif; ?></span>
		        <?php else: ?>
		        	婚姻：<span class="c-gray-light"><?php if($result["fldMarryStatus"] != null): echo ($result["fldMarryStatus"]); else: ?>未设置<?php endif; ?></span><?php endif; ?>
	    	   </div>
	    </div>
	    <div class="tag-box c-gray text-left">
			证件：
			<?php if(is_array($result["homemaking_certificate"])): $i = 0; $__LIST__ = $result["homemaking_certificate"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span class="tag tag-style<?php echo ($i%6==0?6:$i%6); ?>"><?php echo ($vo); ?></span>
				<!--
				<?php if($vo == '身份证'): ?><span class="tag tag-style<?php echo ($i%6==0?6:$i%6); ?>">身份已验证</span>
				<?php else: ?>
					<span class="tag tag-style<?php echo ($i%6==0?6:$i%6); ?>"><?php echo ($vo); ?></span><?php endif; ?>--><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
</section>
    
       
<section class="panel panel-border detail-panel">
	<div class="panel-body">
	     <h2 class="c-title green-title">自我评价</h2>
	     <?php if($result["fldSelfAppraise"] != null): echo ($result["fldSelfAppraise"]); else: ?>未设置<?php endif; ?>
	</div>
</section>
<section class="panel panel-border detail-panel">
	<div class="panel-body">
	     <h2 class="c-title yellow-title">技能特长</h2>
		 <?php if($result["fldSpecialty"] != null): echo ($result["fldSpecialty"]); else: ?>未设置<?php endif; ?>
	</div>
</section>
<section class="panel panel-border detail-panel">
	<div class="panel-body">
	     <?php if($type == 1): ?><h2 class="c-title blue-title">服务内容</h2>
		 <h3 class="sub-title">宝宝护理：</h3> 
		 <p>
		 	1、喂奶、喂水、换尿布  <br>
			2、洗澡、抚触按摩、脐带消毒、指导游泳  <br>
			3、宝宝衣物清洗和消毒、消毒奶瓶奶具  <br>
			4、夜间陪护、观察宝宝有无异常、和宝宝沟通开发潜能
		 </p>
		
		<h3 class="sub-title">产妇护理：</h3>  
		<p>
		1、制作营养餐，合理安排膳食<br>
		2、协助产妇做个人卫生、洗涤衣物、观察恶露<br>
		3、预防月子病<br>
		4、协助产妇做产后保健操及产后恢复护理
		</p>
		<?php else: ?>
		<h2 class="c-title blue-title">服务内容</h2>
		 <h3 class="sub-title">婴儿护理：</h3> 
		 <p class="c-dark">
		 	生活照料：<span class="c-gray">主要有饮食、饮水、睡眠、二便、三浴、卫生、
营养配餐、辅食添加、衣服玩具等清洁。</span>
		 </p>
		 <p class="c-dark">
		 	启蒙开发：<span class="c-gray">音乐智能、动作技能训练。其中大动作包括抬头、
翻身、坐、爬、走、跑、跳，精细动作包括手、眼、脑协调。</span>
		 </p>
		 <p class="c-dark">
		 	日常保健：<span class="c-gray">体格训练、婴幼儿被动操、婴幼儿健身操。</span>
		 </p><?php endif; ?>
	</div>
</section>
<section class="panel panel-border detail-panel honor-panel">
	<div class="panel-body">
	     <h2 class="c-title yellow2-title">荣誉&证件</h2>
		<?php if(!empty($result["honorPhoto"])): ?><ul class="clearfix imagelightbox" data-target="#slider-honor">
				<?php if(is_array($result["honorPhoto"])): $i = 0; $__LIST__ = array_slice($result["honorPhoto"],0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="col-4">
			            <a href="javascript:;" target="_blank"><img class="lazy img-responsive" src="Assets/Home/images/lazy.gif" data-original="<?php echo ($vo["fldUrl"]); ?>" alt="" /></a>
			        </li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul><?php endif; ?>
		<!--<?php if(!empty($result["honorPhoto"])): ?><div class="page">
		        <div id="slider">
		            <ul>
		            	<?php if(is_array($result["honorPhoto"])): $i = 0; $__LIST__ = array_slice($result["honorPhoto"],0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="display:block">
			                    <div class="pinch-zoom">
			                        <img src="<?php echo ($vo["fldUrl"]); ?>"/>
			                    </div>
			                  </li><?php endforeach; endif; else: echo "" ;endif; ?>
		            </ul>
		        </div>
		    </div><?php endif; ?>-->
		<script id="slider-honor" type="text/html">
			<?php if(!empty($result["honorPhoto"])): ?><div class="page">
				<!--<div class="mask"></div>-->
				<span class="close-icon">×</span>
			        <div id="slider" class="slider-box">
			            <ul>
			            	<?php if(is_array($result["honorPhoto"])): $i = 0; $__LIST__ = array_slice($result["honorPhoto"],0,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="display:block">
				                    <div class="pinch-zoom">
				                        <img src="<?php echo ($vo["fldUrl"]); ?>"/>
				                    </div>
				                  </li><?php endforeach; endif; else: echo "" ;endif; ?>
			            </ul>
			        </div>
			    </div><?php endif; ?>
		</script>
	</div>
</section>
<section class="panel panel-border detail-panel">
	<div class="panel-body">
		<?php if($all > 0): ?><h2 class="c-title pink-title">雇主点评(<?php echo ($homemakingServiceCommentcount); ?>)</h2>
	    <?php if($type == 1): ?><div class="horizontal">
        	<div class="left">
        		<p class="text-center pingfen-circle">
        		  <em class="num"><?php echo ($all); ?><span class="small">%</span></em> <br>
        		  好评度
        		</p>
        	</div>
        	<div class="right pingfen">
        		<div class="col-6">宝宝护理 (<?php echo ($baby); ?>%)</div>
        		<div class="col-6">
        			<div class="progress">
					  <div class="progress-bar" style="width: <?php echo ($baby); ?>%;"></div>
					</div>
        		</div>
        		<div class="col-6">产妇护理 (<?php echo ($mother); ?>%)</div>
        		<div class="col-6">
        			<div class="progress">
					  <div class="progress-bar" style="width: <?php echo ($mother); ?>%;"></div>
					</div>
        		</div>
        		<div class="col-6">个人素质 (<?php echo ($sitter); ?>%)</div>
        		<div class="col-6">
        			<div class="progress">
					  <div class="progress-bar" style="width: <?php echo ($sitter); ?>%;"></div>
					</div>
        		</div>

        	</div>
        </div>
        <?php else: ?>
        	<p class="pull-right pingfen2">
	           好评度: <em class="num c-brand"><?php echo ($all); ?><span class="small c-brand">%</span></em> 
	        </p><?php endif; ?>
        <?php else: ?>
	        <h2 class="c-title pink-title">雇主点评(<?php echo ($homemakingServiceCommentcount); ?>)</h2>
		    <?php if($type == 1): ?><div class="horizontal">
	        	<div class="left">
	        		<p class="text-center pingfen-circle">
	        		  <em class="num">100<span class="small">%</span></em> <br>
	        		  好评度
	        		</p>
	        	</div>
	        	<div class="right pingfen">
	        		<div class="col-6">宝宝护理 (100%)</div>
	        		<div class="col-6">
	        			<div class="progress">
						  <div class="progress-bar" style="width: 100%;"></div>
						</div>
	        		</div>
	        		<div class="col-6">产妇护理 (100%)</div>
	        		<div class="col-6">
	        			<div class="progress">
						  <div class="progress-bar" style="width: 100%;"></div>
						</div>
	        		</div>
	        		<div class="col-6">个人素质 (100%)</div>
	        		<div class="col-6">
	        			<div class="progress">
						  <div class="progress-bar" style="width: 100%;"></div>
						</div>
	        		</div>

	        	</div>
	        </div>
	        <?php else: ?>
	        	<p class="pull-right pingfen2">
		           好评度: <em class="num c-brand">100<span class="small c-brand">%</span></em> 
		        </p><?php endif; endif; ?>
	</div>
</section>
<ul class="fuwu-item">
	<?php if(is_array($homemakingServiceCommentResult)): $k = 0; $__LIST__ = $homemakingServiceCommentResult;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="panel panel-border">

		<div class="panel-body clearfix">
			<div class="col-4 text-overflow c-dark">雇主：<?php echo ($vo["fldName"]); ?></div>
			<div class="col-8 text-overflow text-right c-dark">服务时间：<span class="c-gray-light"><?php echo ($vo["fldBeginDate"]); ?>~<?php echo ($vo["fldEndDate"]); ?></span></div>
		    <h4 class="sub-title clear">评语：</h4>
		    <p><?php if($vo["fldAssessment"] != null): echo ($vo["fldAssessment"]); else: ?>未填写<?php endif; ?></p>
		    <ul class="clearfix imagelightbox"  data-target="#slider-comment<?php echo ($k); ?>">
		      <?php if(is_array($vo["UploadFile"])): $i = 0; $__LIST__ = $vo["UploadFile"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><li class="col-4"><a href="javascript:;" target="_blank"><img class="lazy img-responsive" src="Assets/Home/images/lazy.gif" data-original="<?php echo ($voo[1]); ?>"  alt="" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<script id="slider-comment<?php echo ($k); ?>" type="text/html">
				<?php if(!empty($vo["UploadFile"])): ?><div class="page">
					<span class="close-icon">×</span>
				        <div id="slider" class="slider-box">
				            <ul>
				            	<?php if(is_array($vo["UploadFile"])): $i = 0; $__LIST__ = $vo["UploadFile"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><li style="display:block">
					                    <div class="pinch-zoom">
					                        <img src="<?php echo ($voo[0]); ?>"/>
					                    </div>
					                  </li><?php endforeach; endif; else: echo "" ;endif; ?>
				            </ul>
				        </div>
				    </div><?php endif; ?>
			</script>
		</div>
	</li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<a class="btn btn-block fixed-bar fixed-bottom fixed-btn btn-primary" href="Homemaking-requirementSubmit-type-<?php echo ($type); ?>-id-<?php echo ($result["fldID"]); ?>">马上预约</a>
<script type="text/javascript" src="Assets/Home/js/lib/sea.js"></script>
<script type="text/javascript" src="Assets/Home/js/lib/seajs-css.js"></script>
<script type="text/javascript" src="Assets/Home/js/page/requirement.min.js"></script>
<script type="text/javascript" src="/Assets/Home/js/wx/wxHideShare.js"></script>
</body>
</html>