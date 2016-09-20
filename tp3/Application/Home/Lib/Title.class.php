<?php
namespace Home\Lib;

use Think\Controller;

class TitleController extends Controller
{
    public $view='title';
    public $value='';
    public function getview(){
        return $this->view;
    }
    public function getvalue($appid){
        return $this->value='标题';
    }
}
?>