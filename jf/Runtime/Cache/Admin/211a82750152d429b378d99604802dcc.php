<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta name="renderer" content="webkit">
    <meta charset="utf-8" />
    <title><?php echo C('COPYRIGHT');?>内容管理系统</title>
    <link rel="stylesheet" type="text/css" href="/jf/Public/admin/reset.css">
    <link rel="stylesheet" type="text/css" href="/jf/Public/fonts/fonts.css">
    <script type="text/javascript">var ROOT = "/jf/";var PUBLIC = "/jf/Public/";</script>
    <script type="text/javascript" src="/jf/Public/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/jf/Public/layer/layer.js"></script>
    <script type="text/javascript" src="/jf/Public/layer/extend/layer.ext.js"></script>
    <script type="text/javascript" src="/jf/Public/Validform/Validform.js"></script>
    <link rel="stylesheet" type="text/css" href="/jf/Public/Validform/Validform.css">
    <script type="text/javascript" src="/jf/Public/admin/comm.js"></script>
    <link rel="stylesheet" type="text/css" href="/jf/Public/admin/style.css">
    
    <!--[if lt IE 9]>
      <script src="/jf/Public/admin/html5shiv.min.js"></script>
    <![endif]-->
    
</head>
<body>
    <!-- topnav -->
    <div id="bdtopnav">
        <a href="<?php echo U('Index/index');?>"><div class="logo">　</div></a>
        <div class="topmenu">
            <a href="<?php echo U('Index/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Index"))): ?>class="active"<?php endif; ?>><i class="icon-home"></i> 首页</a>
            <?php if(authcheck('Category/index',$uid)): ?><a href="<?php echo U('Category/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Category"))): ?>class="active"<?php endif; ?>>分类</a><?php endif; ?>
            <?php if(authcheck('Document/index',$uid)): ?><a href="<?php echo U('Document/index',array('mid'=>2));?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Document,Onepage,Block"))): ?>class="active"<?php endif; ?>>内容</a><?php endif; ?>
            <?php if(authcheck('Bussiness/index',$uid)): ?><a href="<?php echo U('Bussiness/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Business"))): ?>class="active"<?php endif; ?>>商家</a><?php endif; ?>
            <?php if(authcheck('Member/index',$uid)): ?><a href="<?php echo U('Member/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Member,Log"))): ?>class="active"<?php endif; ?>>会员</a><?php endif; ?>
            <?php if(authcheck('Order/index',$uid)): ?><a href="<?php echo U('Order/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Order"))): ?>class="active"<?php endif; ?>>订单</a><?php endif; ?>
            <?php if(authcheck('Model/index',$uid)): ?><a href="<?php echo U('Model/index');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Model,Field"))): ?>class="active"<?php endif; ?>>模型</a><?php endif; ?>
            <a href="<?php echo U('System/setting');?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"System,Admin,Dbbak,Auth"))): ?>class="active"<?php endif; ?>>系统</a>
            <span class="welcome">
                <i style="font-size:14px;" class="icon-user"></i> 欢迎<?php echo session('uname');?> <i class="icon-caret-down"></i>
                <div class="son_nav">
                    <a target="_blank" href="/jf/"><i class="icon-home"></i> 前台首页</a>
                    <a href="<?php echo U('Admin/modpass');?>"><i class="icon-key"></i> 修改密码</a>
                    <a href="<?php echo U('Admin/modinfo');?>"><i class="icon-newspaper"></i> 个人资料</a>
                    <a onclick="ajaxjump('<?php echo U('Index/ClearCache');?>')" href="javascript:;"><i class="icon-trash-o"></i> 清理缓存</a>
                    <a onclick="ajaxjump('<?php echo U('Login/logout');?>')" href="javascript:;"><i class="icon-power-off"></i> 退出系统</a>
                </div>
            </span>
        </div>
    </div><!-- /topnav -->
    <!-- bdleft -->
    <div id="bdleft">
        <ul>
            <li class="item">
                <div class="menu"><i class="icon-cart-plus"></i>订单管理<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('Order/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 最新订单</div></a>
                    <a href="<?php echo U('Order/old');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 已发货订单</div></a>
                </div>
            </li>
        </ul>
    </div><!-- /bdleft -->
    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Order/index');?>" class="active"><i class="icon-th-large"></i> 最新订单</a></div>
        <div class="content">
            <div class="search">
                <i class="icon-search"></i>
                <form action="<?php echo U('Order/search');?>" method="post" accept-charset="utf-8">
                <input type="text" placeholder=" 输入订单号搜索" name="keyword" class="input-text radius le4">
                <input class="btn btn-primary le1 radius" type="submit" value="搜索">
                </form>
            </div>
            <table class="table table-border table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>订单号</th>
                        <th>姓名</th>
                        <th>手机</th>
                        <th>兑换商品</th>
                        <th>邮寄地址</th>
                        <th>兑换时间</th>
                        <!-- <th>快递</th>
                        <th>快递单号</th> -->
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <th><?php echo ($vo["ord_num"]); ?></th>
                        <td><a title="点击查看用户信息" href="<?php echo U('Member/info',array('id'=>$vo['mbid']));?>"><?php echo (r_mb_name($vo["mbid"])); ?></a></td>
                        <td><?php echo (r_mb_phone($vo["mbid"])); ?></td>
                        <td>
                            <a title="点击查看该商品详细" href="/jf/index.php/Index/msg/id/<?php echo ($vo["ptid"]); ?>" target="_blank"><?php echo (cutstr(r_pt_title($vo["ptid"]),20)); ?></a><span class="red">　×<?php echo ($vo["num"]); ?></span>
                        </td>
                        <td><?php echo (r_mb_address($vo["mbid"])); ?></td>
                        <td><?php echo (date("y.m.d",$vo["order_date"])); ?></td>
                        <td>
                            <a href="<?php echo U('Order/mod',array('id'=>$vo['id']));?>" class="btn btn-success radius size-S">发货</a>
                            <a onclick="confirm('<?php echo U('Order/back',array('id'=>$vo['id']));?>','确定要退单吗？退单后，对应的积分将返还给用户。')" class="btn btn-danger radius size-S" value="退单">退单</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="20" class="text-r">
                            <form action="<?php echo U('Order/export');?>" accept-charset="utf-8" method="get">
                                <input type="submit" class="btn btn-danger radius size-S" value="导出表格">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>