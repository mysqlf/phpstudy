<?php
class Client
{
    private $client;

    public function __construct() {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP);
    }

    public function connect() {
        if( !$this->client->connect("127.0.0.1", 9501 , 1) ) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
        }
        $message = $this->client->recv();
        echo "Get Message From Server:{$message}\n";
        #fwrite(STDOUT, "请输入消息：");  
        #$msg = trim(fgets(STDIN));
        $msg=rand(1,15);
        $this->client->send( $msg );
    }
}


$i=0;
while ($i <= 10) {
    $client = new Client();
    $client->connect();
    sleep(2);
    $i++;
}

