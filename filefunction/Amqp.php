<?php
/*
php安装amqp的依赖
在https://pecl.php.net/package/amqp
这个网站上找对应php的版本下载,
最低5.5因为5.4没有64位的包
然后把php_amqp.dll放到php/ext目录下在php.ini里面添加引用
把rabbitmq.4.dll放到system32目录下,
重启环境
*/
class Amqp{
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

