<?php
/*$arr1=array('id'=>1,"app_id"=>1,"type"=>0,"is_annex"=>0,"is_allday"=>"是","start_time"=>"2016-09-29 15:39:30","end_time"=>"2016-09-30 15:39:00","reason"=>1,"is_effect"=>1);
$arr2=array("app_i"=>2,"app_id"=>2,"type"=>0,"is_annex"=>1,"is_allday"=>"1","start_time"=>"2016-09-29 15:39:00","end_time"=>"2016-09-30 15:39:00","reason"=>1,"is_effect"=>1);
$arrs=array_diff_assoc($arr1,$arr2);
print_r($arrs);
$arr=json_encode(array_keys(array_diff_assoc($arr1,$arr2)));
print_r($arr);*/
/*function curl_post($url,$array=array()){

        $curl = curl_multi_init();
        //设置提交的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = $array;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
      //获得数据并返回
        return $data;
}*/
/*
$str="method=Apply-addApply&template_id=320&contents=%5b%7b%22date%22%3a%222016-11-01%22%2c%22time%22%3a%221%22%2c%22reason%22%3a%22ddd%22%2c%22relateusers%22%3a%221210%22%7d%5d&title=20161107_%e5%ae%81%e4%bd%90%e9%a3%9e_%e6%9c%aa%e6%89%93%e5%8d%a1%e7%94%b3%e8%af%b7%e5%8d%95&fldNo=456125&emp_id=1210";
$str="[{&quot;date&quot;:&quot;2016-11-01&quot;,&quot;time&quot;:&quot;1&quot;,&quot;reason&quot;:&quot;ddd&quot;,&quot;relateusers&quot;:&quot;1210&quot;}]";
var_dump( urldecode(($str)));*/
/*$str="&flow[]=it1210&flow[]=it1210";
echo(ltrim($str,'&'));*/
var_dump(md5(123456));
 $str='abcdefghijklmnopqrestuvwxyz';
 echo $str[rand(0,26)];