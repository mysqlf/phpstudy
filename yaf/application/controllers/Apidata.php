<?php
use Yaf\Application;
class ApidataController extends BaseController{
    /**
     * [addapiAction 添加接口]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-06
     * @return   [type]     [description]
     */
    public function addapiAction(){
        $request = $this->getRequest();
        if (!empty($request->getPost())) {
            $param=$request->getPost();
            #获取接口参数
            #写入数据库
        }else{
            $this->_view('api/soap.php');
        }
    }
    public function getdataAction(){
        $db = Yaf\Registry::get('adapter');
        #var_dump($db->getDriver());
        $result = $db->query("select * from api where id=1");
         var_dump($db->getQueryResultSetPrototype());
        $sql="select * from api where id=1";
        #var_dump($result->execute($sql));
        return false;
    
    }
}
