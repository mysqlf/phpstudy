<?php
/*$client = new SoapClient("http://61.187.115.171/CAwebservice/HuncaSVSService?wsdl",array('encoding'=>'UTF-8'));
$ca_cert ='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';
$ca_sign ='Tz1V4nqCn/S3gV7ct+4Yy12ybkjJzJa5EB5NqFY1NyNOqcaFzdpjokYZyAtlffeCzYmsXxlog9zlG8HHITO+54aBOX8S2WKOcSeTVxrjhq/zcJ85lbYSwZqNoTgyG+K2vN0f2DKyBVeThY3I+m91IB0BjyrNlf7/BE5g7gKfotNrpQQ8KlNzomb4pDHng7QOrdfOw4T9YuV/rFRlYCEHmMewWPdXjM6us+huy4z1ZXDxD3jsYjavBGZXR381jOeMSwJBeNB/45FuIgamLyXhldB7VzmARhBzZgDIMNEpz+uh/8oSvSz3qpu7LaRuCWkHHZONpI1klIk64O81QjsgEA==';
$ca_data ='FqWzur';
$arr=array('arg0'=>$ca_cert,'arg1'=>$ca_data,'arg2'=>$ca_sign);
var_dump($client->__getFunctions());
var_dump($client->__getTypes());
$result=$client->HuncaVerify($arr);
var_dump($result);
?>*/

set_time_limit(0);
/*$socket = fsockopen('192.168.2.19','6195',$error,$errorstringfsockopen,20);
var_dump($socket);
var_dump(get_resource_type($socket));
var_dump(stream_get_contents($socket));*/
/*$x=stream_socket_client('192.168.2.19:6195',$error,$errorstringfsockopen,20);
var_dump($x);
var_dump(get_resource_type($x));
var_dump(stream_get_contents($x));*/

/*$host = "192.168.2.19";
$port = 6195;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$connection = socket_connect($socket, $host, $port);
//socket_write($socket, "hello socket") or die("Write failed\n");

var_dump(get_resource_type($socket));
var_dump(socket_read($socket,4));*/
function test(){
    for ($i=0; $i <10; $i++) { 
        $x = (yield $i);
    }
}
$k=test();
foreach ( $k as  $value) {
    print_r($value);
}