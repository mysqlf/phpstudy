<?php
	require_once 'api.php';
	$api_host = "192.168.2.19";		// 请设置成实际的rmiserver host
	$webmail_host = "192.168.2.19";	// 请设置成实际的webmail server host
	$webmail_url = "http://" . $webmail_host . "/coremail/XT3/index.jsp?sid="; // 登陆成功后的url
	#http://mail.chitone.com.cn/coremail/XT3/index.jsp?sid=
	$username = '';
	$password = '';
	$err_msg = '';
	$destUrl = "";
    if ($_POST) {
        // 获取请求中的用户名和密码
        if (array_key_exists('username', $_POST)) {
            $username = $_POST['username'];
        }
        if (array_key_exists('password', $_POST)) {
            $password = $_POST['password'];
        }
        if (empty($username) || strlen($username)==0 ||
            empty($password) || strlen($password)==0 ) {
            $err_msg = 'username or passwd is empty';
        }
        else {
            $api = new CoremailAPI;
            $api->open($api_host, 6195);
            $api->checkPass($username, $password);
            if ($api->getErrorCode()!=0) {
                $errcode = $api->getErrorCode();
                if (19 == $errcode || 20 == $errcode) {
                    $err_msg = "用户名不存在";
                }
                else if (21 == $errcode) {
                    $err_msg = "网络错误, 请重试";
                }
                else if (35 == $errcode) {
                    $err_msg = "密码错误";
                }
                else if (51 == $errcode) {
                    $err_msg = "用户已锁定或者已过期";
                }
                else {
                    $err_msg = $api->getResult() . ':' . $api->getErrorString();
                }

                $api->close();
            }
            else {
                $api->userLogin($username, "");
                if ($api->getErrorCode()!=0) {
                    $err_msg = $api->getResult() . ':' . $api->getErrorString();
                    $api->close();
                }
                else {
                    $sid = $api->getResult();
                    $api->close();
                    $destUrl = $webmail_url . $sid;
                    header("Location: " . $destUrl);
                }
            }
        }
    }

?>

<html>
	<head><title> php login demo</title></head>
	<body>

	<div id="msgZone" style="color:blue"><?php echo $err_msg; ?></div>

<form name="loginForm" method="post" 
	action="http://lzyx.com/javawebservice/php/apilogin.php">		
  <table>
    <tr>
      <td>user name:</td>
      <td>
      	<input type="text" name="username" value="<?php echo $username ?>" >
      </td>
    </tr>
    <tr>
      <td>password:</td>
      <td>
      	<input type="text" name="password" value="" />
      </td>
    </tr>
    <tr>
      <td colspan="2">
      	<input type="submit" value="Login" />
      </td>
    </tr>    
  </table>
</form>  
  
	</body>
</html>