<?php
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract{
	//自动加载第三方
	public function _initLoader() {
        Yaf\Loader::import(APP_PATH . "/vendor/autoload.php");
    }
	//加载配置
	public function _initConfig() {
		$config = Yaf\Application::app()->getConfig();
		Yaf\Registry::set("config", $config);
	}
    //添加默认
	public function _initDefaultName(Yaf\Dispatcher $dispatcher) {
		$dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
	}
	
	//数据库初始化操作
	public function _initDatabaseEloquent() {
        $config = Yaf\Application::app()->getConfig()->database->toArray();
        $capsule = new Capsule;
        // 创建链接
        $capsule->addConnection($config);

        // 设置全局静态可访问
        $capsule->setAsGlobal();

        // 启动Eloquent
        $capsule->bootEloquent();

    }
    //自动加载用户函数
    public function _initFunction(){
        Yaf\Loader::import('common/functions.php');
    }
    //开启session
    public function _initSession(Yaf\Dispatcher $dispatcher){
        Yaf\Session::getInstance()->start();
    }
    //视图配置
    public function _initView(Yaf\Dispatcher $dispatcher){
        $dispatcher->autoRender(false);#关闭根据路由自动渲染模版
    }
    
    //路由初始化
    public function _initRoute(){
        //开启错误
        Yaf\Dispatcher::getInstance()->catchException(true);
    }
    /**---错误处理---**/
    public static function error_handler($errno, $errstr, $errfile, $errline) { 
        if (error_reporting() === 0) 
            return;
         throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    public function _initErrorHandler(Yaf\Dispatcher $dispatcher) { 
        $dispatcher->setErrorHandler(array(get_class($this),'error_handler'));
    }
}
