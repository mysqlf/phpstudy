<?php
/**
 * [msg_code 返回消息状态]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-13
 * @param    [type]     $code [description]
 * @return   [type]           [description]
 */
function msg_code($code){
    switch ($code) {
        case '400':
            $message='缺少参数';
            break;
        case '401':
            $message='参数已存在';
            break;
        case '500':
            $message='写入失败';
            break;
        default:
            $message='内部错误';
            break;
    }
    return $message;
}
/**
 * [site_url 链接构造]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $url [链接]
 * @return   [str]          [带域名链接]
 */
function site_url($url=''){
    return DOMAIN_NAME.'/'.$url;
}
/***跳转返回相关函数***/
/**
 * [success 成功跳转]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $message [提示信息]
 * @param    string     $url     [跳转链接]
 * @return   [type]              [description]
 */
function success($message='',$url=''){
    header("Content-type: text/html; charset=utf-8");
        if($url){
            echo '<script>alert("'.$message.'");document.location.href="'.$url.'";</script>';
        }else{
            echo '<script>alert("'.$message.'");history.back();</script>';
        }
        die;
}
/**
 * [error 失败跳转]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $message [提示信息]
 * @param    string     $url     [跳转链接]
 * @return   [type]              [description]
 */
function error($message='',$url=''){
    header("Content-type: text/html; charset=utf-8");
        if($url){
            echo '<script>alert("'.$message.'");document.location.href="'.$url.'";</script>';
        }else{
            echo '<script>alert("'.$message.'");history.back();</script>';
        }
        die;
}
/***session相关函数***/
/**
 * [set_session 设置session]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $name [description]
 * @param    string     $data [description]
 */
function set_session($name='',$data=''){
    if (empty($name)) {
        return false;
    }else{
        return Yaf\Session::getInstance()->set($name,$data);
    }
    
}
/**
 * [get_session 读取session]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $name [description]
 * @return   [type]           [description]
 */
function get_session($name=''){
    if (empty($name)) {
        return false;
    }else{
        return Yaf\Session::getInstance()->get($name);
    }
    
}
/**
 * [del_session 删除session]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-07-12
 * @param    string     $name [description]
 * @return   [type]           [description]
 */
function del_session($name=''){
    if (empty($name)) {
        return false;
    }else{
        return Yaf\Session::getInstance()->del($name);
    }
}

/***安全相关函数***/
/**
 * 安全过滤类-全局变量过滤
 * 在Controller初始化的时候已经运行过该变量，对全局变量进行处理
 * 使用方法：filter()
 * @return
 */
function filter() {
    if (is_array($_SERVER)) {
        foreach ($_SERVER as $k => $v) {
            if (isset($_SERVER[$k])) {
                $_SERVER[$k] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'), '', $v);
            }
        }
    }
    unset($_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);
    filter_slashes($_GET);
    filter_slashes($_POST);
    filter_slashes($_COOKIE);
    filter_slashes($_FILES);
    filter_slashes($_REQUEST);
}

/**
 * 安全过滤类-加反斜杠，放置SQL注入
 * 使用方法：filter_slashes(&$value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function filter_slashes(&$value) {
    if (get_magic_quotes_gpc()) return false; //开启魔术变量
    $value = (array) $value;
    foreach ($value as $key => $val) {
        if (is_array($val)) {
            filter_slashes($value[$key]);
        } else {
            $value[$key] = addslashes($val);
        }
    }
}

/**
 * 安全过滤类-通用数据过滤
 * 使用方法：filter_escape($value)
 * @param string $value 需要过滤的变量
 * @return string|array
 */
function filter_escape($value) {
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = filter_str($v);
        }
    } else {
        $value = filter_str($value);
    }
    return $value;
}

/**
 * 安全过滤类-字符串过滤 过滤特殊有危害字符
 * 使用方法：filter_str($value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function filter_str($value) {
    $value = str_replace(array("\0","%00","\r"), '', $value);
    $value = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $value);
    $value = str_replace(array("%3C",'<'), '&lt;', $value);
    $value = str_replace(array("%3E",'>'), '&gt;', $value);
    $value = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $value);
    return $value;
}

/**
 * 安全过滤类-过滤javascript,css,iframes,object等不安全参数 过滤级别高
 * 使用方法：filter_script($value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function filter_script($value) {
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = filter_script($v);
        }
        return $value;
    } else {
        $parten = array(
            "/(javascript:)?on(click|load|key|mouse|error|abort|move|unload|change|dblclick|move|reset|resize|submit)/i",
            "/<script(.*?)>(.*?)<\/script>/si",
            "/<iframe(.*?)>(.*?)<\/iframe>/si",
            "/<object.+<\/object>/isU"
        );
        $replace = array("\\2", "", "", "");
        $value = preg_replace($parten, $replace, $value, -1, $count);
        if ($count > 0) {
            $value = filter_script($value);
        }
        return $value;
    }
}

/**
 * 安全过滤类-过滤HTML标签
 * 使用方法：filter_html($value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function filter_html($value) {
    if (function_exists('htmlspecialchars')) return htmlspecialchars($value);
    return str_replace(array("&", '"', "'", "<", ">"), array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;"), $value);
}

/**
 * 安全过滤类-对进入的数据加下划线 防止SQL注入
 * 使用方法：filter_sql($value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function filter_sql($value) {
    $sql = array("select", 'insert', "update", "delete", "\'", "\/\*",
        "\.\.\/", "\.\/", "union", "into", "load_file", "outfile");
    $sql_re = array("","","","","","","","","","","","");
    return str_replace($sql, $sql_re, $value);
}

/**
 * 私有路径安全转化
 * 使用方法：filter_dir($fileName)
 * @param string $fileName
 * @return string
 */
function filter_dir($fileName) {
    $tmpname = strtolower($fileName);
    $temp = array('://',"\0", "..");
    if (str_replace($temp, '', $tmpname) !== $tmpname) {
        return false;
    }
    return $fileName;
}

/**
 * 过滤目录
 * 使用方法：filter_path($path)
 * @param string $path
 * @return array
 */
function filter_path($path) {
    $path = str_replace(array("'",'#','=','`','$','%','&',';'), '', $path);
    return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $path), '/');
}

/**
 * 过滤PHP标签
 * 使用方法：filter_phptag($string)
 * @param string $string
 * @return string
 */
function filter_phptag($string) {
    return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $string);
}

/**
 * 安全过滤类-返回函数
 * 使用方法：str_out($value)
 * @param  string $value 需要过滤的值
 * @return string
 */
function str_out($value) {
    $badstr = array("&", '"', "'", "<", ">", "%3C", "%3E");
    $newstr = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;", "&lt;", "&gt;");
    $value  = str_replace($newstr, $badstr, $value);
    return stripslashes($value); //下划线
}
