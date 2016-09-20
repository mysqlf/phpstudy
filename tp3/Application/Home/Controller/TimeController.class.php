<?php
namespace Home\Controller;

use Think\Controller;

class TimeController extends Controller
{
    public $value='';

    public function getvalue($appid){
        return $this->value='时间';
    }
    public function update($appid,$value){
        print_r($value);
        #数据库操作----
    }
}
?>