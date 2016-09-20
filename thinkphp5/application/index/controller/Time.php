<?php
namespace app\index\controller;

use \think\View;
class Time
{
    public function index()
    {
        $view = new View();
        return $view->fetch('home/hello',['name'=>'thinkphp']);
    }
    public function addtitle(){
        return array('title'=>'test');
    }
}