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
                <div class="menu"><i class="icon-folder-open"></i>内容管理<b><i class='icon-angle-right'></i></b></div>
                <div class="info">
                    <!-- <a href="<?php echo U('Onepage/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 单页面管理</div></a> -->
                    <?php if(is_array($model_menu)): $i = 0; $__LIST__ = $model_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Document/index',array('mid'=>$vo['id']));?>"><div class="sonmenu"><i class="icon-caret-right"></i> <?php echo ($vo["title"]); ?>管理</div></a><?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="<?php echo U('Block/index');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 碎片管理</div></a>
                    <a href="<?php echo U('Block/file');?>"><div class="sonmenu"><i class="icon-caret-right"></i> 附件管理</div></a>
                </div>
            </li>
        </ul>
    </div><!-- /bdleft -->
    <!-- bdright -->
    <div id="bdright">
        <div class="site"><a href="<?php echo U('Document/index',array('mid'=>$mdata['id']));?>"><i class="icon-th-large"></i> <?php echo r_model_name($mdata['id']);?>列表</a><a class="active" href="<?php echo U('Document/add',array('mid'=>$mdata['id']));?>"><i class="icon-plus"></i> 添加<?php echo r_model_name($mdata['id']);?></a></div>
        <div class="content">
            <form id="form1" action="#" method="post" accept-charset="utf-8">
            <div id="tab_form" class="HuiTab">
                <div class="tabBar cl"><span>添加<?php echo r_model_name($mdata['id']);?></span></div>
                <!-- 基础 -->
                <div class="tabCon">
                    <table class="table table-border table-bordered table-hover radius">
                        <?php if(is_array($fdata)): $i = 0; $__LIST__ = $fdata;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; switch($vo["type"]): case "1": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <input type="text" name="<?php echo ($vo["name"]); ?>" class="input-text radius le4" <?php if(empty($vo["regular"])): ?>datatype="n"<?php else: ?>datatype="<?php echo ($vo["regular"]); ?>"<?php endif; ?> <?php if(($vo["is_must"]) == "1"): ?>nullmsg="<?php echo ($vo["title"]); ?>必须填写"<?php else: ?>ignore="ignore"<?php endif; ?> errormsg="<?php echo ($vo["note"]); ?>" value="<?php echo ($vo["value"]); ?>"><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "2": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <input type="text" name="<?php echo ($vo["name"]); ?>" class="input-text radius le4" <?php if(empty($vo["regular"])): ?>datatype="*"<?php else: ?>datatype="<?php echo ($vo["regular"]); ?>"<?php endif; ?> <?php if(($vo["is_must"]) == "1"): ?>nullmsg="<?php echo ($vo["title"]); ?>必须填写"<?php else: ?>ignore="ignore"<?php endif; ?> errormsg="<?php echo ($vo["note"]); ?>" value="<?php echo ($vo["value"]); ?>"><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "3": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <textarea <?php if(empty($vo["att"])): ?>style="height:90px;"<?php else: ?>style="height:<?php echo ($vo["att"]); ?>px;"<?php endif; ?> name="<?php echo ($vo["name"]); ?>" class="textarea radius le4" <?php if(empty($vo["regular"])): ?>datatype="*"<?php else: ?>datatype="<?php echo ($vo["regular"]); ?>"<?php endif; ?> <?php if(($vo["is_must"]) == "1"): ?>nullmsg="<?php echo ($vo["title"]); ?>必须填写"<?php else: ?>ignore="ignore"<?php endif; ?> errormsg="<?php echo ($vo["note"]); ?>"><?php echo ($vo["value"]); ?></textarea><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "4": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_select($vo['name'],0,$vo['att'],$vo['title'],$vo['is_must']);?><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "5": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_ueditor($vo['name'],"",$vo['att']);?>
                                    </td>
                                </tr><?php break;?>
                                <?php case "6": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_upload($vo['name'],$vo['att']);?><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "7": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_uploads($vo['name'],$vo['att']);?><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "8": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td><?php echo form_category($vo['name'],$vo['att']);?><span class="notic"> <?php echo ($vo["note"]); ?></span></td>
                                </tr><?php break;?>
                                <?php case "9": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_date($vo['name'],$vo['title'],$vo['is_must']);?><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "10": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_radio($vo['name'],$vo['value'],$vo['att'],$vo['title'],$vo['is_must']);?><span class="notic"> <?php echo ($vo["note"]); ?></span>
                                    </td>
                                </tr><?php break;?>
                                <?php case "11": ?><tr>
                                    <th width="80px"><?php echo ($vo["title"]); ?></th>
                                    <td>
                                        <?php echo form_ueditor2($vo['name'],"",$vo['att']);?>
                                    </td>
                                </tr><?php break; endswitch; endforeach; endif; else: echo "" ;endif; ?>
                    </table>
                </div>
            </div>
            <div class="submit_btn"><input class="btn btn-primary le1 radius" type="submit" value="提交"></div>
            </form>
        </div>
    </div>
</body>
</html>