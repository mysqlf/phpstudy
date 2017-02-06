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

use hello\HelloWorldIf;
use Thrift\Transport\TPhpStream;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;

class HelloWorldHandler implements HelloWorldIf
{
    public function sayHello(){
        return 'Hello World';
    }
}

header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
    echo "\r\n";
}

$handler = new HelloWorldHandler();
$processor = new \hello\HelloWorldProcessor($handler);

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);

$transport->open();
$processor->process($protocol, $protocol);
$transport->close();
var_dump('ok');