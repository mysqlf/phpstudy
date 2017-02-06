<?php
error_reporting(E_ALL);
require_once __DIR__ . '/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(__DIR__.'/gen-php');

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__);
$loader->registerDefinition('hello', $GEN_DIR);
$loader->register();

if (php_sapi_name() == 'cli') {
    ini_set('display_errors', 'stderr');
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

use hello\HelloWorldClient;

try{
    $socket = new THttpClient('localhost', 9090, '/se.php');
    $transport = new TBufferedTransport($socket, 1024,1024);
    $protocol = new TBinaryProtocol($transport);
    $client = new \hello\HelloWorldClient($protocol);

    $transport->open();
    $result = $client->sayHello();
    echo $result;
    $transport->close();
}catch (TException $e){
    echo  'TException: '.$e->getMessage()."\n";
}