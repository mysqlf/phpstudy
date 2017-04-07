<?php
use Yaf\Controller_Abstract;
use Yaf\Application;
class IndexController extends Controller_Abstract
{
    public function indexAction()
    {
        $this->_view->word = "hello yaf";
        $request = $this->getRequest();
       /* var_dump($request->getRequestUri());     //   输出：/test/test
        var_dump($request->getBaseUri());        //   输出：''
        var_dump($request->getMethod());         //   输出GET
        var_dump($request->getPost());           //   输出：array()
        var_dump($request->getQuery());          //   输出: array()
        var_dump($request->getParam('id'));      //   输出：NULL
        var_dump($request->getParams());  */       //   输出：array()
        /*$Test=new Test\Test();
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
        var_dump($this->getRequest()->getParam('id'));*/


    }
    public function getpostAction(){
         $request = $this->getRequest();
         var_dump($request->getPost());
    }
    public function addAction(){
        $this->_view->show="I can use yaf";
    }
    public function testmongodbAction(){
        #创建连接
       $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  

        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert(['x' => 1, 'name'=>'菜鸟教程', 'url' => 'http://www.runoob.com']);
        $bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
        $bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
        $manager->executeBulkWrite('test.sites', $bulk);

        $filter = ['x' => ['$gt' => 1]];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['x' => -1],
        ];
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);

        foreach ($cursor as $document) {
            var_dump($document);
        }
        $bulks = new MongoDB\Driver\BulkWrite;
        $bulks->update(
            ['x' => 2],
            ['$set' => ['name' => '菜鸟工具', 'url' => 'tool.runoob.com']],
            ['multi' => false, 'upsert' => false]
        );
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $manager->executeBulkWrite('test.sites', $bulks, $writeConcern);
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);

        foreach ($cursor as $document) {
            var_dump($document);
        }

    }
    public function gogogoAction(){
        $manager=new MongoDB\Driver\Manager("mongodb://localhost:27017");
        $bulk=new MongoDB\Driver\BulkWrite;
        $document=['_id'=>new MongoDB\BSON\ObjectID,'name'=>'LZYX'];
        $_id=$bulk->insert($document);
        $write=new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY,1000);
        $result=$manager->executeBulkWrite('test.sites',$bulk,$write);
        var_dump($_id);
        var_dump($result);
        $filter=['name'=>['$like'=>'L']];
        $options=[];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);
        foreach ($cursor as $document) {
            print_r($document);
        }
    }
}