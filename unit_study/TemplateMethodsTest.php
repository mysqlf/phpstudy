<?php
/*setUpBeforeClass() 与 tearDownAfterClass() 模板方法将分别在测试用例类的第一个测试运行之前和测试用例类的最后一个测试运行之后调用。*/
use PHPUnit\Framework\TestCase;
class TemplateMethodsTest extends TestCase{
    public static function setUpBeforeClass(){
        fwrite(STDOUT, __METHOD__."\n");
    }
    protected function setUp(){
        fwrite(STDOUT,__METHOD__."\n");
    }
    protected function assertPreConditions(){
        fwrite(STDOUT,__METHOD__."\n");
    }
    public function testOne(){
        fwrite(STDOUT, __METHOD__."\n");
        $this->assertTrue(true);
    }
    public function testTwo(){
        fwrite(STDOUT,__METHOD__."\n");
        $this->assertTrue(false);
    }
    public function assertPostConditions(){
        fwrite(STDOUT, __METHOD__."\n");
    }
    public function testDown(){
        fwrite(STDOUT, __METHOD__."\n");
    }
    public static function tearDownAfterClass(){
        fwrite(STDOUT,__METHOD__."\n");
    }
    protected function onNotSuccessfulTest(Exception $e){
        fwrite(STDOUT,__METHOD__."\n");
        throw $e;
    }
}