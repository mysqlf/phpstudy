<?php
function curl_post($url,$array=array()){

        $curl =curl_init();
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
}
$url="http://192.168.0.37:6666/api/oa/doc/getDocInfo";
$data=array(
    'doc_id'=>455464,
    'user'=>'oa4609',
    'action'=>2
    );
$result=curl_post($url,$data);
var_dump(json_decode($result,true));