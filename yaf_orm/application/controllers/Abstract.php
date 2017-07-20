<?php

/**
 * Class AbstractController
 */
abstract class AbstractController extends Yaf\Controller_Abstract
{
    /**
     * 初始化
     */
    public function init(){
        #参数过滤
        filter();
        #登录判断
        $user=get_session('user');
        if (empty($user)) {
            $this->redirect('login/index');
        }
    }
    /**
     * [_view 视图加载]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-10
     * @param    string     $view [description]
     * @param    array      $data [description]
     * @return   [type]           [description]
     */
    public function _view($view='',$data=array()){
        $this->getView()->display('public/header.php');
        $this->getView()->display($view,$data);
        $this->getView()->display('public/footer.php');
    }

}
