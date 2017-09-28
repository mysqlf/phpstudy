<?php
// Server
// task 客户端与服务端需要保持一致,需要进行绑定才行
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
            'debug_mode'=> 1,
            'task_worker_num'=>8
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
          $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));

        $this->serv->start();
    }

    public function onStart( $serv ) {
        echo "Start\n";
    }

    public function onConnect( $serv, $fd, $from_id ) {
        $serv->send( $fd, "Hello {$fd}!" );
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        echo "Get Message From Client {$fd}:{$data}\n";
        $param=array('fd'=>$fd);
        $serv->task(json_encode($param));
        echo "continue handle worker";
    }
    public function onTask($serv, $task_id, $from_id, $data ){
        echo "This Task {$task_id} from Worker {$from_id} \n";
        echo "Data :{$data}\n";
        for ($i=0; $i < 10; $i++) { 
            sleep(1);
            echo "task {$task_id} handle {$i} times..\n";
        }
        $fd=json_decode($data,true)['fd'];
        $serv->send($fd,"data in task {$task_id}");
        return "task {$task_id}`s result";
    }
    public function onFinish($serv,$task_id,$data){
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }
    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }
}
// 启动服务器
$server = new Server();
