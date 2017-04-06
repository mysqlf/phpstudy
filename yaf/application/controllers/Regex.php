<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class RegexController extends Controller_Abstract{
    public function indexAction(){
        var_dump($this->getRequest()->getParam('id'));
        echo "Regex";
        var_dump($this->getRequest()->getParam('name'));
    }
}
