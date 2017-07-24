<?php

#移动并写入新记录
function move($a,$time){
    $a[2]=$a[1];
    $a[1]=$a[0];
    $a[0]=$time;
    return $a;
}
#踏板状态返回
function flag($file){
    $time=time();
    #读取文件内容
    $a=read_file($file);
    if (!empty($a[2])&&$time-$a[2]<60) {
        return true;#如果有三条记录,并且最后一条记录在60秒以内
    }else{
        return false;#没有三条记录,或者最后一条记录在60秒以外
    }
}
#读文件
function read_file($file){
    #打开文件
    $f=fopen($file, 'r');
    #读取一行
    $str=fread($f,255);
    #关闭文件
    fclose($f);
    #将字符转为数组
    $a=explode(';', $str);
    if (empty($a[0])) {
        $a=array(1,1,1);
    }
    #返回数组
    return $a;
}
#写文件
function write_file($file,$a){
    #打开文件
    $f=fopen($file,'w');
    #将数组转为字符串
    $str=implode(';',$a);
    #将字符串写入文件
    fwrite($f,$str);
    #关闭文件
    fclose($f);
}


#踏板监听调用程序
function push($new,$file){
    #如果是高位信号
    if ($new==1) {
        #读取原来的数据
        $a=read_file($file);
        #移动数据,将新的数据写回数据
        $a=move($a,time());
        #写文件记录
        write_file($file,$a);
    }
    #写log
   
}
#发动机监听调用程序
function fdj($message,$file){
    #当发动机是高位的时候
    if ($message==1) {
        #获取踏板状态
        $flag=flag($file);
        #踏板在60秒内有3个高位信号
        if ($flag==true) {
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
$i=0;
while ($i<10) {
    $file='./tmp.txt';
    sleep(1);
   /* if ($i==0) {
        echo 
    }*/
    $fdj=rand(0,1);
    $tb=rand(0,1);
    push($tb,$file);
    $tmp=fdj($fdj,$file);
    $t_f=$fdj==1?'  |':'|  ';
    $t_t=$tb==1?'  |':'|  ';
    $t_flag=$tmp==1?'  |':'|  ';
    echo $i.$t_f,$t_t,$t_flag."\n";
    $i++;
}
