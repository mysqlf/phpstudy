<?php
namespace Home\Lib;



class Title
{
    public $views="Public/title";
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
    public function checkvalue(){
        
    }
}
?>