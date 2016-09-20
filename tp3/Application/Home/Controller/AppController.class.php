<?php
namespace Home\Controller;

use Think\Controller;
class AppController extends Controller
{
    public $value=array(
        "title"=>''
        );
    public function __construct(){
        parent::__construct();
        $this->title=new \Home\Controller\TitleController();
         $this->time=new \Home\Controller\TimeController();
    }
    public function getvalue(){
        $this->value['title']=$this->title->getvalue(1);
        $this->value['time']=$this->time->getvalue(1);
    }

    public function show(){
        $this->getvalue();
        $this->assign('value',$this->value);
        $this->display();
    }
    public function edit(){
        $time=I('time');
        $title=I('title');
        $this->getvalue();
        $this->time->update(1,$time);
        $this->title->update(1,$title);
/*       var_dump($time);
        var_dump($title);*/
    }
}
 ?>