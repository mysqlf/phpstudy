<?php
$a='end';
$b='试验';
if ($a==$b) {
    echo 1;
}else{
    echo 2;
}
die();
/*function testmongodbAction(){
    $bulk = new MongoDB\Driver\BulkWrite;
    $document = ['_id' => new MongoDB\BSON\ObjectID, 'name' => '菜鸟教程'];
    $_id= $bulk->insert($document);

    var_dump($_id);

    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017"); 
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result = $manager->executeBulkWrite('test.runoob', $bulk, $writeConcern);
    var_dump($result);
}
testmongodbAction();*/
function getmon(){
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
var_dump($manager);
    // 插入数据
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert(['x' => 1, 'name'=>'菜鸟教程', 'url' => 'http://www.runoob.com']);
    $bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
    $bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
    $manager->executeBulkWrite('test.sites', $bulk);

    $filter = ['x' => ['$gt' => 1]];
    $options = [
        'projection' => ['_id' => 0],
        'sort' => ['x' => -1],
    ];

    // 查询数据
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('test.sites', $query);

    foreach ($cursor as $document) {
        print_r($document);
    }
}
getmon();
/*$a='123- 12/测试/';
$b='试';
print_r(strpos($a, $b));

$a='123';
$b='3';
print_r(strpos($a, $b));
die;
$a='123';
$b='456';
$tmp= $a.$b;
var_dump($tmp);*/
/*try{
    require_once "1231";
    print_r(123);
}catch(Exception $e){
    print_r(345);
}


die;*/
die();
$tmp=array('tel'=>'','mobile'=>'','short_mobile'=>'');
print_r(array_keys($tmp));
die;
$tmp1=array('tel'=>'1','short_mobile'=>'1');

$x=array_merge($tmp,$tmp1);
print_r($x);
die;
function getkeyofnovalue($data){
    $arr=array();
    foreach ($data as $key => $value) {
        if ($value==='') {
            $arr[$key]='';
        }
    }
    return $arr;
}
$arr=array("id"=>1,"app_id"=>1,"type"=>1,"is_annex"=>0,"is_allday"=>"是","start_time"=>"2016-09-29 15:39:00","end_time"=>"2016-09-30 15:39:00","reason"=>'',"is_effect"=>1);

$res=fitlearr($arr);
print_r($res);
die;
/*$str=json_encode($arr);
$arrs=json_decode($str,true);
var_dump($arrs);die;*/
/*$a=1;
$b=0;
try {
    if($b>0){
         }else{
        throw new \Exception("Error Processing 0", $b);
    }
        $k=$a/$b;
   

} catch (\Exception $e) {
    var_dump($e->getmessage());
}*/
die;
$a='key';
$b='zxc';
$k=$a ^ $b;
print_r($k);
echo "<br/>";
print_r($k^$b);
die;
$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
$a2=array("e"=>"red","f"=>"green","g"=>"blue");

$result=array_diff($a1,$a2);
print_r($result);
die;
var_dump( glob("./work/oa.*"));die;
#echo include "oatest.php";

/*$width=array('B'=>23,'C'=>12,'D'=>10,'E'=>23,'F'=>10,'G'=>10,'H'=>13);
foreach ($width as $key => $value) {
    var_dump($key,$value);
}*/

print_r(md5('xiaoxunce200:qq123456'));
$str=json_encode(array(array('name'=>'工本费' , 'total'=>2)) , JSON_UNESCAPED_UNICODE);
print($str);die;

$arr1=array(
    /*'0'=>array('a'=>2,'b'=>3),
    '1'=>array('a'=>2,'b'=>3),
    '2'=>array('a'=>2,'b'=>3),
    '3'=>array('a'=>2,'b'=>3),
    '4'=>array('a'=>2,'b'=>3),*/
    );
/*$arr2=array(
    '0'=>array('a'=>2,'b'=>3),
    '1'=>array('a'=>2,'b'=>3),
    '2'=>array('a'=>2,'b'=>3),
    '3'=>array('a'=>2,'b'=>3),
    '4'=>array('a'=>2,'b'=>3),
    );
$arr1=array_merge($arr1,$arr2);
print_r($arr1);*/

#占位符的数据填充 
// $str=vsprintf('%s123-%sqwert',explode('-','1981-81'));
// var_dump($str);
#string '1981123-81qwert' (length=15)
//echo(md5(md5(123456)));
function myfoo($x){
    if($x%2==1){
        return $x*$x;
    }else{
        return $x;
    }
}
$arr= array(1,2,3,4,5);
#array_map 与py中map方法一致
#遍历数组,将数组内的每一个值按照函数内的方法进行运算,再返回
#array_map所调用的函数有且只能有一个参数
#print_r(array_map('myfoo',$arr));
#echo "<br>";

function myfoo2($x,$y){
    if ($x>0) {
        #echo $x.'+'.$y."<br/>";
        return $x-$y;
    }else{
        #echo $x.'-'.$y."<br/>";
        return $x+$y; 
    }
    
}
#array_reduce使用的函数参数只能有两个,第一个参数起始为空用于暂存结果,数组遍历赋值给第二个参数
#且默认参数无效
$arr= array(100,100,1,1,1,1);
#print_r(array_reduce($arr,'myfoo2'));

#echo "<br/>";
#从指定位置开始返回数据类似于Py中list切片
#print_r(array_slice($arr,1));

function my_foo3($x){
    if ($x>50) {
        return true;
    }else{
        return false;
    }
}
#array_filter
#所指定的方法参数只能有一个
#根据指定方法筛选数组内的值
#去掉返回未false的值
#print_r(array_filter($arr,'my_foo3'));

$tmps=123;
/**
 * [get_variable_name 获取变量的名字]
 * @param  [] &$var  []
 * @return [str]        
 */
function get_variable_name(&$var){
    $tmp  = $var;
    $var   = "tmp_exists_x" . mt_rand();//产生一个随机数
    $name = array_search($var, $GLOBALS, TRUE);
    $var   = $tmp;
    return $name;
}

echo 123;

#var_dump(get_defined_vars());#get_defined_vars()获取当前的符号表
#$x=get_defined_vars($tmps);
#var_dump($x);
/*#var_dump($GLOBALS);
$name=get_variable_name($tmps);
#类似C指针的使用
print_r(get_variable_name($name));
#name
echo "<br/>";
print_r(get_variable_name($$name));
#tmps
echo "<br/>";
print_r(get_variable_name($$$name));
#123
echo "<br/>";
print_r($name);
#tmps
echo "<br/>";
print_r($$name);
#123*/
$n=100;
$m=50;
$arr=array();
/*function getonly($n,$m){

}*/

/*function kickMonkey($n, $m) {  
    $monkey = range(1, $n);         // 给猴子编号，生成数组  
    $i = 0;  
    while(list($key, $val) = each($monkey)) {       // each - 返回数组中当前的键／值对并将数组指针向前移动一步   
        if (count($monkey) == 1) {      // 剩最后一个猴子，你就是猴王了  
             echo  $val . '成为猴王了<br />';  
             exit;  
        }  
        if (++$i == $m) {  
            echo $monkey[$key] . '出局<br />';  
            unset($monkey[$key]);  
            $i = 0;  
        }  
        if (!current($monkey)) {        // 循环到头了，重置数组  
            reset($monkey);  
        }       
    }  
}  
kickMonkey(9,1);  */

try {
    print_r('123');
} catch (Exception $e) {
    print_r('456');
}


