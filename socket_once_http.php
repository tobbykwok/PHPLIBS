<?php
define('TARGET_HOST', 'www.baidu.com');
error_reporting(E_ALL);

echo "TCP/IP Connection";

$port = getservbyname('www', 'tcp');
$address = gethostbyname(TARGET_HOST);

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if($socket === false){
	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
	echo "OK.\n";
}

echo "Attempting to connect to {$address} on port {$port}";

$result = socket_connect($socket, $address, $port);

if($result === false){
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "HEAD / HTTP/1.1\r\n";
$in .= "Host: ".TARGET_HOST."\r\n";
$in .= "Connection: Close\r\n\r\n";
$out = '';

echo "Sending HTTP HEAD request...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";