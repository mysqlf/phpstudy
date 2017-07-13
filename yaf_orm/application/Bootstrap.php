<?php
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * ������Bootstrap����, ��_init��ͷ�ķ���, ���ᱻYaf����,
 * ��Щ����, ������һ������:Yaf_Dispatcher $dispatcher
 * ���õĴ���, �������Ĵ�����ͬ
 */
class Bootstrap extends Yaf\Bootstrap_Abstract{
	//�Զ����ص�����
	public function _initLoader() {
        Yaf\Loader::import(APP_PATH . "/vendor/autoload.php");
    }
	//��������
	public function _initConfig() {
		$config = Yaf\Application::app()->getConfig();
		Yaf\Registry::set("config", $config);
	}
    //���Ĭ��
	public function _initDefaultName(Yaf\Dispatcher $dispatcher) {
		$dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
	}
	
	//���ݿ��ʼ������
	public function _initDatabaseEloquent() {
        $config = Yaf\Application::app()->getConfig()->database->toArray();
        $capsule = new Capsule;
        // ��������
        $capsule->addConnection($config);

        // ����ȫ�־�̬�ɷ���
        $capsule->setAsGlobal();

        // ����Eloquent
        $capsule->bootEloquent();

    }
    //�Զ������û�����
    public function _initFunction(){
        Yaf\Loader::import('common/functions.php');
    }
    //����session
    public function _initSession(Yaf\Dispatcher $dispatcher){
        Yaf\Session::getInstance()->start();
    }
    //��ͼ����
    public function _initView(Yaf\Dispatcher $dispatcher){
        $dispatcher->autoRender(false);#�رո���·���Զ���Ⱦģ��
    }
    
    //·�ɳ�ʼ��
    public function _initRoute(){
        //��������
        Yaf\Dispatcher::getInstance()->catchException(true);
    }
    /**---������---**/
    public static function error_handler($errno, $errstr, $errfile, $errline) { 
        if (error_reporting() === 0) 
            return;
         throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    public function _initErrorHandler(Yaf\Dispatcher $dispatcher) { 
        $dispatcher->setErrorHandler(array(get_class($this),'error_handler'));
    }
}
