<?php
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
echo "<br>";

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
print_r(array_reduce($arr,'myfoo2'));

echo "<br/>";
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
print_r(array_filter($arr,'my_foo3'));
?>
