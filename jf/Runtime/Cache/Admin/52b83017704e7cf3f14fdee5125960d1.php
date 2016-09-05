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
                <div class="menu"><i class="icon-gears"></i>系统设置<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('System/setting');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 前台设置</div></a>
                </div>
            </li>
        </ul>
        <ul>
            <li class="item">
                <div class="menu"><i class="icon-male"></i>管理员<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <?php if(authcheck('Admin/index',$uid)): ?><a href="<?php echo U('Admin/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 管理员列表</div></a><?php endif; ?>
                    <?php if(authcheck('Auth/role',$uid)): ?><a href="<?php echo U('Auth/role');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 角色分组</div></a><?php endif; ?>
                    <?php if(authcheck('Auth/rule',$uid)): ?><a href="<?php echo U('Auth/rule');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 权限列表</div></a><?php endif; ?>
                    <a href="<?php echo U('Admin/modpass');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 修改密码</div></a>
                    <a href="<?php echo U('Admin/modinfo');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 个人资料</div></a>
                </div>
            </li>
        </ul>
        <ul>
            <li class="item">
                <div class="menu"><i class="icon-cloud"></i>数据库<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('dbbak/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 数据表列表</div></a>
                    <a href="<?php echo U('dbbak/file');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 备份文件列表</div></a>
                </div>
            </li>
        </ul>
    </div><!-- /bdleft -->
    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Admin/index');?>" class="active"><i class="icon-th-large"></i> 管理列表</a><a href="<?php echo U('Admin/add');?>"><i class="icon-user-plus"></i> 添加管理员</a></div>
        <div class="content">
            <table class="table table-border table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width="20px">ID</th>
                        <th width="120px">用户名</th>
                        <th width="240px">手机号码</th>
                        <th class="text-c" width="40px">状态</th>
                        <th width="180px">最后登录时间</th>
                        <th  width="120px">最后登录IP</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["uid"]) != "1"): ?><tr>
                        <th><?php echo ($vo["uid"]); ?></th>
                        <th><?php echo ($vo["username"]); ?></th>
                        <td><?php echo ($vo["phone"]); ?></td>
                        <td class="text-c">
                            <?php if(($vo["status"]) == "1"): ?><a onclick="ajaxjump('<?php echo U('Admin/isno_status',array('id'=>$vo['uid'],'s'=>0));?>')" href="javascript:;"><img src="/jf/Public/admin//ico_green.png" title="点击禁用"></a>
                            <?php else: ?>
                            <a onclick="ajaxjump('<?php echo U('Admin/isno_status',array('id'=>$vo['uid'],'s'=>1));?>')" href="javascript:;"><img src="/jf/Public/admin//ico_red.png" title="点击启用"></a><?php endif; ?>
                        </td>
                        <td><?php echo (date("Y.m.d  h:i:s a",$vo["last_login_time"])); ?></td>
                        <td><?php echo ($vo["last_login_ip"]); ?></td>
                        <td>
                            <a href="<?php echo U('Admin/mod',array('id'=>$vo['uid']));?>" class="btn btn-success radius size-S">修改</a>
                            <input onclick="confirm('<?php echo U('Admin/del',array('id'=>$vo['uid']));?>','确定要删除吗？')" class="btn btn-danger radius size-S" type="button" value="删除"></td>
                    </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>