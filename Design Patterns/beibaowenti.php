<?php
#背包问题思路
#先将P/V比算出来,按照p/v排序
#然后循环取宝石,体积超过背包剩余容量则抛弃
$arr=array(
    array('n'=>'a','v'=>'2','p'=>'5'),
    array('n'=>'b','v'=>'4','p'=>'5'),
    array('n'=>'c','v'=>'5','p'=>'5'),
    array('n'=>'d','v'=>'13','p'=>'150'),
    array('n'=>'e','v'=>'3','p'=>'5'),
    array('n'=>'f','v'=>'10','p'=>'5'),
    array('n'=>'g','v'=>'4','p'=>'15'),
    array('n'=>'h','v'=>'20','p'=>'5'),
    );


/**
 * [sort_pv 计算价值比并排序]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-25
 * @param    [type]     $arr  [description]
 * @param    [type]     $sort [description]
 * @return   [type]           [description]
 */
function sort_pv($arr,$sort){
    foreach ($arr as $key => $value) {
        $arr[$key]['pv']=$value['p']/$value['v'];
    }
    $arrSort=array();
    #先将数组拆分为多个一维数组
    foreach($arr AS $uniqid => $row){
        foreach($row AS $key=>$value){
            $arrSort[$key][$uniqid] = $value;
        }
    }
    if($sort['direction']){
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arr);
    }
    return $arr;
}
/**
 * [best 背包求解]
 * @author Greedywolf 1154505909@qq.com
 * @DateTime 2017-05-25
 * @param    [type]     $arr [description]
 * @return   [type]          [description]
 */
function best($arr){
    $max=15;
    $count=count($arr);
    $wig=0;
    $res=array();
    for ($i=0; $i < $count; $i++) { 
        if ($arr[$i]['v']<=($max-$wig)) {
            $res[]=$arr[$i]['n'];
            $wig=$wig+$arr[$i]['v'];
        }
    }
    return $res;
}
$sort=array(
    'direction' => 'SORT_DESC', 
     //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
    'field'     => 'pv',
    //排序字段
    );

$sortpv=sort_pv($arr,$sort);
$best=best($sortpv);
print_r($best);

//var_dump($arrUsers);
