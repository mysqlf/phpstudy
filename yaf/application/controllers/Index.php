<?php
use Yaf\Controller_Abstract;
use Yaf\Application;
class IndexController extends Controller_Abstract
{
    public function indexAction()
    {
        $this->_view->word = "hello yaf";
        $Test=new Test\Test();
        echo $Test->Index();
        echo "<br>";
        $Test=new Test();
        echo $Test->Index();
        #使用对象方法读取配置文件
        $config = Application::app()->getConfig();
        var_dump($config->application->directory);
        var_dump($config->application->modules);
        var_dump($config->application->dispatcher->defaultModule);
    }
    public function addAction(){
        $this->_view->show="I can use yaf";
    }
}