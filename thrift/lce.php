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

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {
$socket = new THttpClient('localhost', 9090, '/lse.php');
//   $socket = new THttpClient('localhost', 8090, '/thrift/penngo/PhpMulServer.php');
  $transport = new TBufferedTransport($socket);
  $protocol = new TBinaryProtocol($transport);
  $loginProtocol = new TMultiplexedProtocol($protocol, 'LoginService');
  $loginService = new LoginServiceClient($loginProtocol);

  $user = $loginService->login('penngo', '123');
  var_dump($user);

  $registerProtocol = new TMultiplexedProtocol($protocol, 'RegisterService');
  $registerService = new RegisterServiceClient($registerProtocol);
  $user = $registerService->createUser('penngo', '123');
  var_dump($user);
//   $transport->close();
} catch (TException $tx) {
  print 'TException: '.$tx->getMessage()."\n";
}
