<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class RegexsController extends Controller_Abstract{
    public function indexAction(){
        var_dump($this->getRequest()->getParam('id'));
        echo 123;
        var_dump($this->getRequest()->getParam('name'));
    }
}
