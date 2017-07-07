<?php

/**
 * 框架启动文件
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{
    private $_config;

    public function _initConfig() {
        $this->_config = Yaf\Application::app()->getConfig();
        Yaf\Registry::set("config", $this->_config);
    }

    public function _initDefaultName(Yaf\Dispatcher $dispatcher) {
        $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");

    }

    /**
     * 设置页面layout
    */
    /*public function _initLayout(Yaf\Dispatcher $dispatcher){
        $layout = new Layout($this->_config->application->layout->directory);
        $dispatcher->setView($layout);
    }*/

    public function _initNamespaces(){
        //申明, 凡是以Zend,Local开头的类, 都是本地类
        Yaf\Loader::getInstance()->registerLocalNameSpace(array("Zend", "Local"));
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由  通过派遣器获取默认的路由器
        $router = Yaf\Dispatcher::getInstance()->getRouter();//获取路由器
        $router->addConfig($this->_config->routes);//加载路由协议

        //手动注册路由
        $route = new \Yaf\Route\Regex('#^/item/([a-zA-Z-_0-9]+)#', array(
            'controller'=>'goods',
            'action'=>'item'
        ), array(1=>'id'));
        $router->addRoute('goodsDetail', $route);
        $route = new \Yaf\Route\Regex('#^/index/([a-zA-Z-_0-9]+)#', array(
            'controller'=>'index',
            'action'=>'index'
        ), array(1=>'id'));
        $router->addRoute('index', $route);
    }
    /**
     * 连接数据库,设置数据库适配器
     */
    public function _initDefaultDbAdapter(){
        $dbAdapter = new Zend\Db\Adapter\Adapter(
            $this->_config->database->params->toArray()
        );
        Yaf\Registry::set("adapter", $dbAdapter);
    }
    public function _initView(Yaf\Dispatcher $dispatcher){
         $dispatcher->autoRender(false);#关闭根据路由自动渲染模版
    }        
}
