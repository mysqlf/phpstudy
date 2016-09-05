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
                <div class="menu"><i class="icon-tags"></i>分类管理<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <a href="<?php echo U('Category/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 分类列表</div></a>
                </div>
            </li>
        </ul>
    </div><!-- /bdleft -->
    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Category/index');?>" class="active"><i class="icon-th-large"></i> 分类列表</a><?php if(($uid) == "1"): ?><a href="<?php echo U('Category/add',array('id'=>0));?>"><i class="icon-plus"></i> 添加顶级分类</a><?php endif; ?></div>
        <div class="content">
            <table class="table table-border table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-c" width="20px">ID</th>
                        <?php if(authcheck('Model/index',$uid)): ?><th width="80px" class="text-c">模型</th><?php endif; ?>
                        <th>名称</th>
                        <th class="text-c" width="50px">排序</th>
                        <th width="300px">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                        <td class="text-c"><?php echo ($vo["id"]); ?></td>
                        <?php if(authcheck('Model/index',$uid)): ?><td class="text-c"><?php echo r_model_name($vo['mid']);?></td><?php endif; ?>
                        <td><?php echo ($vo["cname"]); ?></td>
                        <td class="text-c">
                            <a onclick="mod_sort(<?php echo ($vo["id"]); ?>)" href="javascript:;"><?php echo ($vo["sort"]); ?></a>
                        </td>
                        <td>
                            <?php if(($vo["pid"] == 0) AND ($vo["mid"] == 2)): ?><a href="<?php echo U('Category/add',array('id'=>$vo['id']));?>" class="btn btn-secondary radius size-S" type="button">添加子类</a>　<?php endif; ?>
                            <a href="<?php echo U('Category/mod',array('id'=>$vo['id']));?>" class="btn btn-success radius size-S" type="button">修改</a>　
                            <?php if(($vo["pid"] != 0) AND ($vo["mid"] == 2)): ?><a onclick="confirm('<?php echo U('Category/del',array('id'=>$vo['id']));?>','确定要删除吗？')" class="btn btn-danger radius size-S" type="button">删除</a><?php endif; ?>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<script>
    var modsorturl = "<?php echo U('Category/sort');?>";
    function mod_sort(id){
        layer.prompt({title: '修改序号'},function(val){
            $.post(modsorturl, {id:id,sort:val},function(data){
                if (data.status==1) {
                    layer.msg(data.info,{icon: 6});
                }else{
                    layer.msg(data.info,{icon: 5});
                    layer.close(index);
                }
            });
            location.reload();
        });
    }
</script>
</body>
</html>