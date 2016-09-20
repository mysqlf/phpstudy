<?php
// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}
//html代码输出
function html($str){
    if(function_exists('htmlspecialchars_decode'))
        $str=htmlspecialchars_decode($str);
    else
        $str=html_entity_decode($str);

    $str = stripslashes($str);
    return $str;
}
//获取单个图片地址
function img($id){
    if ($id) {
        $data=M('Uploads')->find($id);
        return __ROOT__."/Uploads/".$data['name'];
    }else{
        return "图片不存在";
    }
}
//获取单个图片地址
function imgs($ids,$tit=""){
    if ($ids) {
        $str=substr($ids,0,-1);
        $map['id']  = array('in',$str);
        $data=M('Uploads')->where($map)->select();
        // if ($data) {
        //     foreach ($data as $key => $value) {
        //         $html.="<img src='".__ROOT__."/Uploads/".$value['name']."' alt='".$tit."'>";
        //     }
        // }
        return $data;
    }else{
        return "图片不存在";
    }
}
//获取单个图片缩略图地址
function thumb($id){
    if ($id) {
        $data=M('Uploads')->find($id);
        return __ROOT__."/Uploads/th_".$data['name'];
    }else{
        return "图片不存在";
    }
}
/**
+----------------------------------------------------------
 * 生成随机字符串
+----------------------------------------------------------
 * @param int       $length  要生成的随机字符串长度
 * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function randCode($length = 5, $type = 0) {
    $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
    if ($type == 0) {
        array_pop($arr);
        $string = implode("", $arr);
    } elseif ($type == "-1") {
        $string = implode("", $arr);
    } else {
        $string = $arr[$type];
    }
    $count = strlen($string) - 1;
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $string[rand(0, $count)];
    }
    return $code;
}
//判断访问客户端
function ismobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}
// 字符串分割,两个英文字母代表一个汉字
// string，传入的字符串内容
// length，截取长度
// dot，补截（如省略号等）
// html，是否保留HTML样式
function cut($string,$length=0,$dot='',$html=false)
{
    if(!$length || !trim($string)){
        return $string;
    }
    $str = $string;
    $string = strip_tags(trim($string));
    $string = str_replace("&nbsp;"," ",$string);
    if(strlen($string) <= $length){
        return $html ? $str : $string;
    }
    $info = _substr($string,$length,$dot);
    if(!$html){
        return $info;
    }
    //组成HTML样式
    $starts = $ends = $starts_str = false;
    preg_match_all('/<\w+[^>]*>/isU',$str,$starts,PREG_OFFSET_CAPTURE);
    preg_match_all('/<\/\w+>/isU',$str,$ends,PREG_OFFSET_CAPTURE);
    if(!$starts || ($starts && !$starts[0])){
        return str_replace(" ","&nbsp;",$info);
    }
    $lst = $use = false;
    foreach($starts[0] as $key=>$value){
        if($value[1] >= $length){
            break;
        }
        $info = substr($info,0,$value[1]).$value[0].substr($info,$value[1]);
        $length += strlen($value[0]);
        if($ends && $ends[0][$key]){
            $chk = str_replace(array('/','>'),'',$ends[0][$key][0]);
            if(substr($value[0],0,strlen($chk)) == $chk){
                $info = substr($info,0,$ends[0][$key][1]).$ends[0][$key][0].substr($info,$ends[0][$key][1]);
                $length += strlen($ends[0][$key][0]);
                $use[$key] = $ends[0][$key];
            }else{
                $lst[] = $value[0];
            }
        }else{
            $lst[] = $value[0];
        }
    }
    if($ends && $lst){
        foreach($ends[0] as $key=>$value){
            if($use && $use[$key]){
                continue;
            }
            $chk = str_replace(array('/','>'),'',$value[0]);
            foreach($lst as $k=>$v){
                if(substr($v,0,strlen($chk)) == $chk){
                    $info = substr($info,0,$value[1]).$value[0].substr($info,$value[1]);
                    $length += strlen($value[0]);
                    $use[$key] = $value;
                    unset($lst[$k]);
                }
            }
        }
    }
    return $info;
}
function _substr($sourcestr,$cutlength=255,$dot=''){
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr);
    $mb_str_length = mb_strlen($sourcestr,'utf-8');
    while(($n < $cutlength) && ($i <= $str_length)){
        $temp_str = substr($sourcestr,$i,1);
        $ascnum = ord($temp_str);
        if($ascnum >= 224){
            $returnstr = $returnstr.substr($sourcestr,$i,3);
            $i = $i + 3;
            $n++;
        }elseif($ascnum >= 192){
            $returnstr = $returnstr.substr($sourcestr,$i,2);
            $i = $i + 2;
            $n++;
        }elseif(($ascnum >= 65) && ($ascnum <= 90)){
            $returnstr = $returnstr.substr($sourcestr,$i,1);
            $i = $i + 1;
            $n = $n + 0.5;
        }else{
            $returnstr = $returnstr.substr($sourcestr,$i,1);
            $i = $i + 1;
            $n = $n + 0.5;
        }
    }
    if ($mb_str_length > $cutlength){
        $returnstr = $returnstr . $dot;
    }
    return $returnstr;
}
// 浏览器友好的变量输出
function p(){
    header("Content-type: text/html; charset=utf-8");
    $args=func_get_args();  //获取多个参数
    if(count($args)<1){
        return;
    }
    echo '<div style="width:100%;text-align:left"><pre>';
    //多个参数循环输出
    foreach($args as $arg){
        if(is_array($arg)){
            print_r($arg);
            echo '<br>';
        }else if(is_string($arg)){
            echo $arg.'<br>';
        }else{
            var_dump($arg);
            echo '<br>';
        }
    }
    echo '</pre></div>';
    exit;
}
/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * @return string
 */
