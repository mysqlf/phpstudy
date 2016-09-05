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
    <!-- bdleft -->
    <div id="bdleft">
        <ul>
            <li class="item">
                <div class="menu"><i class="icon-users"></i>会员管理<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('Member/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 会员列表</div></a>
                </div>
            </li>
            <li class="item">
                <div class="menu"><i class="icon-rmb"></i>日志<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('Log/score_log');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 日志列表</div></a>
                </div>
            </li>
        </ul>
    </div><!-- /bdleft -->
    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Member/index');?>" class="active"><i class="icon-th-large"></i> 会员列表</a><a href="<?php echo U('Member/add');?>"><i class="icon-plus"></i> 添加会员</a><a href="<?php echo U('Member/import');?>"><i class="icon-arrow-circle-o-up"></i> 导入会员</a><a href="<?php echo U('Member/import2');?>"><i class="icon-arrow-circle-o-up"></i> 导入积分</a></div>
        <div class="content">
            <div class="search">
                <i class="icon-search"></i>
                <form action="<?php echo U('Member/search');?>" method="post" accept-charset="utf-8">
                <input type="text" placeholder=" 输入用户名或姓名搜索" name="keyword" class="input-text radius le4">
                <input class="btn btn-primary le1 radius" type="submit" value="搜索">
                </form>
            </div>
            <table class="table table-border table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>用户名</th>
                        <th>积分</th>
                        <th>姓名</th>
                        <th>性别</th>
                        <th>年龄</th>
                        <th>手机号</th>
                        <!--<th>等级</th>-->
                        <th class="text-c">状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <th><?php echo ($vo["username"]); ?></th>
                        <td><strong><?php echo ($vo["score"]); ?></strong></td>
                        <td><?php echo ($vo["name"]); ?></td>
                        <td><?php echo ($vo["sex"]); ?></td>
                        <td><?php echo ($vo["age"]); ?></td>
                        <th><?php echo ($vo["phone"]); ?></th>
                        <!--<th>-->
                            <!--<?php switch($vo["level"]): case "1": ?>普通<?php break; case "2": ?>银牌<?php break; case "3": ?>金牌<?php break; endswitch;?>-->
                        <!--</th>-->
                        <td class="text-c">
                            <?php if(($vo["status"]) == "1"): ?><a onclick="ajaxjump('<?php echo U('Member/isno_status',array('id'=>$vo['id'],'s'=>0));?>')" href="javascript:;"><img src="/jf/Public/admin//ico_green.png" title="点击禁用"></a>
                            <?php else: ?>
                            <a onclick="ajaxjump('<?php echo U('Member/isno_status',array('id'=>$vo['id'],'s'=>1));?>')" href="javascript:;"><img src="/jf/Public/admin//ico_red.png" title="点击启用"></a><?php endif; ?>
                        </td>
                        <td>
                        	<a href="<?php echo U('Member/info',array('id'=>$vo['id']));?>" class="btn btn-secondary radius size-S">查看详细</a>
                            <a href="<?php echo U('Log/score_make',array('id'=>$vo['id']));?>" class="btn btn-primary radius size-S">增减积分</a>
                            <a href="<?php echo U('Log/score_log',array('id'=>$vo['id']));?>" class="btn btn-success radius size-S">积分日志</a>
                        	<a href="<?php echo U('Member/mod',array('id'=>$vo['id']));?>" class="btn btn-warning radius size-S" value="修改">修改</a>
                            <a onclick="confirm('<?php echo U('Member/del',array('id'=>$vo['id']));?>','确定要删除吗？')" class="btn btn-danger radius size-S" value="删除">删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="10"><div class="paging"><?php echo ($paging); ?></div></td>
                    </tr>
                    <tr>
                        <td colspan="20" class="text-r">
                            <form action="<?php echo U('Member/export');?>" accept-charset="utf-8" method="get">
                                <input type="submit" class="btn btn-danger radius size-S" value="导出会员">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>