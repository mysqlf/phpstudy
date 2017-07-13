<?php
use Katzgrau\KLogger\Logger;
class ErrorController extends Yaf\Controller_Abstract
{
    /**
     * 也可通过$request->getException()获取到发生的异常
     */
    public function errorAction($exception)
    {
        $constArr = array(
            YAF\ERR\NOTFOUND\MODULE,
            YAF\ERR\NOTFOUND\CONTROLLER,
            YAF\ERR\DISPATCH_FAILED,
            YAF\ERR\NOTFOUND\ACTION,
            YAF\ERR\NOTFOUND\VIEW
        );
        #var_dump($exception->getMessage());
        
        $log=new Logger(APP_PATH.'/data/log');
        $err = $exception->getCode();
        if (in_array($err, $constArr)) {
            $code = 404;
            $message = '请求的页面不存在';
        } else {
            $code = 500;
            $message = '系统出错';
        }
        if (ENV == 'DEV') {
            $message = $exception->getMessage();
        }
        $log->info($exception->getMessage());
        //记录日志
        //ajax输出或显示错误模板
        
    }
}
