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
if (php_sapi_name() == 'cli') {
  ini_set("display_errors", "stderr");
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;
use Thrift\TMultiplexedProcessor;
use langzi\Member;

class upNameHandler implements \langzi\upNameIf {
    public function EditName($id, $name){
        $Member = new Member();
        $Member->id = $id;
        $Member->name = $name;
        return $Member;
    }
};
class upAddressHandler implements \langzi\upAddressIf {
    public function EditAddress($id, $address){
        $Member = new Member();
        $Member->id = $id;
        $Member->address = $address;
        return $Member;
    }
};
header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
  echo "\r\n";
}

$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);
$tMultiplexedProcessor = new TMultiplexedProcessor();

$handler = new upNameHandler();
$upNameProcessor = new upNameProcessor($handler);
$tMultiplexedProcessor->registerProcessor("upName", $upNameProcessor);

$upAddress = new upAddressHandler();
$upAddressProcessor = new upAddressProcessor($upAddress);
$tMultiplexedProcessor->registerProcessor("upAddress", $upAddressProcessor);
$transport->open();
$tMultiplexedProcessor->process($protocol, $protocol);
$transport->close();