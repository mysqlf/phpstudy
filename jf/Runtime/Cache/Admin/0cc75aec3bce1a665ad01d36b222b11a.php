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

    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Bussiness/index',array('mid'=>$mdata['id']));?>" class="active"><i class="icon-th-large"></i> <?php echo r_model_name($mdata['id']);?>列表</a><a href="<?php echo U('Bussiness/add',array('mid'=>$mdata['id']));?>"><i class="icon-plus"></i> 添加<?php echo r_model_name($mdata['id']);?></a></div>
        <div class="content">
            <?php if(!empty($catelist)): ?><div class="category">
                <div class="menu"><i class="icon-angle-down"></i> 选择分类</div>
                <div class="cate_son_menu">
                    <?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Bussiness/index',array('mid'=>$mdata['id'],'cid'=>$vo['id']));?>"><i class="icon-angle-right"></i> <?php echo ($vo["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div><?php endif; ?>
            <div class="search">
                <i class="icon-search"></i>
                <form action="<?php echo U('Bussiness/search');?>" method="post" accept-charset="utf-8">
                <input type="text" placeholder=" 输入关键词搜索" name="keyword" class="input-text radius le4">
                <input type="hidden" name="mid" value="<?php echo ($mdata["id"]); ?>">
                <?php if(!empty($cid)): ?><input type="hidden" name="cid" value="<?php echo ($cid); ?>"><?php endif; ?>
                <input class="btn btn-primary le1 radius" type="submit" value="搜索">
                </form>
            </div>
            <div class="cl"></div>
            <table class="table table-border table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <td class="text-c" width="20px"><i style="cursor:pointer;font-size: 16px;" onClick="CheckAll()" class="icon-check-square-o"></i></td>
                        <?php if($data[0]['cid']){echo "<td>类别</td>";} ?>
                        <th style="min-width:400px;">标题</th>
                        <th  width="120px">更新时间</th>
                        <th width="150px">操作</th>
                    </tr>
                </thead>
                <form id="form1" action="<?php echo U('Bussiness/delmore');?>" method="post" accept-charset="utf-8">
                <input name="mid" type="hidden" value="<?php echo ($mdata["id"]); ?>">
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td class="text-c"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>"></td>
                        <?php if(isset($vo["cid"])): ?><td><?php echo r_category_name($vo['cid']);?></td><?php endif; ?>
                        <td><?php echo ($vo["title"]); if(($vo["roll"]) == "1"): ?><span style="color:red;"> [滚动]</span><?php endif; ?></td>
                        <td><?php echo (date("Y-m-d",$vo["update_time"])); ?></td>
                        <td><a href="<?php echo U('Bussiness/mod',array('id'=>$vo['id'],'mid'=>$mdata['id']));?>" class="btn btn-success radius size-S" value="修改">修改</a>　<input onclick="confirm('<?php echo U('Bussiness/del',array('id'=>$vo['id'],'mid'=>$mdata['id']));?>','确定要删除吗？')" class="btn btn-danger radius size-S" type="button" value="删除"></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    <tr>
                        <td colspan="10">
                            <a href="javascript:;" class="btn btn-secondary radius size-MINI" onClick="CheckAll()">全选/反选</a> 
                            <input id="submit" class="btn btn-warning radius size-S" type="button" value="删除选中">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10"><div class="paging"><?php echo ($paging); ?></div></td>
                    </tr>
                </tbody>
                </form>
            </table>
        </div>
    </div>
<script type="text/javascript">
$('#submit').click(function(){
    layer.confirm('确定要删除选中的内容吗？', {icon: 3}, function(index){
        layer.close(index);
        $("#form1").submit();
    });
});
</script>
</body>
</html>