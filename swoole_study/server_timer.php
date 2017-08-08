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
           swoole_timer_tick(1000, function ($work_id) {
                echo "tick-2000ms\n";
            });
        }
    }
    public function onTimer($serv,$interval){
        switch ($interval) {
            case 5:{  // 
                echo "Do Thing A at interval 500\n";
                break;
            }
            case 10:{
                echo "Do Thing B at interval 1000\n";
                break;
            }
            case 15:{
                echo "Do Thing C at interval 1500\n";
                break;
            }
        }
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