function encrypt($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    $str = sprintf('%010d', $expire ? $expire + time():0);

    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}

/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * @return string
 */
function decrypt($data, $key = ''){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);

    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';

    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }

    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
/**
 * 系统发送邮件方法
 * @param  string $mailto 发送给谁
 * @param  string $title  邮件主题
 * @return string $body   邮件内容
 */
function email($mailto,$title,$body){
    $sys=M('System')->find();
    $smtpserver = $sys['smtp'];//SMTP服务器
    $smtpserverport =25;//SMTP服务器端口
    $smtpusermail = $sys['smtp_username'];//SMTP服务器的用户邮箱
    $smtpuser = $sys['smtp_username'];//SMTP服务器的用户帐号
    $smtppass = decrypt($sys['smtp_password']);//SMTP服务器的用户密码
    $Email = new \Vendor\Email();
    $Email->config($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
    $Email->debug = FALSE;//是否显示发送的调试信息
    $re=$Email->sendmail($mailto, $title,$body,$smtpuser); //发送邮件
    return $re;
}
/**
 * 删除目录和目录下的文件
 * @param  string $dir 目录名称
 */
function deldir($dir) {
    //先删除目录下的文件：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
            $fullpath=$dir."/".$file;
            if(!is_dir($fullpath)) {
            unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹：
    if(rmdir($dir)) {
        return true;
    } else {
        return false;
    }
}
//时间戳转换
function date2($time,$type,$str="-"){
    if ($time) {
        if($type){
            return date("Y".$str."m".$str."d"." H:i:s",$time);
        }else{
            return date("Y".$str."m".$str."d",$time);
        }
    }else{
        return "";
    }
}
//获取订单状态
function checkorderstatus($ordid){
    $Ord=M('Orderlist');
    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
    if($ordstatus==1){
        return true;
    }else{
        return false;
    }
}

//处理订单函数
//更新订单状态，写入订单支付后返回的数据
function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']      =$parameter['trade_no'];
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email'];
    $data['ordstatus']             =1;
    $Ord=M('Orderlist');
    $Ord->where('ordid='.$ordid)->save($data);
}
//获取一个随机且唯一的订单号；
function getordcode(){
    $Ord=M('Orderlist');
    $numbers = range (10,99);
    shuffle ($numbers);
    $code=array_slice($numbers,0,4);
    $ordcode=$code[0].$code[1].$code[2].$code[3];
    $oldcode=$Ord->where("ordcode='".$ordcode."'")->getField('ordcode');
    if($oldcode){
        getordcode();
    }else{
        return $ordcode;
    }
}
//操作用户增加或减少积分,$type默认增加，0减少
function mb_score($mbid,$score,$type=1){
    if(empty($mbid) || empty($score)){
        return false;
    }
    $Member=M('Member');
    if($type==1){
        $re=$Member->where(array('id'=>$mbid))->setInc('score',$score); // 用户的积分增加
    }else{
        $re=$Member->where(array('id'=>$mbid))->setDec('score',$score); // 用户的积分减少
    }
    return $re;
}
//子类
function is_scate($pid){
    $re=M('Category')->where(array('pid'=>$pid))->find();
    return $re ? true : 0;
}
//返回产品名称
function r_p_tit($id){
    $re=M('Doc_product')->where(array('id'=>$id))->find();
    return $re ? $re['title'] : '产品名称不存在';
}
//返回产品图片
function r_p_thumb($id){
    $re=M('Doc_product')->where(array('id'=>$id))->find();
    return $re ? thumb($re['thumb']) : '产品图片不存在';
}
//返回产品积分
function r_p_score($id){
    $re=M('Doc_product')->where(array('id'=>$id))->find();
    return $re ? $re['score'] : '积分不存在';
}
//生成唯一的订单号
function build_order_no(){
    return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
function r_mb_phone($id){
    $re=M('Member')->find($id);
    return $re ? $re['phone'] : '';
}
function r_mb_name($id){
    $re=M('Member')->find($id);
    return $re ? $re['name'] : '';
}
//返回分类名称
function r_catename($id){
    $re=M('Category')->find($id);
    return $re ? $re['name'] : '';
}
//手机号码中间四位用星号（*）隐藏
function hidtel($phone){
    $IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$phone); //固定电话
    if($IsWhat == 1){
        return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$phone);
    }else{
        return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
    }
}
function mdate($time = NULL) {
    $text = '';
    $time = $time === NULL || $time > time() ? time() : intval($time);
    $t = time() - $time; //时间差 （秒）
    $y = date('Y', $time)-date('Y', time());//是否跨年
    switch($t){
        case $t == 0:
            $text = '刚刚';
            break;
        case $t < 60:
            $text = $t . '秒前'; // 一分钟内
            break;
        case $t < 60 * 60:
            $text = floor($t / 60) . '分钟前'; //一小时内
            break;
        case $t < 60 * 60 * 24:
            $text = floor($t / (60 * 60)) . '小时前'; // 一天内
            break;
        case $t < 60 * 60 * 24 * 3:
            $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
            break;
        case $t < 60 * 60 * 24 * 30:
            $text = date('m月d日 H:i', $time); //一个月内
            break;
        case $t < 60 * 60 * 24 * 365&&$y==0:
            $text = date('m月d日', $time); //一年内
            break;
        default:
            $text = date('Y年m月d日', $time); //一年以前
            break;
    }

    return $text;
}
/***********************
 **功能:将多维数组合并为一位数组
 **$array:需要合并的数组
 **$clearRepeated:是否清除并后的数组中得重复值
 ***********************/
