<?php
//生成href链接
function H($type,$cid,$id,$mid){
    switch ($type) {
        case 1:
            return U('/page/'.(string)$cid);
            break;
        case 2:
            return U('list/'.(string)$cid);
            break;
        case 3:
            return U('Content/'.(int)$cid.'/'.(int)$id);
            break;
        default:
            return "#";
            break;
    }
}
//获取数据内容中的图片，默认获取第一张，参数二位ALL获取全部
function con_img($content,$order=0){
    $pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
    preg_match_all($pattern,$content,$match);
    if(isset($match[1])&&!empty($match[1])){
        if($order==='ALL'){
            return $match[1];
        }
        if(is_numeric($order)&&isset($match[1][$order])){
            return $match[1][$order];
        }
    }
    return '';
}