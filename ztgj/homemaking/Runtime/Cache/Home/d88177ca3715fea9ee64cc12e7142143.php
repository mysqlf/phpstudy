<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>育儿资讯</title>
<meta name="description" content="">
<meta name="keywords" content="">
<link href="/Assets/Home/css/common.min.css" rel="stylesheet">
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div id="P_parentingInformation">
    <section class="public-box search-box">
      <form  action="Homemaking-parentingInformationSearch" method="get">
      	 <div class="horizontal horizontal-r">
       	  <div class="right">
       	  	<input class="btn btn-block sub-btn" type="submit" value="" disabled="">
       	  </div>
       	  <div class="left">
       	  	<input class="form-control" name="keyword" maxLength="50" required placeholder="输入你要找的育儿知识">
       	  </div>

       </div>
      </form>
    </section>

    <section>
    	<ul class="zixun-box">
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="horizontal"> 
           <div class="left">
             <img class="img-responsive" src="/Assets/Home/images/zixun<?php echo ($v["fldId"]); ?>.jpg" alt="">    
           </div>
           <div class="right">
             <?php if(is_array($v['homemaking_communityitem'])): $i = 0; $__LIST__ = $v['homemaking_communityitem'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="btn btn-default" href="Homemaking-parentingInformationSearch-id-<?php echo ($vo["fldId"]); ?>-v-<?php echo ($timestamp); ?>" title="<?php echo ($vo["fldName"]); ?>" target="_blank"><?php echo ($vo["fldName"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
           </div>
        </li><?php endforeach; endif; else: echo "" ;endif; ?>
       </ul>
    </section>
</div>

<script type="text/javascript" src="/Assets/Home/js/lib/sea.js"></script>
<script type="text/javascript" src="/Assets/Home/js/page/zixun.min.js"></script>
<script type="text/javascript" src="/Assets/Home/js/wx/wxHideShare.js"></script>
</body>
</html>