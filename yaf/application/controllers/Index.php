<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class IndexController extends Controller_Abstract
{

    public function startAction(){
        $baseurl='http://sou.zhaopin.com/jobs/searchresult.ashx?jl=%E6%B7%B1%E5%9C%B3&kw=php&sm=0&pd=3&isfilter=1&sf=-1&st=-1&sg=1987c3cd5d8d47fdab58a1a94dd7fe0e&p=10';
        $count=10;
        //$mongodb=new Database\Mongo();
       $content=self::getcontent($baseurl);
       var_dump($content);

       //var_dump($content);
       # $list=self::get_links($content);

       /* foreach ($content as  $value) {
            var_dump($value->outertext);
            var_dump($value->outertext);
            var_dump($value->plaintext);
            //var_dump(self::objectToArray($value));
        }*/



    }
    /**
     * object 转 array
     */

    public function objectToArray($d) {
        $_arr=is_object($obj)?get_object_vars($obj):$obj;
        foreach($_arr as $key=>$val){
            $val=(is_array($val))||is_object($val)?object_to_array($val):$val;
            $arr[$key]=$val;
        }
        return $arr;
    }

    public function getcontent($url){
        $content=file_get_contents($url);
        Yaf\Loader::import('simple_html_dom.php');
        $html = new simple_html_dom();
        $html->load($content);
        $div=$html->find('div[id=newlist_list_content_table]');
        return $div;
        $zwmc=$html->find('tbody td.zwmc div');
        $gsmc=$html->find('tbody td.gsmc a');
        $zwyx=$html->find('tbody td.zwyx');
        $gzdd=$html->find('tbody td.gzdd');
        $gxsj=$html->find('tbody td.gxsj');
        $html->clear();
        //return $zwmc;
       
    }
    public function get_links($url) { 

        // Create a new DOM Document to hold our webpage structure 
        $xml = new DOMDocument(); 

        // Load the url's contents into the DOM 
        $xml->loadHTMLFile($url);

        // Empty array to hold all links to return 
        $links = array(); 

        //Loop through each <a> tag in the dom and add it to the link array 
        foreach($xml->getElementsByTagName('a') as $link) { 
            $links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue); 
        } 

        //Return the links 
        return $links; 
} 


    /**
     * [testmongodbAction mongodb]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-04-10
     * @return   [type]     [description]
     */
    public function testmongodbAction(){
      
        #插入
        $mongodb=new Database\Mongo();
        $list=['x' => 1, 'name'=>'langzi', 'url' => 'http://wp.greedy.bid'];
        $table='test.lz';
        $mongodb->add($table,$list);
        #查询
        $filter=['x' => ['$gt' => 0]];
        $options=[ 
            'projection' => ['_id' => 0],
            'sort' => ['x' => 1],
            ];
        $document=$mongodb->search($table,$filter,$options);
        foreach ($document as  $value) {
            var_dump($value);
        }
        #修改
        $where=['x' => 1];
        $set= ['name' => 'LZYX', 'url' => 'tool.runoob.com'];
        $multi=['multi' => false, 'upsert' => false];
        $mongodb->update($table,$where,$set,$multi);
        #查询
        $filter=['x' => ['$gt' => 0]];
        $options=[ 
            'projection' => ['_id' => 0],
            'sort' => ['x' => 1],
            ];
        $document=$mongodb->search($table,$filter,$options);
        foreach ($document as  $value) {
            var_dump($value);
        }
        #删除
        $where=['x' => 1];
        $limit= ['limit' => 0];
        $mongodb->delete($table,$where,$limit);
          #创建连接
      /* $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;

        $bulk->insert();
        $bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
        $bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
        $bulk->insert(['x' => 4, 'name'=>null, 'url' => 'http://www.taobao.com']);
        $manager->executeBulkWrite('test.sites', $bulk);*/
        /*$filter = ['x' => ['$gt' => 0]];
        $options = ['projection' => ['_id' => 0],'sort' => ['x' => 1],];
        // 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('test.sites', $query);
        foreach ($cursor as $document) {
            var_dump($document);
        }*/
       /* $filter = ['name' => ['$type' =>10]];
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
        $result = $manager->executeBulkWrite('test.sites', $bulk, $writeConcern);*/

    }
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
    public function getpostAction(){
         $request = $this->getRequest();
         var_dump($request->getPost());
    }
    public function addAction(){
        $this->_view->show="I can use yaf";
    }

}