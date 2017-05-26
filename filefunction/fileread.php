<?php
$dir=dirname(__FILE__)."/../";//这里输入其它路径
//PHP遍历文件夹下所有文件
$handle=opendir($dir.".");
//定义用于存储文件名的数组
$array_file = array();
while (false !== ($file = readdir($handle)))
{
if ($file != "." && $file != "..") {
$array_file[] = $file; //输出文件名
}
}
closedir($handle);
#print_r($array_file);

function readfiles($dir){
    if (is_file($dir)) {
        return $dir;
    }
    $file_array=array();
    $handle=opendir($dir);
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != "..") {
            $filename=$dir.'/'.$file;
            if (is_dir($filename)) {
                $file_array=array_merge($file_array,readfiles($filename));
            }else{
                $file_array[]=$filename;
            }
        }
    }
    closedir($handle);
    return $file_array;
}

print_r(readfiles(".."));
function get_files($dir) {
    $files = array();
 
    if(!is_dir($dir)) {
        return $files;
    }
 
    $handle = opendir($dir);
    if($handle) {
        while(false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $filename = $dir . "/"  . $file;
                if(is_file($filename)) {
                    $files[] = $filename;
                }else {
                    $files = array_merge($files, get_files($filename));
                }
            }
        }   //  end while
        closedir($handle);
    }
    return $files;
} 

#print_r(get_files(".."));
