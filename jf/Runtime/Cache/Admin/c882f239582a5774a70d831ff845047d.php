<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>单文件选择上传</title>
    <link rel="stylesheet" type="text/css" href="/jf/Public/admin/reset.css">
    <link rel="stylesheet" type="text/css" href="/jf/Public/fonts/fonts.css">
    <script type="text/javascript">var ROOT = "/jf/";var PUBLIC = "/jf/Public/";</script>
    <script type="text/javascript" src="/jf/Public/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/jf/Public/layer/layer.js"></script>
    <script type="text/javascript" src="/jf/Public/ueditor/dialogs/internal.js"></script>
    <script src="/jf/Public/uploadify/uploadify.js"></script>
    <link rel="stylesheet" href="/jf/Public/uploadify/uploadify.css" />
    <style>
        .up_form{padding:5px 10px;background-color: #dde4e6;height:30px;overflow: hidden;clear: both;}
        .img_box{padding:5px 0 5px 3px;width: 676px;height: 336px;overflow: hidden;clear: both;border-bottom: 1px solid #ccc;}
        .img_box .img_list{width: 100px;height: 100px;line-height: 100px;text-align: center;border:1px solid #ccc;float: left;margin:5px;float: left;overflow: hidden;position: relative;}
        .img_box .img_list img{max-width: 100px;max-height: 100px;cursor: pointer;}
        .img_box .img_list .tit{width: 100px;height: 20px;font-size: 12px;background-color:#000;opacity: 0.6;text-align: center;position: absolute;left: 0;bottom:0;color:#fff;line-height: 20px;}
        .paging{width:670px;height: 35px;margin-left:10px;text-align: right;clear: both;line-height:35px;}
        .paging a,.paging span{padding:2px 8px;margin-right: 5px;background-color:#1DCCAA;color:#fff;border-radius:3px;}
        .paging .current{background-color:#999;color:#fff;}
    </style>
</head>
<body>
	<div class="up_form">
        <form>
        <input id="file_upload" name="file_upload" type="file" multiple="true">
        </form>
    </div>
    <div class="img_box">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="img_list"><img onclick="chose('<?php echo ($vo["name"]); ?>','<?php echo ($vo["title"]); ?>')" src="/jf/Uploads/th_<?php echo ($vo["name"]); ?>"><div class="tit"><?php echo (cut($vo["title"],8)); ?></div></div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="paging"><?php echo ($paging); ?></div>
<script type="text/javascript">
  //上传配置
  $(function() {
    $('#file_upload').uploadify({
        'formData': {
            'session': '<?php echo session_id(); ?>',
            'att':'<?php echo ($img_size); ?>'
        },
        'swf'      : '/jf/Public/uploadify/uploadify.swf',
        'uploader' : '<?php echo U('Uploads/index');?>',
        'fileSizeLimit' : "1MB",
        'fileTypeExts' : "*.gif; *.jpg; *.png",
        'onUploadSuccess' : function(file, data, response) {
            var obj = jQuery.parseJSON(data);                   
            var dataObj=eval(obj); 
            if (dataObj.error) {
                alert(dataObj.message);
            }
        },
        'onQueueComplete' : function(queueData) {
            self.location.reload();
        }
    });
  });

function chose(src,title){
    var value = "<img src='/jf/Uploads/"+src+"' alt='"+title+"'>";
    editor.execCommand('insertHtml', value);
    dialog.close(true);
}
</script>
</body>
</html>