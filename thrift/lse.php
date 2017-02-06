<?php
namespace com\penngo;
error_reporting(E_ALL);
require_once __DIR__ . '/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(__DIR__.'/gen-php');
$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__);
$loader->registerDefinition('com', $GEN_DIR);
$loader->register();

if (php_sapi_name() == 'cli') {
  ini_set("display_errors", "stderr");
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\TMultiplexedProcessor;
use com\penngo\User;

class RegisterServiceHandler implements \com\penngo\RegisterServiceIf {
    public function createUser($name, $psw){
        $user = new User();
        $user->id = 2;
        $user->name = $name;
        $user->password = $psw;
        return $user;
    }
};
class LoginServiceHandler implements \com\penngo\LoginServiceIf {
    public function login($name, $psw){
        $user = new User();
        if($name == 'penngo' && $psw == '123'){
            $user->id = 1;
            $user->name = 'penngo';
        }
        return $user;
    }
};
header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
  echo "\r\n";
}

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);
$tMultiplexedProcessor = new TMultiplexedProcessor();

$handler = new LoginServiceHandler();
$loginServiceProcessor = new LoginServiceProcessor($handler);
$tMultiplexedProcessor->registerProcessor("LoginService", $loginServiceProcessor);

$registerService = new RegisterServiceHandler();
$registerServiceProcessor = new RegisterServiceProcessor($registerService);
$tMultiplexedProcessor->registerProcessor("RegisterService", $registerServiceProcessor);
$transport->open();
$tMultiplexedProcessor->process($protocol, $protocol);
$transport->close();