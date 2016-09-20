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
        <div class="site"><a class="active"><i class="icon-th-large"></i> 前台设置</a></div>
        <div class="content">
            <form id="form1" action="#" method="post" accept-charset="utf-8">
            <div id="tab_form" class="HuiTab">
                <div class="tabBar cl"><span>基础</span><span>扩展</span></div>
                <!-- 基础 -->
                <div class="tabCon">
                    <input type="hidden" name="id" value="1">
                    <table class="table table-border table-bordered table-hover radius">
                        <tr>
                            <th width="75px">前台标题</th>
                            <td><input type="text" name="title" class="input-text radius le4" value="<?php echo ($data["title"]); ?>"><span class="notic">（SEO重要参数，显示在浏览器页面选项卡上）</span></td>
                        </tr>
                        <tr>
                            <th width="65px">前台关键词</th>
                            <td><input type="text" name="keywords" class="input-text radius le4" value="<?php echo ($data["keywords"]); ?>"><span class="notic">（SEO优化重要参数，用几个词语来形容网站）</span></td>
                        </tr>
                        <tr>
                            <th width="65px">网站描述</th>
                            <td><textarea name="description" rows="4" class="textarea radius le4"><?php echo ($data["description"]); ?></textarea><span class="notic">（SEO重要参数，简单描述网站的主题）</span></td>
                        </tr>
                    </table>
                </div>
                 <!-- 扩展 -->
                <div class="tabCon">
                    <table class="table table-border table-bordered table-hover radius">
                        <tr>
                            <th width="100px">签到开关</th>
                            <td>
                                <label><input name="signed_onoff" type="radio" value="1" <?php if(($data["signed_onoff"]) == "1"): ?>checked<?php endif; ?>/>开启 </label>
                                <label><input name="signed_onoff" type="radio" value="0"  <?php if(($data["signed_onoff"]) == "0"): ?>checked<?php endif; ?>/>关闭 </label>
                            </td>
                        </tr>
                        <tr>
                            <th width="100px">连续签到天数</th>
                            <td><input type="number" name="signed" class="input-text radius le4" value="<?php echo ($data["signed"]); ?>"  datatype="n1-30" nullmsg="签到天数" errormsg="签到天数在1~10天之间"><span class="notic">（隔一天以上签到，将从新计算）</span></td>
                        </tr>
                        <tr>
                            <th width="100px">签到合格奖励积分</th>
                            <td><input type="number" name="signed_score" class="input-text radius le4" value="<?php echo ($data["signed_score"]); ?>"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="submit_btn"><input class="btn btn-primary le1 radius" type="submit" value="提交"></div>
            </form>
        </div>
    </div>
</body>
</html>