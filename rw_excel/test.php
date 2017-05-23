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

		$count[]=$arr;
	}
	return $count;
}
/**
 * [saveexcel 保存为xml]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-10
 * @param    string     $arr  [description]
 * @param    string     $file [description]
 * @return   [type]           [description]
 */

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
			#列名

			$name=chr($x);
			$k=$i+2;
			$resultPHPExcel->getActiveSheet()->setCellValue($name.$k,$arr[$i][$j]);
			$x++;
		} 
	}

	#$outputFileName = uniqid().'_.xls';
	$objWriter = PHPExcel_IOFactory::createWriter($resultPHPExcel, 'Excel2007');
	$objWriter->save($file);        
}
$list=array(
	"北京_123"=>array(
		'北京_123'=>array(
			'朝阳区_123'=>array(
				'SHOP'=>value[],
				'WS_NAME'=>$value[0],
				'ADDRESS'=>$value[2],
				'HOT_LINE'=>$value[3],
				'URL'=>$value[4]),
			),
		),
	);
/**
 * [fittle_city 数据]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-10
 * @param    [type]     $arr [description]
 * @return   [type]          [description]
 */
function fittle_city($arr){
	foreach ($arr as $key => $value) {
		$result[$value[1]][$value[5]][]=array('WS_NAME'=>$value[0],'ADDRESS'=>$value[2],'HOT_LINE'=>$value[3],'URL'=>$value[4]);
	}
	return $result;
}
#数据组合根据城市
function fittle_city2($arr){
	foreach ($arr as $key => $value) {
		#省code 市code 区code 
		$result[$value[1]."_".$value['code']][$value[5]."_".$value['code']][$value[]."_".$value[]]=array('WS_NAME'=>$value[0],'ADDRESS'=>$value[2],'HOT_LINE'=>$value[3],'URL'=>$value[4]);
	}
	return $result;
}
/**
 * [savexml2 保存为xml 带code]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-10
 * @param    [type]     $arr  [description]
 * @param    [type]     $file [description]
 * @return   [type]           [description]
 */
function savexml2($arr,$file){
	$xml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xml.="<list>\n";
	foreach ($arr as $key => $value) {
		#省份
		$arrpro=explode($key,"_");
		$xml.='<Province name="'.$arrpro[0]."\" id=".$arrpro[1].">\n";
		foreach ($value as $ke => $val) {
			#城市
			$arrcity=explode($ke,"_");
			$xml.="<City name=\"".$arrcity[0]."\" id=".$arrcity[1].">\n";
			foreach ($val as $k => $v) {
				#区
				$arrtown=explode($k,"_");
				$xml.="<Town name=\"".$arrtown[0]."\" id=".$arrtown[1].">\n";
				foreach ($v as $y => $e) {
					#经销商
					$xml.="<Shop id="">\n";
					$xml.="<WS_NAME>".$e['WS_NAME']."</WS_NAME>\n"; 
					$xml.="<ADDRESS>".$e['ADDRESS']."</ADDRESS>\n";
					$xml.="<HOT_LINE>".$e['HOT_LINE']."</HOT_LINE>\n";
					$xml.="<URL>".$e['URL']."</URL>\n";
					$xml.="</Shop>\n";  
				}
				$xml.="</Town>"
			}
			$xml.="</City>\n";
		}
		$xml.="</Province>\n";
	}
	$xml.="</list>";
	$f=fopen($file, "w");
	fwrite($f,$xml);
	fclose($f);

}
/**
 * [savexml 保存为xml]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-10
 * @param    [type]     $arr  [description]
 * @param    [type]     $file [description]
 * @return   [type]           [description]
 */
function savexml($arr,$file){
	$xml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xml.="<list>\n";
	foreach ($arr as $key => $value) {
		#省份
		
		$xml.='<Province name="'.$key."\" id="">\n";
		foreach ($value as $ke => $val) {
			#城市
			$xml.="<City name=\"".$ke."\" id="">\n";
			foreach ($val as $k => $v) {
				#经销商
				$xml.="<Shop id="">\n";
				$xml.="<WS_NAME>".$v['WS_NAME']."</WS_NAME>\n"; 
				$xml.="<ADDRESS>".$v['ADDRESS']."</ADDRESS>\n";
				$xml.="<HOT_LINE>".$v['HOT_LINE']."</HOT_LINE>\n";
				$xml.="<URL>".$v['URL']."</URL>\n";
				$xml.="</Shop>\n";  
			}
			$xml.="</City>\n";
		}
		$xml.="</Province>\n";
	}
	$xml.="</list>";
	$f=fopen($file, "w");
	fwrite($f,$xml);
	fclose($f);

}
$filePath = "Minisite2.xlsx";
$count=readexcel($filePath);
$count=fittle_city($count);

savexml($count,'Minisite2.xml');
//saveexcel($count,'mytest.xlsx');
#


/*$handle = fopen('Minisite.csv', 'r');

$arr    = array();
while(($data = fgetcsv($handle, 1000, ',')) !== false) {
	$arr[] = array_filter($data);
}
fclose($handle);
$dom  = new DOMDocument( "1.0", "utf-8" );
$root = $dom->createElement('list');
unset($arr[0]);

foreach ($arr as $value) {
	if(empty($value)) {
		continue;
	}
	$domProvince = $dom->createElement('Province');
	$domProvince->setAttribute( "name", $value[1]);

	$domCity = $dom->createElement('City');
	$domCity->setAttribute( "name", $value[1]);
	
	$domShop = $dom->createElement('Shop');

	$domWsName = $dom->createElement('WS_NAME', $value[0]);
	$domAddress= $dom->createElement('ADDRESS', $value[2]);
	$domLine   = $dom->createElement('HOT_LINE', $value[3]);
	$domUrl    = $dom->createElement('URL', $value[4]);

	$domShop->appendChild($domWsName);
	$domShop->appendChild($domAddress);
	$domShop->appendChild($domLine);
	$domShop->appendChild($domUrl);

	$domCity->appendChild($domShop);

	$domProvince->appendChild($domCity);

	$root->appendChild($domProvince);
}

$dom->appendChild($root);
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$xml = $dom->saveXML();

// 导出
$fileName = 'export.xml';
$path     = '';
$handle   = fopen($path . $fileName, 'w');
fwrite($handle, $xml);
fclose($handle);*/

	$outputFileName = uniqid().'_.xls';
	$objWriter = PHPExcel_IOFactory::createWriter($resultPHPExcel, 'Excel2007');
	$objWriter->save($file);        
}
$filePath = "test.xls";
$count=readexcel($filePath);
echo "<pre>";
#print_r($count);
saveexcel($count,'mytest.xlsx');

