<?php

include 'Base.php';

class IndexController extends BaseController {
    public function indexAction() {
        return $this->response(true, 'hello world!', array('hello world!'));
    }
    public function addAction(){
        return $this->response(true,'asd');
    }
}
