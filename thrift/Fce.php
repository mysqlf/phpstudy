<?php
namespace langzi;
error_reporting(E_ALL);
require_once __DIR__ . '/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(__DIR__.'/gen-php');

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__);
$loader->registerDefinition('langzi', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {
$socket = new THttpClient('localhost', 9090, '/Fse.php');
//   $socket = new THttpClient('localhost', 8090, '/thrift/penngo/PhpMulServer.php');
  $transport = new TBufferedTransport($socket);
  $protocol = new TBinaryProtocol($transport);
  $upNameProtocol = new TMultiplexedProtocol($protocol, 'upName');
  $upName = new upNameClient($upNameProtocol);

  $user = $upName->EditName(12, 'zl');
  var_dump($user);

  $upAddressProtocol = new TMultiplexedProtocol($protocol, 'upAddress');
  $upAddress = new upAddressClient($upAddressProtocol);
  $user = $upAddress->EditAddress(12, 'ld');
  var_dump($user);
//   $transport->close();
} catch (TException $tx) {
  print 'TException: '.$tx->getMessage()."\n";
}
