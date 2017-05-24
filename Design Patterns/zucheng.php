<?php
$a=6;
/*
    5+1
    4+2 4+1+1
    3+3 3+2+1 3+1+1+1
    2+2+2 2+2+1+1 2+1+1+1+1
    1+1+1+1+1+1
*/
function chai($a){
    for ($i=0; $i <$a; $i++) { 
        $arr[$i][0]=1;
        $arr[$i][1]=1;
    }
    for ($i=2; $i <=$a; $i++) { 
        for ($j=0; $j <=$i ; $j++) { 
            if ($i+$j>$a) {
                continue;
            }
            $count=0;
            for ($k=1; $k <=$j ; $k++) { 
                $count+=$arr[$i-$k][($i-$k>=$k)?$k:$i-$k];
            }
            $arr[$i][$j]=$count;
        }
    }
    $res=0;
    print_r($arr);
    for ($k=1; $k <$a ; $k++) { 
       
        $res += $arr[$a-$k][($a-$k>=$k)?$k:$a-$k];
    }
    return $res;
    //return $arr;
}
$a=6;
#var_dump(chai($a));
#减去的因子至少要有2个才进入否则直接跳出
#
#如果相等才可以写入
function build($a){
    $build=array();
    for($bi=1;$bi<$a;$bi++){
        $ceil=floor($a/$bi);
        if($ceil>1){#必须要有两个,否则在前面就会被重复
            //$tmp_bi=$bi;
            for($tmp=1;$tmp<=$a;$tmp++){
                for ($bian=0; $bian <$a ; $bian++) { 
                    $count=(($ceil-$tmp)*$bi+$bian);
                    if($count==$a){
                        $str='';
                        $xh=$ceil-$tmp;
                        for($i=1;$i<=$xh;$i++){
                            $str.=$bi.'+';
                        }
                        $str.=$bian;
                        if (!in_array($str,$build)) {
                            array_push($build, $str);
                        }
                    }
                }
            }
        }
    }
    return $build;
}
/*$res=build(6);
var_dump($res);
$res=build(7);
var_dump($res);
$res=build(3);
var_dump($res);
$res=build(2);
var_dump($res);*/

function splnumber($a){
    return splitnumberwithmax($a,$a,0,$a.'=');
}
function splitnumberwithmax($max,$tag,$c,$str){
    if ($tag==1||$tag==0) {
        #截掉多余的+号
        #或者直接拼接1因为1和0不可能再拆解
        print_r(($tag==1)?($str.$tag):substr($str, 0,strlen($str)-1));
        echo "\n";
        $c++;
    }else{
        #这里才是起点
        for ($i=($max>=$tag?$tag:$max); $i >0 ; $i--) {
            $c=splitnumberwithmax($i,$tag-$i,$c,$str.$i.'+');
        }
    }
     return $c;
}
//print_r(splnumber(10));

$a=array(2,6);
$b=array(2,3);
$c=array(6,5);
$d=array(5,4);
$e=array(4,6);
$arr=array(
    'a'=> array(2,6),
    'b'=> array(2,3),
    'c'=> array(6,5),
    'd'=> array(5,4),
    'e'=> array(4,6),
    );

$res=best($arr);
print_r($res);
