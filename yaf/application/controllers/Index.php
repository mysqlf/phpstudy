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
    /**
     * [testmongodbAction mongodb]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-10
     * @return   [type]     [description]
     */
    public function testmongodbAction(){
        #创建连接
       $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;

        $bulk->insert(['x' => 1, 'name'=>'菜鸟教程', 'url' => 'http://www.runoob.com']);
        $bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
        $bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
        $bulk->insert(['x' => 4, 'name'=>null, 'url' => 'http://www.taobao.com']);
        $manager->executeBulkWrite('test.sites', $bulk);

        /*$filter = ['x' => ['$gt' => 0]];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['x' => 1],
        ];
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);

        foreach ($cursor as $document) {
            var_dump($document);
        }*/

        $filter = ['name' => ['$type' =>10]];
        $options = [
            'projection' => ['_id' => 1],#  0 不返回_id  1返回_id
            'sort' => ['x' => 1],
        ];
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);

        foreach ($cursor as $document) {
            var_dump($document);
        }
       
        //修改数据
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['x' => 2],#条件
            ['$set' => ['name' => '菜鸟工具', 'url' => 'tool.runoob.com']],
            #修改的数值
            ['multi' => false, 'upsert' => false]
            #multi : 可选，mongodb 默认是false,只更新找到的第一条记录，如果这个参数为true,就把按条件查出来多条记录全部更新。
            #upsert : 可选，这个参数的意思是，如果不存在update的记录，是否插入objNew,true为插入，默认是false，不插入
        );
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        #MongoDB\Driver\WriteConcern::MAJORITY
        #确定写入操作成功写入
        #1000  写入保证时间,发生故障在时间内会尝试继续写入,超时则报失败
        $result = $manager->executeBulkWrite('test.sites', $bulk,$writeConcern);

        $bulk = new MongoDB\Driver\BulkWrite;
        #删除数据
        $bulk->delete(['x' => 1], ['limit' => 0]);   // limit 为 1 时，删除第一条匹配数据
        $bulk->delete(['x' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据
        $bulk->delete(['x' => 3], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据
        $bulk->delete(['x' => 4], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据
        $result = $manager->executeBulkWrite('test.sites', $bulk, $writeConcern);

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
        $filter=['name'=>['$text'=>'L']];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['x' => -1],
        ];
        $options=[];
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);
        foreach ($cursor as $document) {
            print_r($document);
        }
    }
}