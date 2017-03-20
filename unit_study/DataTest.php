<?php
use PHPUnit\Framework\TestCase;
require 'CsvFile.php';
class DataTest extends TestCase{
    /**
     * @dataProvider additionProvider
     */
    public function testAdd($a,$b,$expected){
        $this->assertEquals($expected,$a+$b);
    }
    /*public function additionProvider(){
        return [
            [0,0,0],
            [0,1,0],
            [1,1,2],
            [1,2,3],
            [1,0,1],
            [1,1,3]
        ];
    }
*/
/*    public function additionProvider(){
        return [
            'adding zeros'  => [0, 0, 0],
            'zero plus one' => [0, 1, 1],
            'one plus zero' => [1, 0, 1],
            'one plus one'  => [1, 1, 3]
        ];
    }*/
    public function additionProvider(){
        return new CsvFileIterator('data.csv');
    }
}