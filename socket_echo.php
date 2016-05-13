<?php
if(PHP_SAPI != 'cli'){
	echo "Needs CLI environment";
	exit;
}

error_reporting(E_ALL);

set_time_limit(0);
ob_implicit_flush();

$address 	= '127.0.0.1';
$port		= 8899;

if(($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false){	//IPV4 TCP 连接
	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . PHP_EOL;	//读取错误内容
}

if(socket_bind($sock, $address, $port) === false){						//绑定端口
	echo "socket_bind() failed: reason: ". socket_strerror(socket_last_error()) . PHP_EOL;
}

if(socket_listen($sock, 5) === false){
	echo "socket_listen() failed: reason: ". socket_strerror(socket_last_error()) . PHP_EOL;
}

do{
	if(($msgsock = socket_accept($sock)) === false){
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
	}
	$msg = PHP_EOL."Welcome to the PHP Test Server.";
	socket_write($msgsock, $msg, strlen($msg));
	
	do{
		if(false == ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))){
			echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
		}
		if(!$buf = trim($buf)){
			continue;
		}
		if($buf == 'quit'){
			break;
		}
		if($buf == 'shutdown'){
			socket_close($msgsock);
		}
		$talkback = "YOU SAID: ". $buf.PHP_EOL;
		socket_write($msgsock, $talkback, strlen($talkback));
		echo $buf.PHP_EOL;
	} while(true);
	socket_close($msgsock);
} while(true);

socket_close($sock);