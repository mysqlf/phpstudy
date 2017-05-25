<?php
$arr=array(
    'a'=>array(1,4),
    'b'=>array(3,5),
    'c'=>array(0,6),
    'd'=>array(5,7),
    'e'=>array(3,8),
    'f'=>array(5,9),
    'g'=>array(6,10),
    'h'=>array(8,11),
    'i'=>array(8,12),
    'j'=>array(2,13),
    'k'=>array(12,14)
    );
function max_active($arr){
    $end=0;
    $start=0;
    $active=array();
    foreach ($arr as $key => $value) {
        if ($value[0]>$end) {#开始时间大于当前活动的结束时间
            $end=$value[1];#更新结束时间为当前活动结束时间
            $active[]=$key;#记录活动
        }
    }
    return $active;
}
print_r(max_active($arr));
#贪心算法可以先将要处理的进行一次排序
#然后根据限制条件来一步步加入

