<?php
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

?>
