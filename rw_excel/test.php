<?php
header("Content-type: text/html; charset=UTF-8");
//首先导入PHPExcel
require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';  
require_once 'PHPExcel/Writer/Excel5.php';  
/**
 * [readexcel 读取excel文件]
 * @param  [string] $filePath [文件名]
 * @return [array]            [数据数组]
 */
function readexcel($filePath){
    //建立reader对象
    $PHPReader = new PHPExcel_Reader_Excel2007();
    if(!$PHPReader->canRead($filePath)){
        $PHPReader = new PHPExcel_Reader_Excel5();
        if(!$PHPReader->canRead($filePath)){
            echo 'no Excel';
            return ;
        }
    }

    //建立excel对象，此时你即可以通过excel对象读取文件，也可以通过它写入文件
    $PHPExcel = $PHPReader->load($filePath);

    /**读取excel文件中的第一个工作表*/
    $currentSheet = $PHPExcel->getSheet(0);
    /**取得最大的列号*/
    $allColumn = $currentSheet->getHighestColumn();
    /**取得一共有多少行*/
    $allRow = $currentSheet->getHighestRow();

    //循环读取每个单元格的内容。注意行从1开始，列从A开始
    $count='';
    for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){
        $arr='';
        for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
            $addr = $colIndex.$rowIndex;
            $cell = $currentSheet->getCell($addr)->getValue();
            if($cell instanceof PHPExcel_RichText)    //富文本转换字符串
                $cell = $cell->__toString();
            $arr[]=$cell;
        
        }
        if ($arr[1]=='女'&&$arr[2]=='深圳')
           $count[]=$arr;
        

    }
    return $count;
}
function saveexcel($arr='',$file=''){
    //新建  
    $resultPHPExcel = new PHPExcel();  
    //设值  
    $resultPHPExcel->getActiveSheet()->setCellValue('A1', '姓名');  
    $resultPHPExcel->getActiveSheet()->setCellValue('B1', '性别');  
    $resultPHPExcel->getActiveSheet()->setCellValue('C1', '地址');  
    $resultPHPExcel->getActiveSheet()->setCellValue('D1', '电话'); 
    $resultPHPExcel->getActiveSheet()->setCellValue('E1', '学历'); 
    $len=count($arr);
     //生成excel文件
    for ($i=0; $i<$len; $i++) {
        $x=65;
        for ($j=0; $j <count($arr[$i]) ; $j++) {
            $name=chr($x);
            $k=$i+2;
            $resultPHPExcel->getActiveSheet()->setCellValue($name.$k,$arr[$i][$j]);
            $x++;
        } 
    }
    $outputFileName = uniqid().'_.xls';
    $objWriter = PHPExcel_IOFactory::createWriter($resultPHPExcel, 'Excel2007');
    $objWriter->save($file);        
}
$filePath = "test.xls";
$count=readexcel($filePath);
echo "<pre>";
#print_r($count);
saveexcel($count,'mytest.xlsx');