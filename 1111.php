<?php
set_time_limit(0);
$socket = fsockopen('192.168.2.19','6195',$error,$errorstringfsockopen,20);
var_dump($socket);
var_dump(get_resource_type($socket));
var_dump(stream_get_contents($socket));
$x=stream_socket_client('192.168.2.19:6195',$error,$errorstringfsockopen,20);
var_dump($x);
var_dump(get_resource_type($x));
var_dump(stream_get_contents($x));

$host = "192.168.2.19";
$port = 6195;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);