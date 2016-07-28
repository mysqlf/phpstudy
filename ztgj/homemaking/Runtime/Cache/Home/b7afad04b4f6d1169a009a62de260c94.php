<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>我要预约</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="Assets/Home/css/common.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>

<body>
    <div id="P_custom">
        <section class="sort-box tab-box">
            <?php if($type == '月嫂'): ?><div class="text-center sort-title stick-up goTop">月嫂条件筛选</div>
                <?php else: ?>
                <div class="text-center sort-title stick-up goTop">育婴嫂条件筛选</div><?php endif; ?>
            <ul class="flex-box tab-nav hidden">
                <?php if($type == '月嫂'): ?><li><a class="active" data-type="月嫂" href="javascript:;">月嫂</a></li>
                    <li><a href="Homemaking-requirement-type-育婴员" data-type="育婴员">育婴嫂</a></li>
                    <?php else: ?>
                    <li><a href="Homemaking-requirement-type-月嫂" data-type="月嫂" href="javascript:;">月嫂</a></li>
                    <li><a class="active" data-type="育婴员">育婴嫂</a></li><?php endif; ?>
            </ul>
            <ul class="tab-content">
                <li>
                    <a class="down-select" id="sort-pay" href="javascript:;" data-select="<?php echo ($gradePriceArray["string"]); ?>">
                        <i class="icon  icon-heart"></i>
                        <span class="value">
		                <?php if($pay == null): ?>价格区间<?php if($type == '月嫂'): ?>（元/26天）<?php else: ?>（元/月）<?php endif; elseif($pay == '不限'): ?>不限<?php else: echo ($pay); if($type == '月嫂'): ?>元/26天<?php else: ?>元/月<?php endif; endif; ?>
		                </span>
                        <i class="icon icon-arrow-down pull-right"></i>
                    </a>
                </li>

                <li>
                    <a class="down-select" id="sort-place" href="javascript:;" data-select="不限,广东,广西,湖南,湖北,四川,浙江,江西">
                        <i class="icon icon-user"></i>
                        <?php if($type == '月嫂'): ?><span data-temp="月嫂籍贯" class="value">
				                <?php if($place == null): ?>月嫂籍贯<?php else: echo ($place); endif; ?>
				                </span>
                            <?php else: ?>
                            <span data-temp="育婴嫂籍贯" class="value">
				                <?php if($place == null): ?>育婴嫂籍贯<?php else: echo ($place); endif; ?></span><?php endif; ?><i class="icon icon-arrow-down pull-right"></i></a>
                </li>

                <li>
                    <?php if($type == '月嫂'): ?><a id="sort-date" class="date-toggle" href="javascript:;">
                            <i class="icon icon-rili"></i>
                            <span class="value">
					                <?php if($date != null): echo ($date); ?> <?php else: ?> 您的预产期<?php endif; ?>
					        </span>
                            <i class="icon icon-arrow-down pull-right"></i>
                            <!--<input class="input-date" min="<?php echo date('Y-m-d',time()); ?>" type="text" >-->
                            <input class="input-date" type="text">
                        </a>
                        <?php else: ?>
                        <a class="down-select" id="sort-date" href="javascript:;" data-select="不限,30岁以下,30-35岁,36-40岁,41-45岁,45岁以上">
                            <i class="icon icon-rili"></i>
                            <span class="value">
			                	<?php if($date != null): echo ($date); ?> <?php else: ?> 育婴嫂年龄<?php endif; ?>
			                </span>
                            <i class="icon icon-arrow-down pull-right"></i>
                        </a><?php endif; ?>
                </li>

                <li>
                    <a class="down-select" id="sort-place2" href="javascript:;" data-select="<?php echo ($locations); ?>">
                        <i class="icon icon-place"></i>
                        <?php if($location != null): ?><span data-temp="月嫂籍贯" class="value"><?php echo ($location); ?></span>
                            <?php elseif($type == '月嫂' ): ?>
                            <span data-temp="月嫂所在地" class="value">月嫂所在地</span>
                            <?php else: ?>
                            <span data-temp="育婴嫂所在地" class="value">育婴嫂所在地</span><?php endif; ?>
                        <i class="icon icon-arrow-down pull-right"></i>
                    </a>
                </li>

            </ul>
        </section>

        <section>
            <?php if($list == null): ?><img class="img-responsive no-data" src="/Assets/Home/images/no-data.gif" />
                <?php else: ?>
                <ul class="yuesao-list">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <?php if($type == '月嫂'): ?><a href="/Homemaking-requirementDetail-id-<?php echo ($vo["fldID"]); ?>-type-1-v-<?php echo ($timestamp); ?>" class="panel">
                                    <?php else: ?>
                                    <a href="/Homemaking-requirementDetail-id-<?php echo ($vo["fldID"]); ?>-type-2-v-<?php echo ($timestamp); ?>" class="panel"><?php endif; ?>
                            <div class="panel-body horizontal">
                                <div class="yuesao-head left">
                                    <img class="lazy" src="/Assets/Home/images/lazy.gif" data-original="<?php echo ($vo["fldPhotoUrl"]); ?>" alt="">
                                </div>
                                <ul class="right yuesao-info">
                                    <li class="clearfix">
                                        <span class="col-3"><?php echo ($vo["fldName"]); ?></span> <span class="col-9 text-right c-brand">
						    		<?php if($vo["fldPrice"] == null): ?>未设置
					           		<?php else: ?>
				           				<?php if($type == '月嫂'): echo ($vo["fldPrice"]); ?>元/26天 
										<?php else: ?>
											<?php echo ($vo["fldPrice"]); ?>元/月<?php endif; endif; ?>
				           		</span>
                                    </li>
                                    <li class="clearfix">
                                        <div class="col-12 text-overflow">
                                            <span class="c-gray-light">
							    		<?php if($vo["fldage"] == null): ?><!--未设置-->
				           			<?php else: ?>
				           				<?php echo ($vo["fldage"]); ?>岁<?php endif; ?>
			           			</span>
                                            <span class="c-gray-light">
							    		<?php if($vo["chineseZodiac"] == null): ?><!--未设置-->
				           			<?php else: ?>
				           				属<?php echo ($vo["chineseZodiac"]); endif; ?>
			           			</span>
                                            <span class="c-gray-light">
							    		<?php if($vo["constellation"] == null): ?><!--未设置-->
				           			<?php else: ?>
				           				<?php echo ($vo["constellation"]); endif; ?>
			           			</span>
                                            <span class="c-gray-light">
						    			<?php if($vo["fldNative"] == null): ?><!--未设置-->
				           			<?php else: ?>
				           				<?php echo ($vo["fldNative"]); endif; ?>
						    		</span>
                                        </div>

                                        <?php if($type == '月嫂'): ?><div class="col-12">带过几个宝宝：<span class="c-gray-light">
					    			<?php if($vo["fldTakeBabyNum"] == null): ?>未设置
				           			<?php else: ?>
				           				<?php echo ($vo["fldTakeBabyNum"]); ?>个<?php endif; ?>
					    		</span></div>
                                            <?php else: ?>
                                            <div class="col-12">工作年限：<span class="c-gray-light">
					    			<?php if($vo["fldWorkYears"] == null): ?>未设置
				           			<?php else: ?>
				           				<?php echo ($vo["fldWorkYears"]); endif; ?>
					    		</span></div><?php endif; ?>
                                        <div class="col-12 text-overflow">拿手菜：<span class="c-gray-light">
					    			<?php if($vo["fldSpecialtyDish"] == null): ?>未设置
				           			<?php else: ?>
				           				<?php echo ($vo["fldSpecialtyDish"]); endif; ?>
				           		</span></div>
                                        <div class="col-12">语言能力：
                                            <span class="c-gray-light">
					    			<?php if($vo["fldLanguage"] == null): ?>未设置
				           			<?php else: ?>
				           				<?php echo ($vo["fldLanguage"]); endif; ?>
					    		</span>
                                            <span class="pull-right c-gray-light"><i class="icon icon-pinglun-gray"></i><?php echo ($vo["homemakingServiceCommentNum"]); ?></span></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="panel-footer">

                                <div class="tag-box c-gray text-left">
                                    <?php if($vo["homemaking_certificate"] != null): ?>证件：
                                        <?php if(is_array($vo["homemaking_certificate"])): $k = 0; $__LIST__ = $vo["homemaking_certificate"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($k % 2 );++$k;?><span class="tag tag-style<?php echo ($key+1); ?>"><?php echo ($voo); ?></span><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </div>
                            </div>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul><?php endif; ?>
        </section>
        <?php if($type == '月嫂'): ?><a class="btn btn-block fixed-bar fixed-bottom fixed-btn btn-primary" href="/Homemaking-requirementSubmit-type-1-v-<?php echo ($timestamp); ?>">马上预约</a>
            <?php else: ?>
            <a class="btn btn-block fixed-bar fixed-bottom fixed-btn btn-primary" href="/Homemaking-requirementSubmit-type-2-v-<?php echo ($timestamp); ?>">马上预约</a><?php endif; ?>


    </div>

    <script type="text/javascript" src="/Assets/Home/js/lib/sea.js"></script>
    <script type="text/javascript" src="/Assets/Home/js/lib/seajs-css.js"></script>
    <script async="async" type="text/javascript" src="/Assets/Home/js/page/custom.min.js"></script>
    <script type="text/javascript" src="/Assets/Home/js/wx/wxHideShare.js"></script>

</body>

</html>