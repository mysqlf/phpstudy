<?php
Class UserController extends Yaf\Controller_Abstract {
    // init方法相当于控制器的初始化函数，取消自动渲染视图
    public function init() {
        Yaf_dispatcher::getInstance()->disableView();
    }
    // 输出需要的JSON信息
    private function __responseJson($code=0, $data=FALSE) {
        $response = json_encode(array('code'=>$code, 'data'=>$data));
        $this->getResponse()->setBody($response);
    }
    // JSON Action
    public function jsonAction($uid=0) {
        if ( $uid < 1 ) return $this->__responseJson(-1);

        $user_model = new UserModel();
        $row = $user_model->fetchRowById($uid);
        return $this->__responseJson(0, $row);
    }
}