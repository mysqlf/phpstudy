<?php
$conn_args = array(  
    'host' => '121.42.184.241',   
    'port' => '5672',   
    'login' => 'lzyx',   
    'password' => 'lzyx16',  
    'vhost'=>'lzyx'  
);    
$e_name = 'lzyx_ch'; //交换机名  
$q_name = 'lzyx'; //无需队列名  
$k_route = 'lzyx_r'; //路由key  
  
//创建连接和channel  
$conn = new AMQPConnection($conn_args);    
if (!$conn->connect()) {
    die("Cannot connect to the broker!\n");    
}    
$channel = new AMQPChannel($conn);    
  
//消息内容  
$message = "TEST MESSAGE! 测试消息！";    
  
//创建交换机对象
$ex = new AMQPExchange($channel);    
$ex->setName($e_name);    
  
//发送消息  
//$channel->startTransaction(); //开始事务   
for($i=0; $i<5; ++$i){  
    echo "Send Message:".$ex->publish($message, $k_route)."\n";   
}  
//$channel->commitTransaction(); //提交事务  
  
$conn->disconnect();   