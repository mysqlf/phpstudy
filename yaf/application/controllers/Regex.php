<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class RegexController extends Controller_Abstract{
    public function indexAction(){
        echo "Regex";
        var_dump($this->getRequest()->getParam('id'));

        var_dump($this->getRequest()->getParam('name'));
    }
}
