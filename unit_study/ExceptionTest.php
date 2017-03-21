<?php
use PHPUnit\Framework\TestCase;
class ExceptionTest extends TestCase{
    /*public function testException(){
        $this->expectException(InvalidArgumentException::class);
    }*/
    /*用标注的形式来表明测试结果*/
    /**
     * @expectedException InvalidArgumentException
     */
    public function testException(){

    }
}