<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form action="edit" method="post">
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
标题:<input type="text" name="title" value="<?php echo ($value["title"]); ?>">
</body>
</html>
<br/>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
时间:<input type="text" name="time" value="<?php echo ($value["time"]); ?>">
</body>
</html>

<!-- <?php if(is_array($views)): $i = 0; $__LIST__ = $views;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo (include($vo)); endforeach; endif; else: echo "" ;endif; ?> -->
<input type="submit" name="">
</form>
</body>
</html>