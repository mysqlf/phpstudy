<?php
#测试间的依赖
use PHPUnit\Framework\TestCase;

class DependencyFailureTest extends TestCase{
    public function testOne(){
        $this->assertTrue(false);
    }
    /**
     * @depends testOne
     */
    public function testTwo(){

    }
}