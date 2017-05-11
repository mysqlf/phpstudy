<?php
header('Content-Type: text/html; charset=UTF-8');
$file=fopen('test.txt',"w");
fwrite($file, 'foo1123');//多次添加将出现乱码
// fflush($file);
fclose($file);
/*$time=filemtime('test.txt');
var_dump($time);
$hand=fopen('test.txt','r');
$x=fread($hand,filesize('test.txt'));
print($x);*/
//fclose($hand);
// while (!feof($file)) {
//     $content[]=fgetss($file);//读取文件去掉html和php标签
// }
//print_r($content);
//$arr=file('test.txt');//直接读取文件返回数组,以行为分割
//print_r($arr);
//print_r(disk_free_space('C:')/(1024*1024));
//print_r(copy('test.txt',file_exists('test1.txt')?time().'.txt':'1.txt'));//copy(源文件,目标文件);会将目标文件强制覆盖
/*$x=0;
$y=0;
$z=1;
#feof 检测是否到达文件尾部
$ot=array();
$arrs=array();
$arr=array();
while (!feof($file)) {
    #fgets 获取文件中的一行
    #echo fgets($file),"<br/>";
    #fgetc 获取一个字节(所以读取中文会乱码)
    #echo fgetc($file),"<br/>";
    $arr[$i]=fread($file,3);//读取中文
    $i++;
    
    $arr[]=ftell($file);
}
print_r($ot);
print_r($arrs);
print_r($arr);*/


//
/*$text = 'John ';
$text[10] = 'Doe';
strlen($text);
var_dump($text);*/


class AmqpModel{
    #初始化建立连接
    public function __construct(){
        #服务器基本配置
        $conn_args = array( 'host'=>'192.168.2.50' , 'port'=> '5672', 'login'=>'oa' ,
'password'=> '95105333','vhost' =>'oa');//C('AMQP');
        $this->conn = new AMQPConnection($conn_args);
        #连接
        if ($this->conn->connect()) {
            #创建信道
            $this->channel = new AMQPChannel($this->conn);
            #创建交换器
            $this->ex = new AMQPExchange($this->channel);
            #创建队列
            $this->queue = new AMQPQueue($this->channel);
        }else {
            return false;
        }
    }

    /**
     * [set_exchange 设置交换器]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     * @param    [type]     $e_name [交换器名]
     */
    private function set_exchange($e_name){
        #交换器名字
        $this->ex->setName($e_name);//创建名字
        $this->ex->setType(AMQP_EX_TYPE_DIRECT);
        $this->ex->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
    }
    /**
     * [set_queue 创建队列绑定信道]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     * @param    [type]     $qname  [队列名]
     * @param    [type]     $e_name [交换器名]
     * @param    [type]     $r_key  [description]
     */
    private function set_queue($q_name,$e_name,$r_key){
        //设置队列名字 如果不存在则添加
        $this->queue->setName($q_name);
        $this->queue->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
        $this->queue->bind($e_name,$r_key);//将你的队列绑定到routingKey
    }
   /**
    * [publish_message 发送消息]
    * @author Greedywolf 1154505909@qq.com
    * @DateTime 2016-11-22
    * @param    [type]     $message [消息体]
    * @param    [type]     $q_name  [队列名]
    * @param    string     $e_name  [交换器名]
    * @param    string     $r_key   [路由]
    * @return   [type]              [description]
    */
    public function publish_message($message,$q_name,$e_name='oa',$r_key='oa_test'){
        #设置交换器
        self::set_exchange($e_name);
        #设置队列
        self::set_queue($q_name,$e_name,$r_key);
        #数据格式化
        $message = json_encode($message);
        $this->ex->publish($message, $r_key); //将你的消息通过制定routingKey发送

    }
    /**
     * [getmessage description]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     * @param    [type]     $q_name [description]
     * @param    string     $e_name [description]
     * @param    string     $r_key  [description]
     * @return   [type]             [description]
     */
    public function getmessage($q_name,$e_name='oa',$r_key='oa_test'){
        #设置队列
        self::set_queue($q_name,$e_name,$r_key);
        #读取消息
        $messages = $this->queue->get(AMQP_AUTOACK);
        if ($messages){
              $content[]=json_decode($messages->getBody(), true );
        }
        return $content;
    }
    /**
     * [__destruct 析构]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     */
    public function __destruct(){
        #释放连接
        $this->conn->disconnect();

    }
}
$test=new AmqpModel();
$message=array('gaodinglea_1askkaksdasd');
//$test->publish_message($message,'queue_test');
$result=$test->getmessage('queue_test');
var_dump($result);
?>