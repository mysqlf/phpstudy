<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <meta name="format-detection" content="telephone=no" >
  <title>测试定制服务</title>
  <link rel="stylesheet" href="Assets/Home/styles/screen.css">
</head>
<body class="login">
	   <i class="icon-logo"></i>
	   <div id="login_box">
	        <form action="<?php echo U('Homemaking-confirmcustommade','','');?>" method="post">
		        <div class="relative_box">
		       	 <label for="tel_email">手机号：</label><input type="text" id="tel" class="username_text" name="tel" placeholder="手机" required maxlength="11">
		        </div>
		        <?php if(($type == 3)): ?><div class="relative_box">
			       <input type="radio" name="housekeeper" value="0" checked />单品 <input type="radio" name="housekeeper" value="1"/>套餐
			    </div>
			    <?php else: ?>
			    <div class="relative_box">
			      <label for="password">时间：</label> <input type="text" id="promisetime" class="pw_text" name="promisetime" placeholder="时间" required>
			    </div><?php endif; ?>
			    <div class="relative_box">
		       	 <label for="tel_email">要求：</label><input type="text" id="account" class="username_text" name="claim" placeholder="要求" required maxlength="11" >
		        </div> 
		        <!-- <a href="tel:18295715690">拨号</a> -->
		        <input type="hidden" name="type" id="type" value="<?php echo ($type); ?>">
		   	   <input type="submit" value="提交" class="commen_btn" >
	   	   </form>
	      <!--  <span class="login_other fr">还没有账号？<a href="Account-index?redirect=<?php echo ($redirect); ?>" class="register">立即注册</a></span> -->
	       <div class="clear"></div>
	   </div>
 <script src="/Assets/Home/scripts/lib/sea.js"></script>

</body>
</html>