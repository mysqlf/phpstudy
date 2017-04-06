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
        //导入一个函数库文件common.php，即可使用common.php中的函数
        Yaf\Loader::import(APP_PATH.'/application/helpers/common.php');
        var_dump(gethelper());
         var_dump($this->getRequest()->getParam('id'));
    }
    public function addAction(){
        $this->_view->show="I can use yaf";
    }
}