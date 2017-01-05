<?php
/*$conn_args = array(  
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
  
$conn->disconnect();   */

#安装方法
#环境 win7 php7
#集成环境下
#下载与php版本对应的amqp版本
#将php_amqp.dll 复制到etc 目录
#然后在apache目录下的php.ini添加extension
#再将rabbit.4.dll复制到system32目录下
#重启环境即可

class Amqp{
    #初始化建立连接
    public function __construct(){
        $conn_args = array(  
            'host' => '121.42.184.241',   
            'port' => '5672',   
            'login' => 'lzyx',   
            'password' => 'lzyx16',  
            'vhost'=>'lzyx'  
        );
        $this->conn = new \AMQPConnection($conn_args);
        #连接
        if ($this->conn->connect()) {
            #创建信道
            $this->channel = new \AMQPChannel($this->conn);
            #创建交换器
            $this->ex = new \AMQPExchange($this->channel);
            #创建队列
            $this->queue = new \AMQPQueue($this->channel);
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
        $this->ex->setType(AMQP_EX_TYPE_DIRECT);//TOPIC//DIRECT
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
    * @param    [array]     $message [消息体]
    * @param    [type]     $q_name  [队列名]
    * @param    string     $r_key   [路由]
    * @param    string     $e_name  [交换器名]
    * @return   [type]              [description]
    */
    public function publish_message($message,$q_name,$r_key='oa_email',$e_name='oa'){
        #设置交换器
        self::set_exchange($e_name);
        #设置队列
        self::set_queue($q_name,$e_name,$r_key);
        #数据格式化
        $message = json_encode($message);
        $this->ex->publish($message, $r_key); //将你的消息通过制定routingKey发送
    }
    /**
     * [getmessage 读取消息]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     * @param    [type]     $q_name [description]
     * @param    string     $r_key  [description]
     * @param    string     $e_name [description]

     * @return   [type]             [description]
     */
    public function getmessage($q_name,$e_name='oa',$r_key='oa_chitone')
    {
        #设置交换器
        self::set_exchange($e_name);
        #设置队列
        self::set_queue($q_name,$e_name,$r_key);
        #读取消息
        $messages = $this->queue->get(AMQP_AUTOACK);
        if ($messages){
            $content=json_decode($messages->getBody(),true);
            $this->ex->publish($content, $r_key);
            return $content;
        }
    }
    /**
     * [__destruct 析构函数]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2016-11-22
     */
    public function __destruct(){
        #释放连接
        $this->conn->disconnect();

    }
}
