<?php
use Yaf\Controller_Abstract;
use Yaf\Application;

class GoodsController extends Controller_Abstract
{
    public function itemAction()
    {   
        var_dump(123);
        var_dump($this->getRequest()->getParam('id'));
    }
}
