<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">
     var browser_class = navigator.userAgent;
     var browser_class_name1 = browser_class.match("Mobile");
     var browser_class_name2 = browser_class.match("mobile");
     var location_url = window.location.href;
     if (browser_class_name1 != null || browser_class_name2 != null){
         if (location_url.match("wap") == null){
         window.location.href="/jf/app.php";
         }
     } else
     {
        if (location_url.match("3g") != null || location_url.match("wap") != null){
        window.location.href="/jf/app.php";
        }
     }
     </script>
    <meta name="renderer" content="webkit">
    <meta charset="utf-8" />
    <title><?php if(empty($title)): echo ($sys["title"]); else: echo ($title); endif; ?></title>
    <!--<title>创推购</title>-->
    <meta content="<?php if(empty($keywords)): echo ($sys["keywords"]); else: echo ($keywords); endif; ?>" name="keywords" />
    <meta content="<?php if(empty($description)): echo ($sys["description"]); else: echo ($description); endif; ?>" name="description" />
    <link rel="stylesheet" type="text/css" href="/jf/images/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/jf/images/reset.css">
    <link rel="stylesheet" type="text/css" href="/jf/images/style.css">
    <link rel="stylesheet" type="text/css" href="/jf/Public/fonts/fonts.css">
    <script type="text/javascript" src="/jf/images/jquery.js"></script>
    <script type="text/javascript" src="/jf/Public/layer/layer.js"></script>
    <script type="text/javascript" src="/jf/Public/Validform/Validform.js"></script>
    <link rel="stylesheet" type="text/css" href="/jf/Public/Validform/Validform.css">
    <script type="text/javascript" src="/jf/images/comm.js"></script>
</head>
<body>
    <!-- top -->
    <div class="jiang cl oh">
        <div class="box cl oh">
            <div class="fl" id="scrollWrap">
                <ul id="scrollMsg">
                <?php if(is_array($new_order)): $i = 0; $__LIST__ = $new_order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><span class="red">恭喜会员：<?php echo ($vo["mbname"]); ?></span>　<?php echo (cut($vo["ptname"],20)); ?>　<?php echo (r_p_score($vo["ptid"])); ?>积分　<span class="red">（<?php echo (mdate($vo["order_date"])); ?>）</span></li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
            <div class="fr"><?php if(empty($mbinfo["name"])): ?><a href="<?php echo U('Index/register');?>">注册会员</a><?php else: ?>你好！<?php echo ($mbinfo["name"]); ?>，欢迎登录创维购。 <a href="<?php echo U('Index/logout');?>">[退出平台]</a><?php endif; ?></div>
        </div>
    </div>
    <div class="top box cl oh">
        <div class="logo fl oh"><a href="/jf/"><img src="/jf/images/logo.png" alt="慧捷积分兑换"></a></div>
        <div class="search fr oh" style="margin-right: 50px;">
            <form action="<?php echo U('Index/lists');?>" method="post" accept-charset="utf-8">
                <input type="text" name="keyword" placeholder="输入要兑换的商品名称" >
                <input type="submit" value="　">
            </form>
        </div>
        <!--<div class="shoucang fr oh"><a href="#"><img src="/jf/images/shoucang_ico.png"></a></div>-->
        <!--<div class="tel400 fr oh"><img src="/jf/images/400.png" alt="400电话" style="display: block;float: left"><span style="font-size: 24px;color: #ED4440;font-weight: 700;display: block;float: left">0358-6666666</span></div>-->
    </div>
    <!-- nav -->
    <div class="nav cl oh">
        <div class="box">
            <a <?php if(($home) == "1"): ?>class="active"<?php endif; ?> href="/jf/">　首页　</a>
            <a href="<?php echo U('Index/lists');?>" <?php if(($active) == "2"): ?>class="active"<?php endif; ?>>积分兑换</a>
            <a href="<?php echo U('Member/index');?>" <?php if(($active) == "4"): ?>class="active"<?php endif; ?>>会员中心</a>
            <!--<a href="<?php echo U('Index/lists');?>" <?php if(($active) == "15"): ?>class="active"<?php endif; ?>>合作商家</a>-->
            <a href="<?php echo U('Index/page',array('cid'=>15));?>" <?php if(($cid) == "15"): ?>class="active"<?php endif; ?>>合作商家</a>
            <a href="<?php echo U('Index/page',array('cid'=>17));?>" <?php if(($cid) == "17"): ?>class="active"<?php endif; ?>>会员规则</a>
            <a href="<?php echo U('Index/page',array('cid'=>18));?>" <?php if(($cid) == "18"): ?>class="active"<?php endif; ?>>常见问题</a>
            <a href="<?php echo U('Index/page',array('cid'=>19));?>" <?php if(($cid) == "19"): ?>class="active"<?php endif; ?>>关于我们</a>
            <div class="tel4001"><img src="/jf/images/4001.png" alt="400电话" style="display: block;float: left;margin-top:10px;height: 25px;"><span style="font-size: 20px;color: white;font-weight: 700;display: block;float: left">0358-6666666</span></div>
        </div>
    </div>
    <div style="height:20px;clear:both;"></div>
    <div class="box">
        <!-- category -->
        <div class="category fl">
            <div style="height:48px;clear:both;"></div>
            <?php if(is_array(M('Category')->limit(6)->where('pid=1')->order('sort')->select())): foreach(M('Category')->limit(6)->where('pid=1')->order('sort')->select() as $i=>$vo): ?><li>
                <a class="menu" href="<?php echo U('Index/lists',array('cid'=>$vo['id']));?>"><?php echo ($vo["name"]); ?></a>
                <?php if((is_scate($vo["id"])) != "0"): ?><div class="son_nav">
                    <?php if(is_array(M('Category')->limit(100)->where('pid='.$vo["id"])->order('sort')->select())): foreach(M('Category')->limit(100)->where('pid='.$vo["id"])->order('sort')->select() as $i=>$vo): ?><a href="<?php echo U('Index/lists',array('cid'=>$vo['id']));?>"><?php echo ($vo["name"]); ?></a><?php endforeach; endif; ?>
                </div><?php endif; ?>
            </li><?php endforeach; endif; ?>
        </div>
        <!-- banner -->
        <script type="text/javascript" src="/jf/images/jquery.superslide.js"></script>
        <div class="banner fl oh">
             <ul class="banner_img">
                <?php $m=M("Doc_banner");$ret=$m->field("")->where("cid=20")->order("")->limit("10")->select();if ($ret): $i=0;foreach($ret as $key=>$vo):++$i;$mod = ($i % 2 );?><li><a href="<?php echo ($vo["link"]); ?>"><img src="<?php echo (img($vo["thumb"])); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>"style="height: 340px;"/></a></li><?php endforeach;endif;?>
            </ul>
            <a class="prev" href="javascript:void(0)"></a>
            <a class="next" href="javascript:void(0)"></a>
            <div class="num">
                <ul></ul>
            </div>
            <script type="text/javascript">
            /*鼠标移过某个按钮 高亮显示*/
            $(".prev,.next").hover(function(){
                $(this).fadeTo("show",0.7);
            },function(){
                $(this).fadeTo("show",0.1);
            })
            $(".banner").slide({ titCell:".num ul" , mainCell:".banner_img" , effect:"fold", autoPlay:true, delayTime:700 , autoPage:true });
            </script>
        </div>
        <!-- user center -->
        <div class="h_user_center fr oh">
            <div style="height:49px;clear:both;background: none;"></div>
