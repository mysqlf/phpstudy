<?php
// Server
class Server
{
    private $serv;

    public function __construct() {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->start();

    }
    public function onWorkerStart($serv,$work_id){
        if ($work_id==0) {
            #定时器需要放在回调函数内设置
            #方法不再是Timer
            #可以使用闭包形式
            swoole_timer_tick(1500,function ($work_id) {
                echo "tick-1500ms\n";
            });
            #也可使用回调形式
           swoole_timer_tick(1000, array($this,'onTest'));
        }
    }
    public function onTest(){
        #function ($work_id) {
                echo "tick-1000ms\n";
        #    }
    }
    public function onStart( $serv ) {
        echo "Start\n";
    }

    public function onConnect( $serv, $fd, $from_id ) {
        $serv->send( $fd, "Hello {$fd}!" );
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
    }

    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }
}
// 启动服务器
$server = new Server();
