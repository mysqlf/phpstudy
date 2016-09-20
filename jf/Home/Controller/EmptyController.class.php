<?php
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {
    public function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("/error");
    }
    public function index() {
        header("HTTP/1.0 404 Not Found");
        $this->display('/error');
    }
}