<div style="height:11px;clear:both;background: white;width: 96%;margin: 0 auto;"></div>
<?php if(empty($mbinfo)): ?><div class="login">
<form class="form-1" id="myform1" action="<?php echo U('Index/login');?>" method="post" accept-charset="utf-8">
    <input type="text" name="username" fs="请填写用户名" datatype="*" nullmsg="用户名不能为空">
    <input type="password" name="password" placeholder="密码">
    <input type="submit" value=" " title="登录">
</form>
    <a href="<?php echo U('Index/page',array('id'=>8));?>" title="忘记密码"></a>
</div>
<?php else: ?>
<div class="user_info">
    <p>账户：<a href="<?php echo U('Member/index');?>"><?php echo ($mbinfo["username"]); ?></a></p>
    <p>姓名：<?php echo ($mbinfo["name"]); ?></p>
    <p>当前积分：<?php echo ($mbinfo["score"]); ?></p>
    <p>登录时间：<?php echo (date2($mbinfo["last_login_time"])); ?></p>
    <div class="log_out"><a href="<?php echo U('Index/logout');?>">退出平台</a></div>
</div><?php endif; ?>
<div style="height:15px;clear:both;background: white;width: 96%;margin: 0 auto"></div>
<a href="<?php echo U('Cart/index');?>"><div class="cart">
    <?php if(!empty($mbinfo)): ?><span><?php echo ($cartnum); ?></span><?php endif; ?>
