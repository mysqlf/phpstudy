<?php
#一行一行读取,太慢不可取
/*$fp=fopen('php_error.log','r');
$line=1930554;
$pos=-2;
$t='';
$data='';

while ($line>0) {
    while ($t!="\n") {
        fseek($fp,$pos,SEEK_END);
        $t=fgetc($fp);
        $pos--;
    }
    $t='';
    $data.=fgetc($fp);
    $line--;
}
fclose($fp);
echo 1;*/
#echo $data;
#1930554
$file='php_error.log';
$fp=fopen($file,'r');
$num=1930;
$chunk=4096;
$fs=sprintf("%u",filesize($file));
$max=(intval($fs)==PHP_INT_MAX)?PHP_INT_MAX:filesize($file);
$readData='';
for ($len=0;$len<$max;$len+=$chunk){
    $seekSize=($max-$len>$chunk)?$chunk:$max-$len;
    fseek($fp,($len+$seekSize)*-1,SEEK_END);
    $readData=fread($fp,$seekSize).$readData;
    if (substr_count($readData,"\n")>$num+1) {
         preg_match("!(.*?\n){" . ($num) . "}$!", $readData, $match);
         $data=$match[0];
         break;
    }
}
fclose($fp);
echo $data;
