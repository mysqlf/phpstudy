<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class RegexsController extends Controller_Abstract{
    public function indexAction(){
        var_dump($this->getRequest()->getParam('ids'));
        echo "Regexs";
        var_dump($this->getRequest()->getParam('names'));
    }
}