</div></a>
        </div>
    </div>
    <div style="height:5px;clear:both;"></div>
    <div class="box push cl oh" style="width: 1045px;;border: 1px solid #d1d1d1;margin:20px auto;background:white;">
        <div class="good gt_1" style="width:25%;"><a class="menu active" onclick="choosepush(1)">大家都喜欢</a></div>
        <div class="good gt_2" style="width:25%;"><a class="menu" onclick="choosepush(2)">最新</a></div>
        <div class="good gt_3" style="width:25%;"><a class="menu" onclick="choosepush(3)">推荐</a></div>
        <div class="good gt_4" style="width:25%;"><a class="menu" onclick="choosepush(4)">特价</a></div>
        <div style="height:5px;clear:both;"></div>
        <?php $m=M("Doc_product");$ret=$m->field("")->where(" 1 ")->order("")->limit("16")->select();if ($ret): $i=0;foreach($ret as $key=>$vo):++$i;$mod = ($i % 2 );?><div class="good_info gi_1" <?php if(in_array(($i), explode(',',"4,8,12,16,20,24"))): ?>style="margin-right:0;float:right;"<?php endif; ?>>
            <div class="img"><a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><img src="<?php echo (thumb($vo["thumb"])); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>"></a></div>
            <div class="tit"><span class="t1"><?php echo (cut($vo["title"],12)); ?></span><span class="t2"><span class="num"><?php echo ($vo["score"]); ?> </span>积分</span></div>
            <a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><div class="buy">立即兑换</div></a>
        </div><?php endforeach;endif;?>
        <?php $m=M("Doc_product");$ret=$m->field("")->where(" 1 ")->order("")->limit("16")->select();if ($ret): $i=0;foreach($ret as $key=>$vo):++$i;$mod = ($i % 2 );?><div class="good_info gi_2" <?php if(in_array(($i), explode(',',"4,8,12,16,20,24"))): ?>style="margin-right:0;float:right;"<?php endif; ?>>
            <div class="img"><a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><img src="<?php echo (thumb($vo["thumb"])); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>"></a></div>
            <div class="tit"><span class="t1"><?php echo (cut($vo["title"],12)); ?></span><span class="t2"><span class="num"><?php echo ($vo["score"]); ?> </span>积分</span></div>
            <a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><div class="buy">立即兑换</div></a>
        </div><?php endforeach;endif;?>
        <?php $m=M("Doc_product");$ret=$m->field("")->where("push=2")->order("")->limit("16")->select();if ($ret): $i=0;foreach($ret as $key=>$vo):++$i;$mod = ($i % 2 );?><div class="good_info gi_3" <?php if(in_array(($i), explode(',',"4,8,12,16,20,24"))): ?>style="margin-right:0;float:right;"<?php endif; ?>>
            <div class="img"><a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><img src="<?php echo (thumb($vo["thumb"])); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>"></a></div>
            <div class="tit"><span class="t1"><?php echo (cut($vo["title"],12)); ?></span><span class="t2"><span class="num"><?php echo ($vo["score"]); ?> </span>积分</span></div>
            <a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><div class="buy">立即兑换</div></a>
        </div><?php endforeach;endif;?>
        <?php $m=M("Doc_product");$ret=$m->field("")->where("push=3")->order("")->limit("16")->select();if ($ret): $i=0;foreach($ret as $key=>$vo):++$i;$mod = ($i % 2 );?><div class="good_info gi_4" <?php if(in_array(($i), explode(',',"4,8,12,16,20,24"))): ?>style="margin-right:0;float:right;"<?php endif; ?>>
            <div class="img"><a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><img src="<?php echo (thumb($vo["thumb"])); ?>" alt="<?php echo ($vo["title"]); ?>" title="<?php echo ($vo["title"]); ?>"></a></div>
            <div class="tit"><span class="t1"><?php echo (cut($vo["title"],12)); ?></span><span class="t2"><span class="num"><?php echo ($vo["score"]); ?> </span>积分</span></div>
            <a href="<?php echo U('Index/msg',array('id'=>$vo['id']));?>"><div class="buy">立即兑换</div></a>
        </div><?php endforeach;endif;?>
    </div>
    <div class="footer">
        <div class="box">
            <div class="footer_nav">
                <a href="/jf/">首页</a><span>|</span>
                <a href="<?php echo U('Index/page',array('id'=>9));?>">关于我们</a><span>|</span>
                <a href="<?php echo U('Index/page',array('id'=>11));?>">隐私声明</a><span>|</span>
                <a href="<?php echo U('Index/page',array('id'=>10));?>">法律声明</a><span>|</span>
                <a href="<?php echo U('Index/page',array('id'=>6));?>">会员规则</a>
            </div>
            <div class="copy">
                <?php echo ($block["copy"]); ?>
            </div>
        </div>
    </div>
<script type="text/javascript">
// 兑换滚动
try{ 
    var isStoped = false; 
    var oScroll = document.getElementById("scrollWrap"); 
    with(oScroll){ 
        noWrap = true; 
    } 

    oScroll.onmouseover = new Function('isStoped = true'); 
    oScroll.onmouseout = new Function('isStoped = false'); 

    var preTop = 0; 
    var curTop = 0; 
    var stopTime = 0; 
    var oScrollMsg = document.getElementById("scrollMsg");

    oScroll.appendChild(oScrollMsg.cloneNode(true)); 
    init_srolltext();
    
}catch(e){} 
function init_srolltext(){ 
    oScroll.scrollTop = 0; 
    setInterval('scrollUp()', 15); 
}
function scrollUp(){ 
    if(isStoped)
    return; 
    curTop += 1; 
    if(curTop == 19){ 
        stopTime += 1; 
        curTop -= 1; 
        if(stopTime == 180){ 
            curTop = 0; 
            stopTime = 0; 
        } 
    }else{ 
        preTop = oScroll.scrollTop; 
        oScroll.scrollTop += 1; 
        if(preTop == oScroll.scrollTop){ 
            oScroll.scrollTop = 0; 
            oScroll.scrollTop += 1; 
        } 
    } 
}
</script>
</body>
</html>