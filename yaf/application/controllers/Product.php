<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class ProductController extends Controller_Abstract{
    public function indexAction(){
        var_dump($this->getRequest()->getParam('name'));
        echo "product";
        var_dump($this->getRequest()->getParam('value'));
    }
}
