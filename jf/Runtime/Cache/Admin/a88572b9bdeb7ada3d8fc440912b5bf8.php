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
            <?php if(authcheck('Bussiness/index',$uid)): ?><a href="<?php echo U('Bussiness/index',array('mid'=>5));?>" <?php if(in_array((CONTROLLER_NAME), explode(',',"Bussiness,Onepage,Block"))): ?>class="active"<?php endif; ?>>商家</a><?php endif; ?>
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
<style type="text/css">
body{background-color: #f6f6f6;}
</style>
    <!-- bdleft -->
    <!-- bdright -->
    <div id="sysinfo">
        <table class="table table-border table-bordered table-bg radius table-hover">
            <thead>
                    <tr><th width="20%">信息名称</th><th>描述</th></tr>
            </thead>
            <tbody>
                <tr>
                    <th>快捷菜单</th>
                    <td>
                        <a class="btn btn-default radius" target="_blank" href="/jf/">前台首页</a>
                        <a class="btn btn-default radius" href="<?php echo U('Document/index',array('mid'=>2));?>">商品管理</a>
                        <a class="btn btn-default radius" href="<?php echo U('Order/index');?>">订单管理</a>
                        <a class="btn btn-default radius" href="<?php echo U('category/index');?>">分类管理</a>
                        <a class="btn btn-default radius" href="<?php echo U('Member/index');?>">会员管理</a>
                    </td>
                </tr>
                <tr><th>未处理订单</th><td><a class="btn btn-default radius" href="<?php echo U('Order/index');?>">未处理订单 <span class="red"><?php echo ($ordernum); ?></span></a></td></tr>
                <tr><th>文章数</th><td><?php echo ($articlenum); ?></td></tr>
                <tr><th>产品数</th><td><?php echo ($productnum); ?></td></tr>
                <tr><th>版本信息</th><td><a target="_blank" href="<?php echo C('COPYRIGHT_URL');?>"><?php echo C('COPYRIGHT');?> <sup>3.2.3</sup></a></td></tr>
                <tr><th>技术支持</th><td><i class="icon-qq"></i> <?php echo C('COPYRIGHT_QQ');?></td></tr>
            </tbody>
        </table>
    </div>
    <div id="copyright">©2013-2015 <?php echo C('COPYRIGHT');?>版权所有</div>
</body>
</html>