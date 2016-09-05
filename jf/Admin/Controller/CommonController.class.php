<?php
namespace Admin\Controller;
use Think\Controller;
//公共继承类
class CommonController extends Controller {
    public function _initialize () {
        //验证登陆,没有登陆则跳转到登陆页面
        if(!isset($_SESSION['uname']) || $_SESSION['uname']==''||!isset($_SESSION['uid']) || $_SESSION['uid']==''){
            $this->redirect('Login/index');
            exit;
        }
        define('UID',$_SESSION['uid']);//用户ID
        $this->assign('uid',UID);
        //权限认证
        if(!authcheck(CONTROLLER_NAME.'/'.ACTION_NAME,UID)){
            $this->error('权限不足');
        }
        //模型列表
        $model_list=M('Model')->where(array('status'=>1))->select();
        $this->assign('model_menu',$model_list);
    }
}