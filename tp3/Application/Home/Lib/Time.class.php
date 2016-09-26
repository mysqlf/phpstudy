<?php
namespace Home\Lib;



class Time
{
    public $views="Public/title";
    public $value='';
    public function getviews(){
        return $this->views;
    }
    public function getvalue($appid){
        return $this->value='时间';
    }
    public function update($appid,$value){
        print_r($value);
        #数据库操作----
    }
    public function checkvalue(){
        
    }
}
?>