function array_multiToSingle($array,$clearRepeated=false){
    if(!isset($array)||!is_array($array)||empty($array)){
        return false;
    }
    if(!in_array($clearRepeated,array('true','false',''))){
        return false;
    }
    static $result_array=array();
    foreach($array as $value){
        if(is_array($value)){
            array_multiToSingle($value);
        }else{
            $result_array[]=$value;
        }
    }
    if($clearRepeated){
        $result_array=array_unique($result_array);
    }
}
//操作会员积分
function set_member_score($mid,$score,$type=1){
    $Member=M('Member');
    if(empty($mid) || empty($score) || empty($type)){
        return false;
    }
    $map['id']=$mid;
    if($type==1){
        $re=$Member->where($map)->setInc('score',$score);
    }else{
        $re=$Member->where($map)->setDec('score',$score);
    }
    return $re;
}
//操作会员积分日志
function set_member_score_log($mid,$score,$type=1,$note){
    $mbinfo=M('Member')->where(array('id'=>$mid))->find();
    if(empty($mbinfo)){
        return false;
    }
    $Score_log=M('Score_log');
    $data['member_id']=$mid;
    $data['member_name']=$mbinfo['name'];
    $data['member_username']=$mbinfo['username'];
    $data['score']=$score;
    $data['type']=$type;
    $data['note']=$note;
    $data['time']=time();
    $re=$Score_log->add($data);
    return $re;
}
?>