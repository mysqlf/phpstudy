<?php
/**
 * file: TestUserController.php
 */
include_once dirname(__FILE__) . '/../TestController.php';

class TestUserController extends TestController {
    public function testGetUserInfoAction() {
        $expectedUserInfo = array(
            1 => array('userId' => '1', 'userNick' => 'ahoLic'),
            2 => array('userId' => '2', 'userNick' => 'kost'),
        );

        $requestUserID = array(1, 2);
        foreach ($requestUserID as $userID) {
            $response = $this->getArrayResponse('User/getUserInfo', $userID);
            $this->assertEquals($expectedUserInfo[$userID], $response);
        }
    }
}
/*define('APP_PATH', dirname(__FILE__) . '/../../');
define('APP_ENV', 'loc');
error_reporting(E_ERROR | E_PARSE);

Class UserControllerTest extends PHPUnit_Framework_TestCase {

    private $__application = NULL;
    
    // 初始化实例化YAF应用，YAF application只能实例化一次
    public function __construct() {
        if ( ! $this->__application = Yaf_Registry::get('Application') ) {
            $this->__application = new Yaf_Application(APP_PATH."/config/application.ini", APP_ENV);
            Yaf_Registry::set('Application', $this->__application);
        }
    }

    // 创建一个简单请求，并利用调度器接受Repsonse信息，指定分发请求。
    private function __requestActionAndParseBody($action, $params=array()) {
        $request = new Yaf_Request_Simple("CLI", "Index", "User", $action, $params);
        $response = $this->__application->getDispatcher()
            ->returnResponse(TRUE)
            ->dispatch($request);
        return $response->getBody();
    }

    // 测试 JsonAction UID存在
    public function testJsonUid1Action() {
        $response = $this->__requestActionAndParseBody('Json', array('uid'=>1));
        $data     = json_decode($response, TRUE);
        $this->assertInternalType('array', $data);
        $this->assertEquals('0', $data['code']);
        $this->assertInternalType('string', $data['data']['username']);
        $this->assertRegExp('/^\d+$/', $data['data']['groupid']);
        $this->assertRegExp('/^\d+$/', $data['data']['adminid']);
        $this->assertRegExp('/^\d+$/', $data['data']['regdate']);
    }

    // 测试 JsonAction UID不存在，UID不存在返回的code应该是-1
    public function testJsonUidNotFoundAction() {
        $response = $this->__requestActionAndParseBody('Json');
        $data     = json_decode($response, TRUE);
        $this->assertInternalType('array', $data);
        $this->assertEquals('0', $data['code']);
    }
}*/