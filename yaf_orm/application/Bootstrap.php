<?php
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * ������Bootstrap����, ��_init��ͷ�ķ���, ���ᱻYaf����,
 * ��Щ����, ������һ������:Yaf_Dispatcher $dispatcher
 * ���õĴ���, �������Ĵ�����ͬ
 */
class Bootstrap extends Yaf\Bootstrap_Abstract{
	
	public function _initLoader() {
        Yaf\Loader::import(APP_PATH . "/vendor/autoload.php");
    }
	
	public function _initConfig() {
		$config = Yaf\Application::app()->getConfig();
		Yaf\Registry::set("config", $config);
	}

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
    public function _initFunction(){
        Yaf\Loader::import('common/functions.php');
    }
    public function _initSession(Yaf\Dispatcher $dispatcher){
        Yaf\Session::getInstance()->start();
    }
    public function _initView(Yaf\Dispatcher $dispatcher){
        $dispatcher->autoRender(false);#�رո���·���Զ���Ⱦģ��
    }
    
    public function _initRoute(){
        \Yaf\Dispatcher::getInstance()->catchException(true);
    }
}
