<?php
/**
 * 框架启动文件
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{
    /**
         * 加载核心目录
         */
        public function _initCore()
        {
            //如自定义一些公共核心类，如基类控制器、基类模型等
        }
        public function _initConfig() {
            $config = Yaf\Application::app()->getConfig();
            Yaf\Registry::set("config", $config);
        }
        /*public function _initConfig()
        {
            #Yaf_Registry::set('config', Yaf_Application::app()->getConfig()->toArray());
            Yaf\Registry::set('config', Yaf\Application::app()->getConfig()->toArray());
        }*/
        /**
         * 初始化插件
         */
        public function _initPlugin()
        {
     
        }
     
        public function _initLib()
        {
            //加载公共通过类
        }
         /**
         * 初始化路由
         */
        //public function _initRoute(Yaf_Dispatcher $dispatcher)
        public function _initRoute(Yaf\Dispatcher $dispatcher) {
            $dispatcher->catchException(true);
            $router = $dispatcher->getRouter();
            /**
             *加载配置文件内的路由规则 application.ini
             */
            $router->addConfig(Yaf\Registry::get("config")->routes);
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
        
       /* public function _initRoute(Yaf\Dispatcher $dispatcher)
        {
            
            $router = $dispatcher->getRouter();

            //$router->addConfig(Yaf_Registry::get("config")['routes']);
            //在配置文件中写路由
            $router->addConfig(Yaf\Registry::get("config")['routes']);
            //$router->addConfig(Yaf_Registry::get("config")->routes);
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
        }*/
     
        public function _initView()
        {
            //Yaf_Dispatcher::getInstance()->disableView();    //如果只是提供数据接口，则禁止模板输出
        }
}