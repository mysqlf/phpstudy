<?php
use PHPUnit\Framework\TestCase;
class OutputTest extends TestCase{
    public function testExpectFppActualFoo(){
        $this->expectOutputString('foo');
        print_r('foo');
    }
    public function testExpectBarActualBaz(){
        $this->expectOutputString('bar');
        print_r('baz');
    }
}