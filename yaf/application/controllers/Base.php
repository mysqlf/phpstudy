<?php
use Yaf\Controller_Abstract;
class BaseController extends Controller_Abstract{
    /**
     * [_view 视图渲染]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-05
     * @param    string     $view [模版路径]
     * @param    array      $data [模版变量]
     * @return   [type]           [description]
     */
    public function _view($view='',$data=array()){
        $this->getView()->display('public/header.php');
        $this->getView()->display($view,$data);
        $this->getView()->display('public/footer.php');
    }
}
