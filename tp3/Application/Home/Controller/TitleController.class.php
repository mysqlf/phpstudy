<?php
namespace Home\Controller;

use Think\Controller;

class TitleController extends Controller
{
    public $views="title";
    public $value='';
    public function getviews(){
        return $this->views;
    }
    public function getvalue($appid){
        return $this->value='标题';
    }
    public function update($appid,$value){
        print_r($value);
        #数据库操作----
    }
}
?>