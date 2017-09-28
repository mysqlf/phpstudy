<?php

#创建对象 
$url="http://192.168.2.19/apiws/services/API?wsdl"
$client = new SoapClient($url,array('encoding'=>'UTF-8'));
#参数
$arr=array('user_at_domain'=>'chao.wen@chitone.com.cn');
#调用方法
#getNewMailInfos方法名根据文档来
$result=$client->getNewMailInfos($arr);
#返回参数解析---具体看对方参数形式,我这个是因为接口给我的是经过urlcode过的
$content=urldecode(urldecode($result->return->result));

/*$pat='/&subject=(.*?)&size=/';
preg_match_all($pat, $content, $m);
var_dump($m);die;*/
$pat='/&from=(.*?)&to=.*?&subject=(.*?)&size=.*?&date=(.*?)&/';
preg_match_all($pat, $content, $m);
var_dump($m);
//print_r($content);
#The C compiler identification is unknown
#-- The CXX compiler identification is unknown

/*var_dump($client->__getFunctions());
var_dump($client->__getTypes());*/
/*
$arr=explode('msid',$content);
foreach ($arr as $key => $value) {
    echo "<br>";
    $title[]=explode('&subject=',$value);

}
//var_dump($title);
foreach ($title as $key => $value) {
    if(count($title[$key])==2){
        $tt[]=explode('&size=',$title[$key][1]);
    }
    
}




var_dump($tt);*/
//print_r($title);
//
/*var_dump( substr('',0,450));
$data=array(array('123'));
var_dump(is_array($data[0]));
$data=array('123');
var_dump(is_array($data[0]));*/
/*
$arr=array('b'=>102,'a'=>100,'c'=>100);

foreach ($arr as $key => $value) {
    $tmp[]=$key;
    $tmp1[]=$value;
}

var_dump($tmp);
var_dump($tmp1);*/


/*try{
    require_once "1231";
    print_r(123);
}catch(Exception $e){
    print_r(345);
}*/
//die;
//echo date('Y-m-d H:i:s',strtotime('2016-11-11T17:23:00'));
//phpinfo();
//连接RabbitMQ
/*$conn_args = array( 'host'=>'192.168.2.50' , 'port'=> '5672', 'login'=>'oa' ,
'password'=> '95105333','vhost' =>'oa');
$conn = new AMQPConnection($conn_args);
if ($conn->connect()) {
    echo "Established a connection to the broker \n";
}else {
    echo "Cannot connect to the broker \n ";
}
$e_name='oa';//交换机名横
$q_name='queue_test';//队列名称
$r_key='oa_test';


//创建channel
$channel = new AMQPChannel($conn);
//创建exchange
$ex = new AMQPExchange($channel);
$ex->setName($e_name);//创建名字
$ex->setType(AMQP_EX_TYPE_DIRECT);
$ex->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
echo "exchange status:".$ex->declare();
echo "\n";
//创建队列
$q = new AMQPQueue($channel);
//设置队列名字 如果不存在则添加
$q->setName($q_name);
$q->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
echo "queue status: ".$q->declare();
echo "\n";
echo 'queue bind: '.$q->bind($e_name,$r_key);//将你的队列绑定到routingKey
echo "\n"; 

$channel->startTransaction();
//你的消息
$message = json_encode(array('Hello World!'));
echo "send: ".$ex->publish($message, $r_key); //将你的消息通过制定routingKey发送
$channel->commitTransaction();
$conn->disconnect();



$conn = new AMQPConnection($conn_args);
$conn->connect();
//设置queue名称，使用exchange，绑定routingkey
$channel = new AMQPChannel($conn);
$q = new AMQPQueue($channel);
$q->setName($q_name); 
$q->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
$q->declare();
$q->bind($e_name, $r_key);
//消息获取
$messages = $q->get(AMQP_AUTOACK);
if ($messages){
      var_dump(json_decode($messages->getBody(), true ));
}
$conn->disconnect();*/


?>
