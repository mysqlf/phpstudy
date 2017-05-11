<?php

/**
* 
*/
class title{
    public $view='titleview';
    public $title;
    public function getview(){
        return $this->view;
    }
    public function settitle(){

    }
    public function getvalue($appid){
        #通过appid查找title
        return $this->title="title";
    }
}
class time{
    public $view='timeview';
    public $time;
    public function getview(){
        return $this->view;
    }
    public function setvalue($value){

    }
    public function getvalue($appid){
        #通过appid查找title
        return $this->title="time";
    }
    public function upvalue($appid){
        #通过appid操作数据库修改value
    }
}
class workcontent{
    public $view='';
    public function getview(){

    }
    public function upcontent(){
        
    }
}
class app{
    public function __construct(){
        $this->title=new title();
        $this->time=new time();
    }
    public function makeview(){
        $view['title']=$this->title->getview();
        $view['time']=$this->time->getview();
        return $view;
    }
    public function ass_value(){
        $value['title']=$this->title->getvalue(1);
        $value['time']=$this->time->getvalue(1);
        return $value;
    }
}
$app1=new app();
print_r($app1->makeview());
print_r($app1->ass_value());
foreach ($app1->makeview() as $key => $value) {
    var_dump($value);
}
?>
