<?php
namespace Home\Controller;

use Think\Controller;
use Home\Lib;
class AppController extends Controller
{
    public $value=array(
        "title"=>'',
        "time"=>''
        );
    public $views=array(
        "title"=>'',
        "time"=>''
        );
    public function __construct(){
        parent::__construct();
        $this->title=new \Home\Lib\Title();
        $this->time=new \Home\Lib\Time();
    }
    public function getvalue(){
        $this->value['title']=$this->title->getvalue(1);
        $this->value['time']=$this->time->getvalue(1);
    }
    /**
     * [show 申请单展示]
     * @return [type] [description]
     */
    public function show(){
        $this->getvalue();
        $this->getviews();
        $this->assign('views',$this->views);
        $this->assign('value',$this->value);
        $this->display();
    }
    public function getviews(){
        $this->views['title']=$this->title->getviews();
        $this->views['time']=$this->time->getviews();
    }
    public function edit(){
        $time=I('time');
        $title=I('title');
        $this->getvalue();
        $this->time->update(1,$time);
        $this->title->update(1,$title);
    }
}
 ?>