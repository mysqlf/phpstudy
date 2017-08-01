<?php
use Illuminate\Database\Capsule\Manager as DB;

class MongodbController extends AbstractController {
    /**
     * [dblistAction 数据库列表]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-08-01
     * @return   [type]     [description]
     */
    public function dblistAction(){
        #查询所有数据库
        $client = new MongoDB\Client;
        $data['list']=$client->listDatabases();
        $this->getView()->display('mongo/header.php',$data);
    }
    /**
     * [collectionlistAction ]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-08-01
     * @return   [type]     [description]
     */
    public function collectionlistAction(){
        $request=$this->getRequest();
        $dbname=$request->get('dbname');
        if ($dbname!==null) {
            $database = (new MongoDB\Client)->$dbname;
            $collection=$database->listCollections();
            foreach ($collection as $key => $value) {
                $data['collection'][]=$value;
            }
            $data['dbname']=$dbname;
            $this->getView()->display('mongo/collectionlist.php',$data);
         }
    }
    /**
     * [collectioninfoAction description]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-08-01
     * @return   [type]     [description]
     */
    public function collectioninfoAction(){
        $request=$this->getRequest();
        $dbname=$request->get('dbname');
        $collectionname=$request->get('collname');
        if ($dbname!==null && $collectionname !==null) {
            $collection=(new MongoDB\Client)->$dbname->$collectionname;
            $cursor=$collection->find(
                [],
                [
                    'skip'=>0,#开始
                    'limit' => 5,#条数限制
                ]
            );
            foreach ($cursor as $restaurant) {
               $list[]=(array)$restaurant;
            };
            $key=array_keys($list[0]);
            $data['key']=$key;
            $data['list']=$list;
            $this->getView()->display('mongo/info.php',$data);

        }
    }
    public function indexAction() {//默认Action
       # $manager = new MongoDB\Driver\Manager('mongodb://127.0.0.1/');
        $collection = (new MongoDB\Client)->test->users;
        #$document = $collection->findOne(['username'=>'admin']);#查询一条
        #var_dump($document);
        #正常查询
        $cursor = $collection->find(
            [],
            [
                'skip'=>0,#开始
                'limit' => 5,#条数限制

            ]
        );
        foreach ($cursor as $restaurant) {
           var_dump((array)$restaurant);
        };
        #查询所有数据库
        $client = new MongoDB\Client;
        #var_dump($client->listDatabases());
        #查询所有表
        $database = (new MongoDB\Client)->test;
        foreach ($database->listCollections() as $collections) {
            #var_dump($collections);
        }
        #查询主键
        $collection = (new MongoDB\Client)->test->users;
        foreach ($collection->listIndexes() as $index) {
           #var_dump($index);
        }
        #单个字段查询
        $collection = (new MongoDB\Client)->test->users;
        $distinct = $collection->distinct('name');
        #var_dump($distinct);
    }
}
