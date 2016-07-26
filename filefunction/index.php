<?php
header('Content-Type: text/html; charset=UTF-8');
$file=fopen('test.txt',"r+");
fwrite($file, 'foo');//多次添加将出现乱码
fflush($file);
// while (!feof($file)) {
//     $content[]=fgetss($file);//读取文件去掉html和php标签
// }
//print_r($content);
//$arr=file('test.txt');//直接读取文件返回数组,以行为分割
//print_r($arr);
//print_r(disk_free_space('C:')/(1024*1024));
//print_r(copy('test.txt',file_exists('test1.txt')?time().'.txt':'1.txt'));//copy(源文件,目标文件);会将目标文件强制覆盖
/*$x=0;
$y=0;
$z=1;
#feof 检测是否到达文件尾部
$ot=array();
$arrs=array();
$arr=array();
while (!feof($file)) {
    #fgets 获取文件中的一行
    #echo fgets($file),"<br/>";
    #fgetc 获取一个字节(所以读取中文会乱码)
    #echo fgetc($file),"<br/>";
    $arr[$i]=fread($file,3);//读取中文
    $i++;
    
    $arr[]=ftell($file);
}
print_r($ot);
print_r($arrs);
print_r($arr);*/
fclose($file);
?>